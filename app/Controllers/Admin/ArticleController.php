<?php

namespace App\Controllers\Admin;

use App\Core\BaseController;
use App\Core\Database;
use App\Core\Notifier;
use App\Core\Session;
use App\Core\Upload;
use App\Models\Article;

class ArticleController extends BaseController
{
    public function index()
    {
        $this->requireAdmin();
        return $this->view->render('admin/articles/index', [
            'title'    => 'مقالات',
            'articles' => (new Article())->all('created_at', 'DESC'),
        ]);
    }

    public function store()
    {
        $this->requireAdmin();
        if (!$this->request->isPost() || !Session::verifyCsrf($this->request->input('_csrf'))) {
            return $this->fail('درخواست نامعتبر است.', 422);
        }

        $title = $this->request->str('title');
        if ($title === '') {
            return $this->fail('عنوان مقاله الزامی است.', 422, ['title' => 'عنوان را وارد کنید.']);
        }

        $model = new Article();
        $id = $model->create($this->collectData($model));

        Notifier::emit(['type' => 'content_update', 'entity' => 'article', 'action' => 'create', 'id' => $id]);
        return $this->ok(['id' => $id], 'مقاله جدید ایجاد شد.');
    }

    public function update()
    {
        $this->requireAdmin();
        if (!$this->request->isPost() || !Session::verifyCsrf($this->request->input('_csrf'))) {
            return $this->fail('درخواست نامعتبر است.', 422);
        }

        $id = $this->request->int('id');
        $model = new Article();
        $existing = $model->find($id);
        if (!$existing) {
            return $this->fail('مقاله یافت نشد.', 404);
        }

        $model->update($id, $this->collectData($model, $existing));

        Notifier::emit(['type' => 'content_update', 'entity' => 'article', 'action' => 'update', 'id' => $id]);
        return $this->ok(['id' => $id], 'تغییرات ذخیره شد.');
    }

    public function destroy()
    {
        $this->requireAdmin();
        if (!$this->request->isPost() || !Session::verifyCsrf($this->request->input('_csrf'))) {
            return $this->fail('درخواست نامعتبر است.', 422);
        }

        $id = $this->request->int('id');
        $model = new Article();
        $existing = $model->find($id);
        if ($existing) {
            Upload::remove($existing['image'] ?? null, $this->config);
            $model->delete($id);
        }
        return $this->ok(null, 'مقاله حذف شد.');
    }

    private function collectData(Article $model, ?array $existing = null): array
    {
        $data = [
            'title'        => $this->request->str('title'),
            'slug'         => $this->uniqueSlug($this->request->str('slug') ?: $this->request->str('title'), $existing['id'] ?? 0),
            'category'     => $this->request->str('category') ?: null,
            'excerpt'      => $this->request->str('excerpt') ?: null,
            'body'         => $this->request->str('body') ?: null,
            'author'       => $this->request->str('author') ?: ($this->auth['name'] ?? 'مدیر'),
            'is_published' => $this->request->bool('is_published') ? 1 : 0,
        ];

        if (!empty($_FILES['image']) && ($_FILES['image']['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_NO_FILE) {
            $data['image'] = Upload::image($_FILES['image'], $this->config);
            if ($existing && !empty($existing['image'])) {
                Upload::remove($existing['image'], $this->config);
            }
        } elseif ($existing) {
            $data['image'] = $existing['image'];
        }

        return $data;
    }

    private function uniqueSlug(string $text, int $exceptId): string
    {
        $slug = slugify($text, 'article');
        $base = $slug;
        $i = 1;
        while (Database::scalar('SELECT COUNT(*) FROM articles WHERE slug = ? AND id <> ?', [$slug, $exceptId])) {
            $slug = $base . '-' . (++$i);
        }
        return $slug;
    }
}
