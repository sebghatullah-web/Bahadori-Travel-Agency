<?php

namespace App\Models;

use App\Core\Database;
use App\Core\Model;

class Tour extends Model
{
    protected string $table = 'tours';

    /** Featured tours joined with destination name. */
    public function featured(int $limit = 6): array
    {
        return Database::all(
            'SELECT t.*, d.title AS destination_name, d.slug AS destination_slug
             FROM tours t
             LEFT JOIN destinations d ON d.id = t.destination_id
             WHERE t.is_featured = 1
             ORDER BY t.sort_order ASC, t.id DESC
             LIMIT ' . (int) $limit
        );
    }

    public function findBySlug(string $slug): ?array
    {
        return Database::one(
            'SELECT t.*, d.title AS destination_name, d.slug AS destination_slug
             FROM tours t
             LEFT JOIN destinations d ON d.id = t.destination_id
             WHERE t.slug = ? LIMIT 1',
            [$slug]
        );
    }

    /** All tours (admin list) with destination name. */
    public function allWithDestination(): array
    {
        return Database::all(
            'SELECT t.*, d.title AS destination_name
             FROM tours t
             LEFT JOIN destinations d ON d.id = t.destination_id
             ORDER BY t.sort_order ASC, t.id DESC'
        );
    }

    /** Tours filtered by destination (for destination detail page). */
    public function byDestination(int $destinationId): array
    {
        return Database::all(
            'SELECT * FROM tours WHERE destination_id = ? ORDER BY sort_order ASC',
            [$destinationId]
        );
    }

    /** Basic keyword search across tours (used by /search). */
    public function search(string $term, int $limit = 20): array
    {
        $like = '%' . $term . '%';
        return Database::all(
            'SELECT id, title, slug, location, image, price, rating, duration_days
             FROM tours
             WHERE title LIKE ? OR location LIKE ? OR summary LIKE ?
             ORDER BY rating DESC LIMIT ' . (int) $limit,
            [$like, $like, $like]
        );
    }
}
