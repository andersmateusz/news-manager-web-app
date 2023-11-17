<?php

declare(strict_types=1);

use Andersma\NewsManager\Container;
use Andersma\NewsManager\Router;

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../config/services.php';
require_once __DIR__ . '/../config/routing.php';

session_start();

if (!isset($_SESSION['user']) && parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) !== '/login') {
    header('Location: /login', true, 301);
    exit;
}

Container::get(Router::class)->handle();