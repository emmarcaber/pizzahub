<?php

use App\Controllers\Admin;
use App\Controllers\Authenticated;
use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->get('/auth', [Authenticated::class, 'index']);


$routes->group('admin', static function ($routes) {
    $routes->get('/', [Admin::class, 'index']);
});
