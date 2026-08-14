<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */

// =========================================================
// Public Routes
// =========================================================
$routes->get('/', 'Home::index');

// =========================================================
// Web Routes (CI4 Kit v3.0)
// =========================================================
$routes->group('', static function ($routes) {
    $routes->get('login',           'Web\UserWebController::loginPage');
    $routes->get('users',           'Web\UserWebController::index');
    $routes->get('users/create',    'Web\UserWebController::create');
    $routes->get('users/(:num)',    'Web\UserWebController::detail/$1');
});

// ⚠️  PLACEHOLDER — AuthController does not exist in this kit yet.
//     This route will throw PageNotFoundException if hit in production.
//     Remove this line or create app/Controllers/AuthController.php before deploying.
//     See Shield documentation for login implementation: https://shield.codeigniter.com
$routes->post('login', 'AuthController::login');

// =========================================================
// API Routes — Public (no auth required)
// =========================================================
$routes->group('api', static function ($routes) {
    $routes->get('ping', 'Api\PingController::index');
    $routes->post('auth/login', 'Api\AuthController::login');
});


// =========================================================
// API Routes — Protected (apiKeyFilter)
// =========================================================
$routes->group('api', ['filter' => 'apiKeyFilter'], static function (RouteCollection $routes): void {
    // Health check (authenticated)
    $routes->get('protected', 'Api\PingController::check');

    // User resource (CRUD)
    $routes->get('users', 'Api\UserController::index');
    $routes->post('users', 'Api\UserController::create');
    $routes->get('users/(:num)', 'Api\UserController::show/$1');
    $routes->put('users/(:num)', 'Api\UserController::update/$1');
    $routes->delete('users/(:num)', 'Api\UserController::delete/$1');

    // TUS chunked upload
    $routes->match(['options', 'post'], 'upload/tus', 'Api\TusController::handle');
    $routes->match(['options', 'post', 'patch', 'head', 'delete'], 'upload/tus/(:any)', 'Api\TusController::handle/$1');
});

$routes->group('', static function ($routes) {
    // ⚠️ Auth checking for web routes should be handled by auth.js checking localStorage on frontend,
    // or by custom web auth filter if using session auth. Since CI4 Kit v3 uses token auth,
    // the views layer is public and auth redirect is handled by JS.
    $routes->get('dashboard', 'Web\DashboardController::index');
    $routes->get('showcase', 'Web\ShowcaseController::index');
});


// Shield auth routes (login, register, magic-link, etc.)
service('auth')->routes($routes);
