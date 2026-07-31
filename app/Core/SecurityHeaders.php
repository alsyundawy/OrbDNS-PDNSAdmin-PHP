<?php

declare(strict_types=1);

namespace App\Core;

final class SecurityHeaders
{
    public static function apply(): void
    {
        $nonce = base64_encode(random_bytes(18));
        $_SESSION['csp_nonce'] = $nonce;
        header('X-Frame-Options: DENY');
        header('X-Content-Type-Options: nosniff');
        header('Referrer-Policy: strict-origin-when-cross-origin');
        header('Permissions-Policy: geolocation=(), camera=(), microphone=()');
        $csp = "default-src 'self'; script-src 'self' 'nonce-{$nonce}'; "
            . "style-src 'self' 'unsafe-inline'; img-src 'self' data:; "
            . "font-src 'self'; connect-src 'self'; frame-ancestors 'none'; "
            . "base-uri 'self'; form-action 'self'";
        header('Content-Security-Policy: ' . $csp);
    }
}
