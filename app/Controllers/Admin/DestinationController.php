<?php

namespace App\Controllers\Admin;

use App\Core\BaseController;
use App\Core\Notifier;
use App\Core\Session;
use App\Core\Upload;
use App\Models\Destination;

class DestinationController extends BaseController
{
    public function index()
    {
        $this->requireAdmin();
        return $this->view->render('admin/destinations/index', [
            'title'        => 'مقاصد',
            'destinations' => (new Destination())->all('sort_order', 'ASC'),
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
            return $this->fail('عنوان مقصد الزامی است.', 422, ['title' => 'عنوان را وارد کنید.']);
        }

        $model = new Destination();
        $data = $this->collectData($model);
        $id = $model->create($data);

        Notifier::emit(['type' => 'content_update', 'entity' => 'destination', 'action' => 'create', 'id' => $id]);
        return $this->ok(['id' => $id], 'مقصد جدید ایجاد شد.');
    }

    public function update()
    {
        $this->requireAdmin();
        if (!$this->request->isPost() || !Session::verifyCsrf($this->request->input('_csrf'))) {
            return $this->fail('درخواست نامعتبر است.', 422);
        }

        $id = $this->request->int('id');
        $model = new Destination();
        $existing = $model->find($id);
        if (!$existing) {
            return $this->fail('مقصد یافت نشد.', 404);
        }

        $data = $this->collectData($model, $existing);
        $model->update($id, $data);

        Notifier::emit(['type' => 'content_update', 'entity' => 'destination', 'action' => 'update', 'id' => $id]);
        return $this->ok(['id' => $id], 'تغییرات ذخیره شد.');
    }

    public function destroy()
    {
        $this->requireAdmin();
        if (!$this->request->isPost() || !Session::verifyCsrf($this->request->input('_csrf'))) {
            return $this->fail('درخواست نامعتبر است.', 422);
        }

        $id = $this->request->int('id');
        $model = new Destination();
        $existing = $model->find($id);
        if ($existing) {
            Upload::remove($existing['image'] ?? null, $this->config);
            $model->delete($id);
        }
        return $this->ok(null, 'مقصد حذف شد.');
    }

    /** Build the DB row from POST + optional uploaded image. */
    private function collectData(Destination $model, ?array $existing = null): array
    {
        $data = [
            'title'       => $this->request->str('title'),
            'slug'        => $this->makeSlug($this->request->str('slug') ?: $this->request->str('title'), $model, $existing['id'] ?? 0),
            'country'     => $this->request->str('country') ?: null,
            'description' => $this->request->str('description') ?: null,
            'price_from'  => (float) preg_replace('/[^0-9.]/', '', $this->request->str('price_from')),
            'rating'      => (float) ($this->request->str('rating') ?: 4.5),
            'is_featured' => $this->request->bool('is_featured') ? 1 : 0,
            'sort_order'  => $this->request->int('sort_order'),
        ];

        // Handle image upload (keeps the old file when none is uploaded).
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

    private function makeSlug(string $text, Destination $model, int $exceptId = 0): string
    {
        $slug = $this->slugify($text);
        $base = $slug;
        $i = 1;
        while ($this->slugExists($model, $slug, $exceptId)) {
            $slug = $base . '-' . (++$i);
        }
        return $slug;
    }

    private function slugExists(Destination $model, string $slug, int $exceptId): bool
    {
        return (bool) \App\Core\Database::scalar(
            'SELECT COUNT(*) FROM destinations WHERE slug = ? AND id <> ?',
            [$slug, $exceptId]
        );
    }

    /** Simple ASCII slug; keeps existing latin text, transliterates nothing. */
    private function slugify(string $text): string
    {
        $text = trim($text);
        // If the title is non-latin (Persian), fall back to a random slug.
        if (!preg_match('/[A-Za-z]/', $text)) {
            return 'dest-' . bin2hex(random_bytes(4));
        }
        $text = strtolower($text);
        $text = preg_replace('/[^a-z0-9]+/', '-', $text);
        return trim($text, '-') ?: ('dest-' . bin2hex(random_bytes(4)));
    }
}
