<?php

namespace App\Controllers;

use App\Core\BaseController;
use App\Models\Destination;
use App\Models\Tour;
use App\Models\Service;
use App\Models\Article;
use App\Models\Setting;

class HomeController extends BaseController
{
    public function index()
    {
        $settings = Setting::allMap();

        $data = [
            'settings'     => $settings,
            'destinations' => (new Destination())->featured(4),
            'tours'        => (new Tour())->featured(3),
            'services'     => (new Service())->active(),
            'articles'     => (new Article())->published(3),
        ];

        return $this->view->render('frontend/home', $data);
    }
}
