<?php

namespace App\Models;

use App\Core\Database;
use App\Core\Model;

class Article extends Model
{
    protected string $table = 'articles';

    public function published(int $limit = 0): array
    {
        $sql = 'SELECT * FROM articles WHERE is_published = 1 ORDER BY created_at DESC';
        if ($limit > 0) {
            $sql .= ' LIMIT ' . (int) $limit;
        }
        return Database::all($sql);
    }

    public function findBySlug(string $slug): ?array
    {
        return $this->findWhere('slug', $slug);
    }

    /** Increment view counter. */
    public function addView(int $id): void
    {
        Database::execute('UPDATE articles SET views = views + 1 WHERE id = ?', [$id]);
    }
}
