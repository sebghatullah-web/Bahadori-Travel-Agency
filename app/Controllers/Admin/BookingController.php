<?php

namespace App\Controllers\Admin;

use App\Core\BaseController;
use App\Core\Notifier;
use App\Core\Session;
use App\Models\Booking;

class BookingController extends BaseController
{
    public function index()
    {
        $this->requireAdmin();
        return $this->view->render('admin/bookings/index', [
            'title'    => 'درخواست‌های رزرو',
            'bookings' => (new Booking())->allWithTour(),
        ]);
    }

    public function updateStatus()
    {
        $this->requireAdmin();
        if (!$this->request->isPost() || !Session::verifyCsrf($this->request->input('_csrf'))) {
            return $this->fail('درخواست نامعتبر است.', 422);
        }

        $id = $this->request->int('id');
        $status = $this->request->str('status');
        if (!in_array($status, ['pending', 'confirmed', 'cancelled'], true)) {
            return $this->fail('وضعیت نامعتبر است.', 422);
        }

        (new Booking())->update($id, ['status' => $status]);
        Notifier::emit(['type' => 'booking_status', 'id' => $id, 'status' => $status]);
        return $this->ok(null, 'وضعیت درخواست به‌روزرسانی شد.');
    }

    public function destroy()
    {
        $this->requireAdmin();
        if (!$this->request->isPost() || !Session::verifyCsrf($this->request->input('_csrf'))) {
            return $this->fail('درخواست نامعتبر است.', 422);
        }
        (new Booking())->delete($this->request->int('id'));
        return $this->ok(null, 'درخواست حذف شد.');
    }
}
