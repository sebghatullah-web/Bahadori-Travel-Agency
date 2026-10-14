<?php

namespace App\Models;

use App\Core\Database;
use App\Core\Model;

class Newsletter extends Model
{
    protected string $table = 'newsletter';

    public function allNewest(): array
    {
        return Database::all('SELECT * FROM newsletter ORDER BY created_at DESC, id DESC');
    }

    public function countAll(): int
    {
        return (int) Database::scalar('SELECT COUNT(*) FROM newsletter');
    }

    /** Returns true when the email already exists. */
    public function exists(string $email): bool
    {
        return (bool) Database::scalar(
            'SELECT COUNT(*) FROM newsletter WHERE email = ?',
            [$email]
        );
    }
}
