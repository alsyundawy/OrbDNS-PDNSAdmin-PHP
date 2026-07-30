<?php

declare(strict_types=1);

namespace App\Core;

use App\Middleware\AuthMiddleware;
use App\Middleware\CsrfMiddleware;
use App\Middleware\RoleMiddleware;

final class Router
{
    private array $routes = [];

    public function __construct()
    {
        $this->routes = [
            ['GET', '/login', 'AuthController@showLogin', ['csrf']],
            ['POST', '/login', 'AuthController@login', ['csrf']],
            ['GET', '/logout', 'AuthController@logout', ['auth']],
            ['GET', '/2fa', 'AuthController@show2fa', ['auth', 'csrf']],
            ['POST', '/2fa', 'AuthController@verify2fa', ['auth', 'csrf']],
            ['GET', '/', 'DashboardController@index', ['auth']],
            ['GET', '/zones', 'ZoneController@index', ['auth']],
            ['GET', '/zones/create', 'ZoneController@create', ['auth']],
            ['POST', '/zones', 'ZoneController@store', ['auth', 'csrf']],
            ['GET', '/users', 'UserController@index', ['auth', 'role:admin']],
            ['GET', '/users/create', 'UserController@create', ['auth', 'role:admin']],
            ['POST', '/users', 'UserController@store', ['auth', 'role:admin', 'csrf']],
            ['GET', '/api/zones', 'ApiController@zones', ['auth']],
            ['GET', '/api/status', 'ApiController@status', ['auth']],
            ['GET', '/logs', 'LogController@index', ['auth', 'role:admin']],
            ['GET', '/logs/export', 'LogController@exportCsv', ['auth', 'role:admin']],
        ];
    }

    public function dispatch(): void
    {
        $method = strtoupper($_SERVER['REQUEST_METHOD'] ?? 'GET');
        $uri = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';

        foreach ($this->routes as [$routeMethod, $routePath, $action, $middlewares]) {
            if ($routeMethod === $method && $routePath === $uri) {
                $this->executeMiddlewares($middlewares);
                [$controller, $actionMethod] = explode('@', $action);
                $class = 'App\\Controllers\\' . $controller;
                (new $class())->{$actionMethod}();
                return;
            }
        }

        http_response_code(404);
        echo 'Not Found';
    }

    private function executeMiddlewares(array $middlewares): void
    {
        foreach ($middlewares as $mw) {
            if ($mw === 'auth') {
                AuthMiddleware::handle();
            } elseif ($mw === 'csrf') {
                CsrfMiddleware::handle();
            } elseif (str_starts_with($mw, 'role:')) {
                RoleMiddleware::handle(substr($mw, 5));
            }
        }
    }
}
