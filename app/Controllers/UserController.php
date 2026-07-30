<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Config;
use App\Core\Controller;
use App\Core\Helper;
use App\Core\Validator;
use App\Models\ActivityLog;
use App\Models\User;

final class UserController extends Controller
{
    private const CREATE_URL = '/users/create';

    public function index(): void
    {
        $users = User::all();
        $title = 'User Management — PDNS Admin';
        $viewFile = APP_PATH . '/Views/users/index.php';
        require_once APP_PATH . '/Views/layouts/app.php';
        unset($users, $title, $viewFile);
    }

    public function create(): void
    {
        $title = 'Tambah User Baru — PDNS Admin';
        $viewFile = APP_PATH . '/Views/users/create.php';
        require_once APP_PATH . '/Views/layouts/app.php';
        unset($title, $viewFile);
    }

    public function store(): void
    {
        $username = trim((string) ($_POST['username'] ?? ''));
        $email = trim((string) ($_POST['email'] ?? ''));
        $password = (string) ($_POST['password'] ?? '');
        $role = trim((string) ($_POST['role'] ?? 'user'));

        $validator = (new Validator())
            ->required('username', $username, 'Username wajib diisi.')
            ->required('email', $email, 'Email wajib diisi.')
            ->email('email', $email, 'Format email tidak valid.')
            ->required('password', $password, 'Password wajib diisi.')
            ->minLen('password', $password, 8, 'Password minimal 8 karakter.')
            ->in('role', $role, ['admin', 'user'], 'Role tidak valid.');

        if (!$validator->passes()) {
            Helper::flashSet('danger', $validator->first());
            Helper::redirect(self::CREATE_URL);
        }

        if (User::exists($username, $email)) {
            Helper::flashSet('danger', 'Username atau email sudah digunakan.');
            Helper::redirect(self::CREATE_URL);
        }

        $config = Config::all();
        $cost = (int) ($config['security']['bcrypt_cost'] ?? 12);
        $passwordHash = password_hash($password, PASSWORD_BCRYPT, ['cost' => $cost]);

        if (User::create($username, $email, $passwordHash, $role)) {
            $currentUser = Auth::user();
            ActivityLog::log($currentUser['id'] ?? null, 'USER_CREATE', $username, 'Created user with role ' . $role);
            Helper::flashSet('success', 'User ' . Helper::e($username) . ' berhasil ditambahkan.');
            Helper::redirect('/users');
        } else {
            Helper::flashSet('danger', 'Gagal menambahkan user baru.');
            Helper::redirect(self::CREATE_URL);
        }
    }
}
