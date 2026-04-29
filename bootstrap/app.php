<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

$app = Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        //
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();

/*
|--------------------------------------------------------------------------
| Vercel Compatibility Logic
|--------------------------------------------------------------------------
*/
// Cek apakah aplikasi sedang berjalan di server Vercel
if (env('VERCEL_JOB_ID') || env('NOW_REGION')) {
    $app->useStoragePath('/tmp');
}

return $app;