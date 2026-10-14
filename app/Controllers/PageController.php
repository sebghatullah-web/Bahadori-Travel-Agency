<?php

namespace App\Controllers;

use App\Core\BaseController;
use App\Core\Database;
use App\Core\Notifier;
use App\Core\Session;
use App\Models\Message;
use App\Models\Newsletter;
use App\Models\Setting;

/**
 * Handles the static-ish pages (about, contact) plus the AJAX endpoints for
 * the contact form and the newsletter subscription box.
 */
class PageController extends BaseController
{
    public function about()
    {
        return $this->view->render('frontend/about', [
            'settings' => Setting::allMap(),
        ]);
    }

    public function contact()
    {
        return $this->view->render('frontend/contact', [
            'settings' => Setting::allMap(),
        ]);
    }

    /** Contact form submission (works for both AJAX and normal POST). */
    public function submitContact()
    {
        if (!$this->request->isPost() || !Session::verifyCsrf($this->request->input('_csrf'))) {
            return $this->reject('درخواست نامعتبر است.');
        }

        $name    = $this->request->str('name');
        $email   = $this->request->str('email');
        $phone   = $this->request->str('phone');
        $subject = $this->request->str('subject');
        $message = $this->request->str('message');

        $errors = [];
        if ($name === '')    $errors['name'] = 'نام را وارد کنید.';
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors['email'] = 'ایمیل معتبر نیست.';
        if ($message === '') $errors['message'] = 'متن پیام را وارد کنید.';

        if ($errors) {
            return $this->reject('لطفاً خطاهای فرم را برطرف کنید.', $errors);
        }

        $id = (new Message())->create([
            'name'    => $name,
            'email'   => $email,
            'phone'   => $phone,
            'subject' => $subject ?: 'بدون موضوع',
            'message' => $message,
        ]);

        // Push a real-time notification to the admin dashboard (Node server).
        Notifier::emit([
            'type'    => 'new_message',
            'id'      => $id,
            'name'    => $name,
            'subject' => $subject ?: 'بدون موضوع',
        ]);

        if ($this->request->wantsJson()) {
            return $this->ok(null, 'پیام شما با موفقیت ارسال شد. به‌زودی با شما تماس می‌گیریم.');
        }

        Session::flash('success', 'پیام شما با موفقیت ارسال شد.');
        $this->redirect($this->baseUrl('contact'));
    }

    /** Newsletter subscription (AJAX from footer / CTA). */
    public function subscribe()
    {
        if (!$this->request->isPost() || !Session::verifyCsrf($this->request->input('_csrf'))) {
            return $this->fail('درخواست نامعتبر است.', 422);
        }

        $email = $this->request->str('email');
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return $this->fail('ایمیل معتبر نیست.', 422);
        }

        $model = new Newsletter();
        if ($model->exists($email)) {
            return $this->ok(null, 'این ایمیل قبلاً در خبرنامه ثبت شده است.');
        }

        $model->create(['email' => $email]);

        Notifier::emit(['type' => 'new_subscriber', 'email' => $email]);

        return $this->ok(null, 'با موفقیت در خبرنامه عضو شدید!');
    }

    /** Shared JSON error responder for form endpoints. */
    private function reject(string $message, array $errors = null)
    {
        if ($this->request->wantsJson()) {
            return $this->fail($message, 422, $errors);
        }
        Session::flash('error', $message);
        $this->redirect($this->baseUrl('contact'));
    }
}
