<?php

namespace App\Models;

use App\Core\Model;

class Destination extends Model
{
    protected string $table = 'destinations';

    /** Featured destinations, newest / ordered. */
    public function featured(int $limit = 8): array
    {
        return \App\Core\Database::all(
            'SELECT * FROM destinations WHERE is_featured = 1
             ORDER BY sort_order ASC, id DESC LIMIT ' . (int) $limit
        );
    }

    public function findBySlug(string $slug): ?array
    {
        return $this->findWhere('slug', $slug);
    }

    /** Count how many tours reference this destination. */
    public function tourCount(int $destinationId): int
    {
        return (int) \App\Core\Database::scalar(
            'SELECT COUNT(*) FROM tours WHERE destination_id = ?',
            [$destinationId]
        );
    }
}
