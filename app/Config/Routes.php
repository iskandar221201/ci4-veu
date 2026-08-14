<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */

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
    // Auth (cookie mode)
    $routes->post('auth/logout', 'Api\AuthController::logout');
    $routes->get('auth/me', 'Api\AuthController::me');

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

// =========================================================
// Shield routes — exclude session-based auth routes so they
// don't shadow the Vue SPA catch-all below. The SPA handles
// auth client-side via the token API (/api/auth/*).
// =========================================================
service('auth')->routes($routes, ['except' => ['register', 'login', 'magic-link', 'logout', 'auth-actions']]);

// =========================================================
// SPA catch-all — MUST be the last route.
// Serves the Vue SPA (frontend/dist/index.html) for every
// non-API GET request. Vue Router resolves the actual view.
// =========================================================
$routes->get('(.*)', 'SpaController::index');
