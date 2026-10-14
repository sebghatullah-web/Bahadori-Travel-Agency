<?php

namespace App\Models;

use App\Core\Database;
use App\Core\Model;

class Service extends Model
{
    protected string $table = 'services';

    /** Active benefit cards in display order. */
    public function active(): array
    {
        return Database::all(
            'SELECT * FROM services WHERE is_active = 1 ORDER BY sort_order ASC, id ASC'
        );
    }
}
