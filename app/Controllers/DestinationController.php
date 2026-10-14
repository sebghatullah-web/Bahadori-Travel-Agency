<?php

namespace App\Controllers;

use App\Core\BaseController;
use App\Core\Database;
use App\Core\Notifier;
use App\Core\Session;
use App\Models\Destination;
use App\Models\Tour;
use App\Models\Setting;

class DestinationController extends BaseController
{
    public function index()
    {
        $data = [
            'settings'     => Setting::allMap(),
            'destinations' => (new Destination())->all('sort_order', 'ASC'),
            'featured'     => (new Destination())->featured(6),
        ];
        return $this->view->render('frontend/destinations', $data);
    }

    public function show(string $slug)
    {
        $model = new Destination();
        $destination = $model->findBySlug($slug);
        if (!$destination) {
            throw new \App\Core\Exceptions\NotFoundException("مقصد یافت نشد: {$slug}");
        }

        $data = [
            'settings'     => Setting::allMap(),
            'destination'  => $destination,
            'tours'        => (new Tour())->byDestination((int) $destination['id']),
            'other'        => $model->featured(6),
        ];
        return $this->view->render('frontend/destination-single', $data);
    }
}
