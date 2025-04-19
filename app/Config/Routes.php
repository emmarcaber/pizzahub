<?php

use App\Controllers\Home;
use App\Controllers\User;
use App\Controllers\Admin;
use App\Controllers\Pizza;
use App\Controllers\Category;
use App\Controllers\Authenticated;
use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', [Home::class, 'index'], ['as' => 'home.index']);

$routes->group('', function($routes) {
    $routes->get('register', 'Authenticated::register', ['as' => 'auth.register']);
    $routes->post('register', 'Authenticated::attemptRegister', ['as' => 'auth.attemptRegister']);

    $routes->get('login', 'Authenticated::login', ['as' => 'auth.login']);
    $routes->post('login', 'Authenticated::attemptLogin', ['as' => 'auth.attemptLogin']);
    
    $routes->get('logout', 'Authenticated::logout', ['as' => 'auth.logout']);
});


$routes->group('admin', ['as' => 'admin.', 'filter' => 'auth:admin'], function ($routes) {
    $routes->get('dashboard', [Admin::class, 'index'], ['as' => 'admin.index']);

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

    $routes->group('pizzas', ['as' => 'pizzas.'], function ($routes) {
        $routes->get('/dashboard', [Pizza::class, 'index'], ['as' => 'admin.pizzas.index']);

        $routes->get('create', [Pizza::class, 'create'], ['as' => 'admin.pizzas.create']);
        $routes->post('store', [Pizza::class, 'store'], ['as' => 'admin.pizzas.store']);

        $routes->get('edit/(:num)', [Pizza::class, 'edit'], ['as' => 'admin.pizzas.edit']);
        $routes->put('update/(:num)', [Pizza::class, 'update'], ['as' => 'admin.pizzas.update']);

        $routes->delete('delete/(:num)', [Pizza::class, 'delete'], ['as' => 'admin.pizzas.delete']);
    });
});
