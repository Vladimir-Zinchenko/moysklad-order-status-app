<?php

use App\Controller\Api\AuthController;
use App\Controller\Api\CustomerOrderController;
use App\Controller\MainController;

return [
    'debug' => true,
    'root_path' => __DIR__,
    'routes' => [
        '/' => [MainController::class, 'index'],
        '/login' => [MainController::class, 'login'],
        '/orders' => [MainController::class, 'orders'],

        // API
        '/api/cutomerorder' => [CustomerOrderController::class, 'index'],
        '/api/auth' => [AuthController::class, 'index'],
        '/api/auth/logout' => [AuthController::class, 'logout'],
    ],
];
