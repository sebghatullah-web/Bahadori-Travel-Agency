<?php

namespace App\Controllers\Admin;

use App\Core\BaseController;
use App\Core\Session;
use App\Models\Admin;

/**
 * Admin authentication: login form, credential check, logout.
 */
class AuthController extends BaseController
{
    public function showLogin()
    {
        // Already logged in → go straight to dashboard.
        if (Session::check()) {
            $this->redirect($this->baseUrl('admin'));
        }

        return $this->view->render('admin/auth/login', [
            'settings' => [],
            'title'    => 'ورود به پنل مدیریت',
        ]);
    }

    public function login()
    {
        if (!$this->request->isPost() || !Session::verifyCsrf($this->request->input('_csrf'))) {
            Session::flash('error', 'نشست منقضی شده، دوباره تلاش کنید.');
            $this->redirect($this->baseUrl('admin/login'));
        }

        $email    = $this->request->str('email');
        $password = (string) $this->request->input('password');

        $user = (new Admin())->findByEmail($email);

        if (!$user || !password_verify($password, $user['password'])) {
            Session::flash('error', 'ایمیل یا گذرواژه نادرست است.');
            Session::set('_old', ['email' => $email]);
            $this->redirect($this->baseUrl('admin/login'));
        }

        Session::login($user);
        Session::flash('success', 'خوش آمدید، ' . $user['name']);
        $this->redirect($this->baseUrl('admin'));
    }

    public function logout()
    {
        Session::logout();
        Session::flash('success', 'با موفقیت خارج شدید.');
        $this->redirect($this->baseUrl('admin/login'));
    }
}
