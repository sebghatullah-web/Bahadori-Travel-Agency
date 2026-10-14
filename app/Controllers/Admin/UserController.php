<?php

namespace App\Controllers\Admin;

use App\Core\BaseController;
use App\Core\Session;
use App\Models\Admin;

class UserController extends BaseController
{
    public function index()
    {
        $this->requireAdmin();
        return $this->view->render('admin/users/index', [
            'title' => 'مدیران',
            'users' => (new Admin())->all('created_at', 'DESC'),
        ]);
    }

    public function store()
    {
        $this->requireAdmin();
        if (!$this->request->isPost() || !Session::verifyCsrf($this->request->input('_csrf'))) {
            return $this->fail('درخواست نامعتبر است.', 422);
        }

        $name  = $this->request->str('name');
        $email = $this->request->str('email');
        $pass  = (string) $this->request->input('password');

        $errors = [];
        if ($name === '') $errors['name'] = 'نام را وارد کنید.';
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors['email'] = 'ایمیل معتبر نیست.';
        if (strlen($pass) < 6) $errors['password'] = 'گذرواژه حداقل ۶ کاراکتر باشد.';

        $model = new Admin();
        if (!$errors && $model->emailTaken($email)) {
            $errors['email'] = 'این ایمیل قبلاً ثبت شده است.';
        }
        if ($errors) {
            return $this->fail('لطفاً خطاها را برطرف کنید.', 422, $errors);
        }

        $id = $model->create([
            'name'     => $name,
            'email'    => $email,
            'password' => password_hash($pass, PASSWORD_BCRYPT),
            'role'     => $this->request->str('role') === 'editor' ? 'editor' : 'admin',
        ]);

        return $this->ok(['id' => $id], 'مدیر جدید ایجاد شد.');
    }

    public function update()
    {
        $this->requireAdmin();
        if (!$this->request->isPost() || !Session::verifyCsrf($this->request->input('_csrf'))) {
            return $this->fail('درخواست نامعتبر است.', 422);
        }

        $id = $this->request->int('id');
        $model = new Admin();
        $existing = $model->find($id);
        if (!$existing) {
            return $this->fail('کاربر یافت نشد.', 404);
        }

        $name  = $this->request->str('name');
        $email = $this->request->str('email');
        $pass  = (string) $this->request->input('password');

        $errors = [];
        if ($name === '') $errors['name'] = 'نام را وارد کنید.';
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors['email'] = 'ایمیل معتبر نیست.';
        if ($model->emailTaken($email, $id)) $errors['email'] = 'این ایمیل قبلاً ثبت شده است.';
        if ($pass !== '' && strlen($pass) < 6) $errors['password'] = 'گذرواژه حداقل ۶ کاراکتر باشد.';
        if ($errors) {
            return $this->fail('لطفاً خطاها را برطرف کنید.', 422, $errors);
        }

        $data = [
            'name'  => $name,
            'email' => $email,
            'role'  => $this->request->str('role') === 'editor' ? 'editor' : 'admin',
        ];
        if ($pass !== '') {
            $data['password'] = password_hash($pass, PASSWORD_BCRYPT);
        }
        $model->update($id, $data);

        return $this->ok(['id' => $id], 'تغییرات ذخیره شد.');
    }

    public function destroy()
    {
        $this->requireAdmin();
        if (!$this->request->isPost() || !Session::verifyCsrf($this->request->input('_csrf'))) {
            return $this->fail('درخواست نامعتبر است.', 422);
        }

        $id = $this->request->int('id');
        // Prevent deleting yourself.
        if ($this->auth && (int) $this->auth['id'] === $id) {
            return $this->fail('نمی‌توانید حساب خودتان را حذف کنید.', 422);
        }
        // Prevent deleting the last admin.
        if ((new Admin())->count() <= 1) {
            return $this->fail('باید حداقل یک مدیر در سیستم باقی بماند.', 422);
        }

        (new Admin())->delete($id);
        return $this->ok(null, 'مدیر حذف شد.');
    }
}
