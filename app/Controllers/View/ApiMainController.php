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
    public function feed(Request $request): void
    {
        [$limit, $offset] = $this->pagination($request);

        $photos = $this->photos->findAll($limit, $offset);
        $videos = $this->videos->findAll($limit, $offset);
        $notes  = $this->notes->findAll($limit, $offset);

        // Объединяем и сортируем по дате
        $items = array_merge($photos, $videos, $notes);
        usort($items, fn($a, $b) => strtotime($b['created_at']) <=> strtotime($a['created_at']));
        $items = array_slice($items, $offset, $limit);

        $this->json([
            'data' => array_values(array_slice($items, 0, $limit)),
            'meta' => [
                'total' => $this->photos->count() + $this->videos->count() + $this->notes->count(),
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
            'total'       => $total,
            'limit'       => $limit,
            'offset'      => $offset,
            'has_more'    => ($offset + $limit) < $total,
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

    private function json(mixed $data, int $status = 200): void
    {
        http_response_code($status);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        exit;
    }
}
