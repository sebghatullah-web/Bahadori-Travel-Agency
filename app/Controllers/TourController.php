<?php

namespace App\Controllers;

use App\Core\BaseController;
use App\Core\Exceptions\NotFoundException;
use App\Models\Destination;
use App\Models\Tour;
use App\Models\Setting;

class TourController extends BaseController
{
    public function index()
    {
        $model = new Tour();
        $data = [
            'settings'  => Setting::allMap(),
            'tours'     => $model->allWithDestination(),
            'featured'  => $model->featured(6),
        ];
        return $this->view->render('frontend/tours', $data);
    }

    public function show(string $slug)
    {
        $model = new Tour();
        $tour = $model->findBySlug($slug);
        if (!$tour) {
            throw new NotFoundException("تور یافت نشد: {$slug}");
        }

        $data = [
            'settings' => Setting::allMap(),
            'tour'     => $tour,
            'related'  => $model->featured(3),
        ];
        return $this->view->render('frontend/tour-single', $data);
    }
}
