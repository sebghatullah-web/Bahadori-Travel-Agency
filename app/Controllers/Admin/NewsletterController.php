<?php

namespace App\Controllers\Admin;

use App\Core\BaseController;
use App\Core\Session;
use App\Models\Newsletter;

class NewsletterController extends BaseController
{
    public function index()
    {
        $this->requireAdmin();
        return $this->view->render('admin/newsletter/index', [
            'title'  => 'خبرنامه',
            'emails' => (new Newsletter())->allNewest(),
        ]);
    }

    public function destroy()
    {
        $this->requireAdmin();
        if (!$this->request->isPost() || !Session::verifyCsrf($this->request->input('_csrf'))) {
            return $this->fail('درخواست نامعتبر است.', 422);
        }
        (new Newsletter())->delete($this->request->int('id'));
        return $this->ok(null, 'عضو حذف شد.');
    }
}
