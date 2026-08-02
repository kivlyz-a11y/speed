<?php

namespace App\Controllers\Front;

use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Models\RoleModel;

class AuthController extends BaseController
{
    protected $userModel;
    protected $roleModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->roleModel = new RoleModel();
    }

    public function loginView()
    {
        if (session()->get('is_logged_in')) {
            return redirect()->to(session()->get('role_slug') === 'member' ? '/' : 'admin/dashboard');
        }
        return view('front/login', ['title' => 'Login - SpeedExpress Cititrans Style']);
    }

    public function loginProcess()
    {
        $email    = trim($this->request->getPost('email'));
        $password = trim($this->request->getPost('password'));

        $user = $this->userModel->where('email', $email)->first();
        if (!$user || !password_verify($password, $user['password_hash'])) {
            return redirect()->back()->with('error', 'Email atau password tidak sesuai.');
        }

        if (!$user['is_active']) {
            return redirect()->back()->with('error', 'Akun Anda dinonaktifkan. Silakan hubungi admin.');
        }

        $role = $this->roleModel->find($user['role_id']);

        session()->set([
            'user_id'      => $user['id'],
            'user_uuid'    => $user['uuid'],
            'user_name'    => $user['name'],
            'user_email'   => $user['email'],
            'role_id'      => $user['role_id'],
            'role_slug'    => $role ? $role['slug'] : 'member',
            'role_name'    => $role ? $role['name'] : 'Member',
            'is_logged_in' => true,
        ]);

        if ($role && in_array($role['slug'], ['super-admin', 'admin-operasional', 'kasir', 'petugas-dermaga', 'supervisor', 'manajer'])) {
            return redirect()->to('admin/dashboard')->with('success', 'Selamat datang kembali, ' . $user['name']);
        }

        return redirect()->to('/')->with('success', 'Berhasil login.');
    }

    public function registerView()
    {
        return view('front/register', ['title' => 'Daftar Member - SpeedExpress']);
    }

    public function registerProcess()
    {
        $name     = trim($this->request->getPost('name'));
        $email    = trim($this->request->getPost('email'));
        $phone    = trim($this->request->getPost('phone'));
        $password = trim($this->request->getPost('password'));

        if ($this->userModel->where('email', $email)->first()) {
            return redirect()->back()->with('error', 'Email sudah terdaftar. Silakan gunakan email lain.');
        }

        $memberRole = $this->roleModel->where('slug', 'member')->first();
        $uuid       = sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x', mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0x0fff) | 0x4000, mt_rand(0, 0x3fff) | 0x8000, mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff));

        $this->userModel->insert([
            'uuid'          => $uuid,
            'role_id'       => $memberRole ? $memberRole['id'] : 7,
            'name'          => $name,
            'email'         => $email,
            'phone'         => $phone,
            'password_hash' => password_hash($password, PASSWORD_BCRYPT),
            'is_active'     => 1
        ]);

        return redirect()->to('login')->with('success', 'Pendaftaran berhasil. Silakan login.');
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('login')->with('success', 'Anda telah logout.');
    }
}
