<?php

use App\Controllers\User;
use App\Controllers\Admin;
use App\Controllers\Category;
use App\Controllers\Authenticated;
use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
// $routes->get('/', 'Home::index');
$routes->get('/auth', [Authenticated::class, 'index']);


$routes->group('admin', ['as' => 'admin.'], function ($routes) {
    $routes->get('/', [Admin::class, 'index'], ['as' => 'admin.index']);

    $routes->group('users', ['as' => 'users.'], function ($routes) {
        $routes->get('/', [User::class, 'index'], ['as' => 'admin.users.index']);
        $routes->delete('delete/(:num)', [User::class, 'delete'], ['as' => 'admin.users.delete']);
    });

    $routes->group('categories', ['as' => 'categories.'], function ($routes) {
        $routes->get('/', [Category::class, 'index'], ['as' => 'admin.categories.index']);

        $routes->get('create', [Category::class, 'create'], ['as' => 'admin.categories.create']);
        $routes->post('store', [Category::class, 'store'], ['as' => 'admin.categories.store']);

        $routes->get('edit/(:num)', [Category::class, 'edit'], ['as' => 'admin.categories.edit']);
        $routes->put('update/(:num)', [Category::class, 'update'], ['as' => 'admin.categories.update']);

        $routes->delete('delete/(:num)', [Category::class, 'delete'], ['as' => 'admin.categories.delete']);
    });

});
