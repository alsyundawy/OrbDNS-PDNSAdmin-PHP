<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Controller;
use App\Core\Helper;
use App\Core\RateLimiter;
use App\Core\Validator;
use App\Models\ActivityLog;
use App\Models\User;
use App\Services\TotpService;

final class AuthController extends Controller
{
    public function showLogin(): void
    {
        if (Auth::check()) {
            Helper::redirect('/');
        }
        $title = 'Login — PDNS Admin';
        require APP_PATH . '/Views/auth/login.php';
    }

    public function login(): void
    {
        $config = require APP_PATH . '/Config/config.php';
        $username = trim((string) ($_POST['username'] ?? ''));
        $password = (string) ($_POST['password'] ?? '');
        $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
        $validator = (new Validator())->required('username', $username, 'Username wajib diisi.')->required('password', $password, 'Password wajib diisi.');
        if (!$validator->passes()) {
            Helper::flashSet('danger', $validator->first());
            Helper::redirect('/login');
        }
        $limiter = new RateLimiter();
        $rateKey = 'login:' . hash('sha256', strtolower($username) . '|' . $ip);
        $rate = $limiter->hit($rateKey, $config['rate_limit']['login_max'], $config['rate_limit']['login_window']);
        if (!$rate['allowed']) {
            ActivityLog::log(null, 'LOGIN_RATE_LIMITED', $username, 'Too many requests', $ip);
            Helper::flashSet('danger', 'Terlalu banyak percobaan login. Coba lagi nanti.');
            Helper::redirect('/login');
        }
        if (!Auth::attempt($username, $password)) {
            User::incrementFailedLogin($username);
            ActivityLog::log(null, 'LOGIN_FAILED', $username, 'Invalid credentials', $ip);
            Helper::flashSet('danger', 'Username atau password tidak valid.');
            Helper::redirect('/login');
        }
        $limiter->clear($rateKey);
        $user = Auth::user();
        if ($user !== null) {
            User::updateLastLogin($user['id']);
            ActivityLog::log($user['id'], 'LOGIN_SUCCESS', $username, 'Successful login', $ip);
            $dbUser = User::findById($user['id']);
            if ($config['security']['totp_enabled'] && $dbUser !== null && (int) ($dbUser['totp_enabled'] ?? 0) === 1) {
                $_SESSION['2fa_verified'] = false;
                Helper::redirect('/2fa');
            }
        }
        $_SESSION['2fa_verified'] = true;
        Helper::redirect('/');
    }

    public function show2fa(): void
    {
        $user = Auth::user();
        if ($user === null) {
            Helper::redirect('/login');
        }
        $dbUser = User::findById($user['id']);
        $title = '2FA Verification — PDNS Admin';
        $secret = (string) ($dbUser['totp_secret'] ?? '');
        $qr = $secret !== '' ? (new TotpService())->getQrImageDataUri($user['username'], $secret) : '';
        require APP_PATH . '/Views/auth/2fa.php';
    }

    public function verify2fa(): void
    {
        $user = Auth::user();
        if ($user === null) {
            Helper::redirect('/login');
        }
        $dbUser = User::findById($user['id']);
        $code = trim((string) ($_POST['otp_code'] ?? ''));
        if ($dbUser === null || (string) ($dbUser['totp_secret'] ?? '') === '') {
            Helper::flashSet('danger', '2FA belum dikonfigurasi.');
            Helper::redirect('/login');
        }
        if (!(new TotpService())->verifyCode((string) $dbUser['totp_secret'], $code)) {
            ActivityLog::log($user['id'], 'TOTP_FAILED', $user['username'], 'Invalid TOTP code');
            Helper::flashSet('danger', 'Kode OTP tidak valid.');
            Helper::redirect('/2fa');
        }
        $_SESSION['2fa_verified'] = true;
        ActivityLog::log($user['id'], 'TOTP_SUCCESS', $user['username'], '2FA verified');
        Helper::redirect('/');
    }

    public function logout(): void
    {
        $user = Auth::user();
        if ($user !== null) {
            ActivityLog::log($user['id'], 'LOGOUT', $user['username'], 'User logout');
        }
        Auth::logout();
        Helper::flashSet('success', 'Berhasil logout.');
        Helper::redirect('/login');
    }
}
