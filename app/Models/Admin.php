<?php

namespace App\Models;

use App\Core\Database;
use App\Core\Model;

class Admin extends Model
{
    protected string $table = 'admins';

    public function findByEmail(string $email): ?array
    {
        return $this->findWhere('email', $email);
    }

    public function emailTaken(string $email, int $exceptId = 0): bool
    {
        return (bool) Database::scalar(
            'SELECT COUNT(*) FROM admins WHERE email = ? AND id <> ?',
            [$email, $exceptId]
        );
    }
}
