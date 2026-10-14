<?php

namespace App\Controllers\Admin;

use App\Core\BaseController;
use App\Core\Database;
use App\Core\Notifier;
use App\Core\Session;
use App\Core\Upload;
use App\Models\Destination;
use App\Models\Tour;

class TourController extends BaseController
{
    public function index()
    {
        $this->requireAdmin();
        return $this->view->render('admin/tours/index', [
            'title' => 'تورها و پکیج‌ها',
            'tours' => (new Tour())->allWithDestination(),
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
            return $this->fail('عنوان تور الزامی است.', 422, ['title' => 'عنوان را وارد کنید.']);
        }

        $model = new Tour();
        $data = $this->collectData($model);
        $id = $model->create($data);

        Notifier::emit(['type' => 'content_update', 'entity' => 'tour', 'action' => 'create', 'id' => $id]);
        return $this->ok(['id' => $id], 'تور جدید ایجاد شد.');
    }

    public function update()
    {
        $this->requireAdmin();
        if (!$this->request->isPost() || !Session::verifyCsrf($this->request->input('_csrf'))) {
            return $this->fail('درخواست نامعتبر است.', 422);
        }

        $id = $this->request->int('id');
        $model = new Tour();
        $existing = $model->find($id);
        if (!$existing) {
            return $this->fail('تور یافت نشد.', 404);
        }

        $data = $this->collectData($model, $existing);
        $model->update($id, $data);

        Notifier::emit(['type' => 'content_update', 'entity' => 'tour', 'action' => 'update', 'id' => $id]);
        return $this->ok(['id' => $id], 'تغییرات ذخیره شد.');
    }

    public function destroy()
    {
        $this->requireAdmin();
        if (!$this->request->isPost() || !Session::verifyCsrf($this->request->input('_csrf'))) {
            return $this->fail('درخواست نامعتبر است.', 422);
        }

        $id = $this->request->int('id');
        $model = new Tour();
        $existing = $model->find($id);
        if ($existing) {
            Upload::remove($existing['image'] ?? null, $this->config);
            $model->delete($id);
        }
        return $this->ok(null, 'تور حذف شد.');
    }

    /** Single tour as JSON (used to populate the edit modal). */
    public function getOne()
    {
        $this->requireAdmin();
        $id = $this->request->int('id');
        $tour = (new Tour())->find($id);
        if (!$tour) {
            return $this->fail('تور یافت نشد.', 404);
        }
        return $this->ok($tour);
    }

    private function collectData(Tour $model, ?array $existing = null): array
    {
        $num = fn($key) => (float) preg_replace('/[^0-9.]/', '', $this->request->str($key));

        $data = [
            'title'           => $this->request->str('title'),
            'slug'            => $this->uniqueSlug($this->request->str('slug') ?: $this->request->str('title'), $existing['id'] ?? 0),
            'destination_id'  => $this->request->int('destination_id') ?: null,
            'location'        => $this->request->str('location') ?: null,
            'badge'           => $this->request->str('badge') ?: null,
            'summary'         => $this->request->str('summary') ?: null,
            'description'     => $this->request->str('description') ?: null,
            'price'           => $num('price'),
            'old_price'       => $num('old_price') ?: null,
            'rating'          => (float) ($this->request->str('rating') ?: 4.5),
            'duration_days'   => max(1, $this->request->int('duration_days', 1)),
            'duration_nights' => max(0, $this->request->int('duration_nights')),
            'is_featured'     => $this->request->bool('is_featured') ? 1 : 0,
            'sort_order'      => $this->request->int('sort_order'),
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
        $slug = slugify($text, 'tour');
        $base = $slug;
        $i = 1;
        while (Database::scalar('SELECT COUNT(*) FROM tours WHERE slug = ? AND id <> ?', [$slug, $exceptId])) {
            $slug = $base . '-' . (++$i);
        }
        return $slug;
    }
}
