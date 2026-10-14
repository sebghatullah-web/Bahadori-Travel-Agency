<?php

namespace App\Controllers\Admin;

use App\Core\BaseController;
use App\Core\Session;
use App\Models\Message;

class MessageController extends BaseController
{
    public function index()
    {
        $this->requireAdmin();
        return $this->view->render('admin/messages/index', [
            'title'    => 'پیام‌ها',
            'messages' => (new Message())->allNewest(),
        ]);
    }

    public function show(string $id)
    {
        $this->requireAdmin();
        $model = new Message();
        $message = $model->find((int) $id);
        if (!$message) {
            return $this->fail('پیام یافت نشد.', 404);
        }
        // Marking as read on open.
        if (!$message['is_read']) {
            $model->update((int) $id, ['is_read' => 1]);
        }
        return $this->view->render('admin/messages/show', [
            'title'   => 'جزئیات پیام',
            'message' => $message,
        ]);
    }

    public function toggleRead()
    {
        $this->requireAdmin();
        if (!$this->request->isPost() || !Session::verifyCsrf($this->request->input('_csrf'))) {
            return $this->fail('درخواست نامعتبر است.', 422);
        }
        $id = $this->request->int('id');
        $model = new Message();
        $message = $model->find($id);
        if (!$message) {
            return $this->fail('پیام یافت نشد.', 404);
        }
        $model->update($id, ['is_read' => $message['is_read'] ? 0 : 1]);
        return $this->ok(['is_read' => $message['is_read'] ? 0 : 1]);
    }

    public function destroy()
    {
        $this->requireAdmin();
        if (!$this->request->isPost() || !Session::verifyCsrf($this->request->input('_csrf'))) {
            return $this->fail('درخواست نامعتبر است.', 422);
        }
        (new Message())->delete($this->request->int('id'));
        return $this->ok(null, 'پیام حذف شد.');
    }
}
