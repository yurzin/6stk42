<?php

declare(strict_types=1);

namespace App\Controllers\View;

use App\Models\Photo;
use App\Models\Video;
use App\Models\Note;

/**
 * MainController — главный контроллер API.
 *
 * Маршруты:
 *   GET /api/feed          — объединённая лента (photos + videos + notes)
 *   GET /api/photos        — только фото
 *   GET /api/videos        — только видео
 *   GET /api/notes         — только заметки
 */
class ApiMainController
{
    private Photo $photos;
    private Video $videos;
    private Note $notes;

    public function __construct()
    {
        $this->photos = new Photo();
        $this->videos = new Video();
        $this->notes  = new Note();
    }

    // ------------------------------------------------------------------ //
    //  Публичные экшены
    // ------------------------------------------------------------------ //

    /** GET /api/feed */
    public function feed(): void
    {
        $limit  = $this->queryInt('limit', 20, 1, 100);
        $offset = $this->queryInt('offset', 0, 0);

        $photos = $this->photos->findAll($limit, $offset);
        $videos = $this->videos->findAll($limit, $offset);
        $notes  = $this->notes->findAll($limit, $offset);

        // Объединяем и сортируем по дате
        $items = array_merge($photos, $videos, $notes);
        usort($items, static fn($a, $b) => strcmp($b['created_at'], $a['created_at']));

        $this->json([
            'data' => array_values(array_slice($items, 0, $limit)),
            'meta' => [
                'total'  => count($items),
                'limit'  => $limit,
                'offset' => $offset,
                'counts' => [
                    'photos' => $this->photos->count(),
                    'videos' => $this->videos->count(),
                    'notes'  => $this->notes->count(),
                ],
            ],
        ]);
    }

    /** GET /api/photos */
    public function photos(): void
    {
        [$limit, $offset] = $this->pagination();

        $this->json([
            'data' => $this->photos->findAll($limit, $offset),
            'meta' => $this->buildMeta($this->photos->count(), $limit, $offset),
        ]);
    }

    /** GET /api/videos */
    public function videos(): void
    {
        [$limit, $offset] = $this->pagination();

        $this->json([
            'data' => $this->videos->findAll($limit, $offset),
            'meta' => $this->buildMeta($this->videos->count(), $limit, $offset),
        ]);
    }

    /** GET /api/notes */
    public function notes(): void
    {
        [$limit, $offset] = $this->pagination();

        $this->json([
            'data' => $this->notes->findAll($limit, $offset),
            'meta' => $this->buildMeta($this->notes->count(), $limit, $offset),
        ]);
    }

    // ------------------------------------------------------------------ //
    //  Хелперы
    // ------------------------------------------------------------------ //

    private function pagination(): array
    {
        return [
            $this->queryInt('limit', 20, 1, 100),
            $this->queryInt('offset', 0, 0),
        ];
    }

    private function buildMeta(int $total, int $limit, int $offset): array
    {
        return [
            'total'       => $total,
            'limit'       => $limit,
            'offset'      => $offset,
            'has_more'    => ($offset + $limit) < $total,
        ];
    }

    private function queryInt(string $key, int $default, int $min = 0, int $max = PHP_INT_MAX): int
    {
        $val = isset($_GET[$key]) ? (int) $_GET[$key] : $default;
        return max($min, min($max, $val));
    }

    private function json(mixed $data, int $status = 200): void
    {
        http_response_code($status);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        exit;
    }
}
