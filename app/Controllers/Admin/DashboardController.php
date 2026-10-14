<?php

namespace App\Controllers\Admin;

use App\Core\BaseController;
use App\Models\Booking;
use App\Models\Message;
use App\Models\Newsletter;
use App\Models\Tour;
use App\Models\Destination;

/**
 * Admin dashboard overview: counters + recent activity + chart data.
 */
class DashboardController extends BaseController
{
    public function index()
    {
        $this->requireAdmin();

        $bookingModel  = new Booking();
        $messageModel  = new Message();

        $data = [
            'title'         => 'نمای کلی',
            'stats'         => [
                'messages'    => $messageModel->unreadCount(),
                'confirmed'   => $bookingModel->countByStatus('confirmed'),
                'pending'     => $bookingModel->countByStatus('pending'),
                'subscribers' => (new Newsletter())->countAll(),
                'tours'       => (new Tour())->count(),
                'destinations'=> (new Destination())->count(),
            ],
            'recentBookings' => array_slice($bookingModel->allWithTour(), 0, 6),
            'chart'          => $bookingModel->recentDaily(14),
        ];

        return $this->view->render('admin/dashboard', $data);
    }
}
