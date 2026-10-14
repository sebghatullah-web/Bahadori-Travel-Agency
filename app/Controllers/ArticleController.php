<?php

namespace App\Controllers;

use App\Core\BaseController;
use App\Core\Exceptions\NotFoundException;
use App\Models\Article;
use App\Models\Setting;

class ArticleController extends BaseController
{
    public function index()
    {
        $model = new Article();
        $data = [
            'settings' => Setting::allMap(),
            'articles' => $model->published(),
            'featured' => $model->published(4),
        ];
        return $this->view->render('frontend/articles', $data);
    }

    public function show(string $slug)
    {
        $model = new Article();
        $article = $model->findBySlug($slug);
        if (!$article || !$article['is_published']) {
            throw new NotFoundException("مقاله یافت نشد: {$slug}");
        }

        $model->addView((int) $article['id']);

        $data = [
            'settings' => Setting::allMap(),
            'article'  => $article,
            'related'  => $model->published(3),
        ];
        return $this->view->render('frontend/article-single', $data);
    }
}
