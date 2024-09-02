<?php

/**
 * @var Illuminate\Foundation\Configuration\Middleware $middleware
 */
$middleware->use([
//    \Illuminate\Http\Middleware\TrustHosts::class,
//    \Illuminate\Http\Middleware\TrustProxies::class,
//    \Illuminate\Http\Middleware\HandleCors::class,
//    \Illuminate\Foundation\Http\Middleware\PreventRequestsDuringMaintenance::class,
//    \Illuminate\Http\Middleware\ValidatePostSize::class,
//    \Illuminate\Foundation\Http\Middleware\TrimStrings::class,
//    \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class,
    \App\Middlewares\RequestLogMiddleware::class,
]);
