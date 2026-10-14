<?php

namespace App\Models;

use App\Core\Database;
use App\Core\Model;

class Message extends Model
{
    protected string $table = 'messages';

    public function unreadCount(): int
    {
        return (int) Database::scalar('SELECT COUNT(*) FROM messages WHERE is_read = 0');
    }

    public function allNewest(): array
    {
        return Database::all('SELECT * FROM messages ORDER BY created_at DESC, id DESC');
    }
}
