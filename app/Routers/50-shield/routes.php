<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */

// Shield routes — exclude session-based auth routes so they
// don't shadow the Vue SPA catch-all. The SPA handles auth
// client-side via the token API (/api/auth/*).
service('auth')->routes($routes, ['except' => ['register', 'login', 'magic-link', 'logout', 'auth-actions']]);
