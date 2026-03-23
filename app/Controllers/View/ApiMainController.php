<?php

declare(strict_types=1);

namespace App\Controllers\View;

use App\Models\Photo;
use App\Models\Video;
use App\Models\Note;
use core\Request;

/**
 * MainController — главный контроллер API.
 *
 * Маршруты:
 *   GET /api/index  — объединённая лента (photos + videos + notes)
 *   GET /api/photos — только фото
 *   GET /api/videos — только видео
 *   GET /api/notes  — только заметки
 */
class ApiMainController
{
    private Photo $photos;
    private Video $videos;
    private Note $notes;

    private \PDO $db;

    public function __construct()
    {
        $this->photos = new Photo();
        $this->videos = new Video();
        $this->notes  = new Note();
        $this->db = \core\Database::getInstance()->getConnection();
    }

    // ------------------------------------------------------------------ //
    //  Публичные action
    // ------------------------------------------------------------------ //

    /** GET /api/index */
    public function index(Request $request): void
    {
        [$limit, $offset] = $this->pagination($request);

        $sql = "
        SELECT
        p.id, p.title, p.description, p.created_at, 'photo' AS type,
        p.url, p.thumbnail, p.width, p.height, p.size,
        u.id   AS author_id,
        u.name AS author,
        GROUP_CONCAT(DISTINCT t.name ORDER BY t.name SEPARATOR ',') AS tags,
        NULL AS duration, NULL AS duration_human,
        NULL AS content, NULL AS color,
        NULL AS is_pinned, NULL AS excerpt
        FROM photos p
        LEFT JOIN users u ON u.id = p.author_id
        LEFT JOIN taggables tg ON tg.taggable_id = p.id AND tg.taggable_type = 'photo'
        LEFT JOIN tags t ON t.id = tg.tag_id
        WHERE p.deleted_at IS NULL
        GROUP BY p.id, p.title, p.description, p.created_at,
        p.url, p.thumbnail, p.width, p.height, p.size, u.id, u.name

        UNION ALL
    
        SELECT
        v.id, v.title, v.description, v.created_at, 'video' AS type,
        v.url, v.thumbnail, NULL, NULL, v.size,
        NULL AS author_id,       -- ← добавлено
        NULL AS author_name,     -- ← добавлено
        GROUP_CONCAT(DISTINCT t.name ORDER BY t.name SEPARATOR ',') AS tags,
        v.duration, NULL AS duration_human,
        NULL AS content, NULL AS color,
        NULL AS is_pinned, NULL AS excerpt
        FROM videos v
        LEFT JOIN taggables tg ON tg.taggable_id = v.id AND tg.taggable_type = 'video'
        LEFT JOIN tags t ON t.id = tg.tag_id
        WHERE v.deleted_at IS NULL
        GROUP BY v.id, v.title, v.description, v.created_at,
                 v.url, v.thumbnail, v.size, v.duration
        
        UNION ALL
        
        SELECT
        n.id, n.title, NULL AS description, n.created_at, 'note' AS type,
        NULL AS url, NULL AS thumbnail, NULL, NULL, NULL AS size,
        NULL AS author_id,       -- ← добавлено
        NULL AS author_name,     -- ← добавлено
        GROUP_CONCAT(DISTINCT t.name ORDER BY t.name SEPARATOR ',') AS tags,
        NULL AS duration, NULL AS duration_human,
        n.content, n.color,
        n.is_pinned, NULL AS excerpt
        FROM notes n
        LEFT JOIN taggables tg ON tg.taggable_id = n.id AND tg.taggable_type = 'note'
        LEFT JOIN tags t ON t.id = tg.tag_id
        WHERE n.deleted_at IS NULL
        GROUP BY n.id, n.title, n.created_at, n.content, n.color, n.is_pinned
        
        ORDER BY created_at DESC
        LIMIT :limit OFFSET :offset  
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limit',  $limit,  \PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, \PDO::PARAM_INT);
        $stmt->execute();
        $items = array_map([$this, 'formatFeedItem'], $stmt->fetchAll(\PDO::FETCH_ASSOC));

        $counts = [
            'photos' => $this->photos->count(),
            'videos' => $this->videos->count(),
            'notes'  => $this->notes->count(),
        ];
        $total = array_sum($counts);

        $this->json([
            'data' => $items,
            'meta' => [
                'total'    => $total,
                'limit'    => $limit,
                'offset'   => $offset,
                'has_more' => ($offset + $limit) < $total,
                'counts'   => $counts,
            ],
        ]);
    }
    private function formatFeedItem(array $row): array
    {
        $row['tags'] = array_values(
            array_unique(
                array_filter(explode(',', $row['tags'] ?? ''))
            )
        );

        switch ($row['type']) {
            case 'photo':
                unset($row['duration'], $row['duration_human'],
                    $row['content'], $row['color'],
                    $row['is_pinned'], $row['excerpt']);
                break;

            case 'video':
                $seconds = (int)($row['duration'] ?? 0);
                $h = intdiv($seconds, 3600);
                $m = intdiv($seconds % 3600, 60);
                $s = $seconds % 60;
                $row['duration_human'] = $h > 0
                    ? sprintf('%d:%02d:%02d', $h, $m, $s)
                    : sprintf('%d:%02d', $m, $s);
                unset($row['width'], $row['height'],
                    $row['content'], $row['color'],
                    $row['is_pinned'], $row['excerpt']);
                break;

            case 'note':
                $row['is_pinned'] = (bool)$row['is_pinned'];
                $row['excerpt']   = mb_substr(strip_tags((string)$row['content']), 0, 160);
                unset($row['url'], $row['thumbnail'],
                    $row['width'], $row['height'],
                    $row['duration'], $row['duration_human']);
                break;
        }

        return $row;
    }

    /** GET /api/photos */
    public function photos(Request $request): void
    {
        [$limit, $offset] = $this->pagination($request);

        $this->json([
            'data' => $this->photos->findAll($limit, $offset),
            'meta' => $this->buildMeta($this->photos->count(), $limit, $offset),
        ]);
    }

    /** GET /api/videos */
    public function videos(Request $request): void
    {
        [$limit, $offset] = $this->pagination($request);

        $this->json([
            'data' => $this->videos->findAll($limit, $offset),
            'meta' => $this->buildMeta($this->videos->count(), $limit, $offset),
        ]);
    }

    /** GET /api/notes */
    public function notes(Request $request): void
    {
        [$limit, $offset] = $this->pagination($request);

        $this->json([
            'data' => $this->notes->findAll($limit, $offset),
            'meta' => $this->buildMeta($this->notes->count(), $limit, $offset),
        ]);
    }

    // ------------------------------------------------------------------ //
    //  Хелперы
    // ------------------------------------------------------------------ //

    private function pagination(Request $request): array
    {
        return [
            $this->queryInt($request, 'limit',  20, 1, 100),
            $this->queryInt($request, 'offset',  0, 0),
        ];
    }

    private function buildMeta(int $total, int $limit, int $offset): array
    {
        return [
            'total'    => $total,
            'limit'    => $limit,
            'offset'   => $offset,
            'has_more' => ($offset + $limit) < $total,
        ];
    }

    private function queryInt(
        Request $request,
        string  $key,
        int     $default,
        int     $min = 0,
        int     $max = PHP_INT_MAX
    ): int {
        $val = $request->getInt($key, $default);
        return max($min, min($max, $val));
    }

    /**
     * Отправляет JSON-ответ и завершает выполнение скрипта.
     */
    private function json(mixed $data, int $status = 200): never
    {
        http_response_code($status);
        header('Content-Type: application/json; charset=utf-8');
        $encoded = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_THROW_ON_ERROR);
        echo $encoded;
        exit;
    }
}