<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

require __DIR__ . '/constants.php';

$app = Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: null,
        api: glob(base_path() . '/routes/api*.php'),
        commands: __DIR__ . '/../routes/console.php',
        apiPrefix: '',
    )
    ->withMiddleware(function (Middleware $middleware) {
        require __DIR__ . '/middlewares.php';
    })
    ->withExceptions(function (Exceptions $exceptions) {
        require __DIR__ . '/exceptions.php';
    })
    ->create();

$app->useStoragePath(base_path() . '/runtime/');

require __DIR__ . '/functions.php';

return $app;
