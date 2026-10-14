<?php

namespace App\Models;

use App\Core\Database;
use App\Core\Model;

class Booking extends Model
{
    protected string $table = 'bookings';

    /** All bookings with tour title, newest first (admin list). */
    public function allWithTour(): array
    {
        return Database::all(
            'SELECT b.*, t.title AS tour_title
             FROM bookings b
             LEFT JOIN tours t ON t.id = b.tour_id
             ORDER BY b.created_at DESC, b.id DESC'
        );
    }

    public function countByStatus(string $status): int
    {
        return (int) Database::scalar(
            'SELECT COUNT(*) FROM bookings WHERE status = ?',
            [$status]
        );
    }

    /** Bookings per day for the last N days (admin dashboard chart). */
    public function recentDaily(int $days = 14): array
    {
        return Database::all(
            'SELECT DATE(created_at) AS day, COUNT(*) AS total
             FROM bookings
             WHERE created_at >= (NOW() - INTERVAL ? DAY)
             GROUP BY DATE(created_at)
             ORDER BY day ASC',
            [$days]
        );
    }
}
