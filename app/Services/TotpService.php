<?php

declare(strict_types=1);

namespace App\Services;

use App\Core\Config;
use RobThree\Auth\TwoFactorAuth;

final class TotpService
{
    private TwoFactorAuth $tfa;

    public function __construct()
    {
        $appName = Config::all()['app']['name'];
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
