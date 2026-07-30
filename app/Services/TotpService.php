<?php

declare(strict_types=1);

namespace App\Services;

use RobThree\Auth\TwoFactorAuth;

final class TotpService
{
    private TwoFactorAuth $tfa;

    public function __construct()
    {
        $appName = (require_once APP_PATH . '/Config/config.php')['app']['name'];
        $this->tfa = new TwoFactorAuth($appName);
    }

    public function getQrImageDataUri(string $username, string $secret): string
    {
        return $this->tfa->getQRCodeImageAsDataUri($username, $secret);
    }

    public function verifyCode(string $secret, string $code): bool
    {
        return $this->tfa->verifyCode($secret, $code);
    }
}
