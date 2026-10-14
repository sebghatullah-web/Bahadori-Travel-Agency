<?php

namespace App\Controllers\Admin;

use App\Core\BaseController;
use App\Core\Notifier;
use App\Core\Session;
use App\Models\Service;

class ServiceController extends BaseController
{
    public function index()
    {
        $this->requireAdmin();
        return $this->view->render('admin/services/index', [
            'title'    => 'خدمات و مزیت‌ها',
            'services' => (new Service())->all('sort_order', 'ASC'),
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
            return $this->fail('عنوان خدمت الزامی است.', 422, ['title' => 'عنوان را وارد کنید.']);
        }

        $model = new Service();
        $id = $model->create($this->collectData());

        Notifier::emit(['type' => 'content_update', 'entity' => 'service', 'action' => 'create', 'id' => $id]);
        return $this->ok(['id' => $id], 'خدمت جدید ایجاد شد.');
    }

    public function update()
    {
        $this->requireAdmin();
        if (!$this->request->isPost() || !Session::verifyCsrf($this->request->input('_csrf'))) {
            return $this->fail('درخواست نامعتبر است.', 422);
        }

        $id = $this->request->int('id');
        $model = new Service();
        if (!$model->find($id)) {
            return $this->fail('خدمت یافت نشد.', 404);
        }

        $model->update($id, $this->collectData());
        return $this->ok(['id' => $id], 'تغییرات ذخیره شد.');
    }

    public function destroy()
    {
        $this->requireAdmin();
        if (!$this->request->isPost() || !Session::verifyCsrf($this->request->input('_csrf'))) {
            return $this->fail('درخواست نامعتبر است.', 422);
        }
        (new Service())->delete($this->request->int('id'));
        return $this->ok(null, 'خدمت حذف شد.');
    }

    private function collectData(): array
    {
        return [
            'icon'        => $this->request->str('icon') ?: 'star',
            'title'       => $this->request->str('title'),
            'description' => $this->request->str('description') ?: null,
            'sort_order'  => $this->request->int('sort_order'),
            'is_active'   => $this->request->bool('is_active') ? 1 : 0,
        ];
    }
}
