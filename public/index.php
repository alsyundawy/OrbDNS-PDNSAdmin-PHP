<?php

declare(strict_types=1);

define('BASE_PATH', dirname(__DIR__));
define('APP_PATH', BASE_PATH . '/app');
define('APP_VERSION', '1.3.0');

require_once BASE_PATH . '/vendor/autoload.php';

$config = require_once APP_PATH . '/Config/config.php';
date_default_timezone_set($config['app']['timezone']);

if ($config['app']['enable_gzip']) {
    if (extension_loaded('zlib') && !ini_get('zlib.output_compression')) {
        ob_start('ob_gzhandler');
    } else {
        ob_start();
    }
}

if ($config['app']['env'] === 'production') {
    ini_set('display_errors', '0');
    ini_set('log_errors', '1');
    error_reporting(E_ALL);
}

if ($config['app']['force_https'] && PHP_SAPI !== 'cli') {
    $https = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || ((int) ($_SERVER['SERVER_PORT'] ?? 0) === 443);
    if (!$https) {
        // DevSkim: ignore DS162092
        header('Location: https://' . ($_SERVER['HTTP_HOST'] ?? 'localhost') . ($_SERVER['REQUEST_URI'] ?? '/'), true, 301);
        exit;
    }
}

use App\Core\Router;
use App\Core\SecurityHeaders;
use App\Core\Session;

Session::start();
SecurityHeaders::apply();
(new Router())->dispatch();
