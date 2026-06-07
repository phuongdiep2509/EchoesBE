<?php

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

$requestUri = $_SERVER['REQUEST_URI'] ?? '';
if (preg_match('#^/(echoesbe|ECHOESBE)(/|$)#', $requestUri)) {
    header('Location: '.preg_replace('#^/(echoesbe|ECHOESBE)#', '/EchoesBE', $requestUri), true, 302);
    exit;
}

// Determine if the application is in maintenance mode...
if (file_exists($maintenance = __DIR__.'/../storage/framework/maintenance.php')) {
    require $maintenance;
}

// Register the Composer autoloader...
require __DIR__.'/../vendor/autoload.php';

// Bootstrap Laravel and handle the request...
/** @var Application $app */
$app = require_once __DIR__.'/../bootstrap/app.php';

$app->handleRequest(Request::capture());
