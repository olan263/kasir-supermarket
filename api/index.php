<?php
// Memanggil autoload dari root
require __DIR__ . '/../vendor/autoload.php';

// Memanggil file bootstrap/app.php yang baru saja kamu edit
$app = require_once __DIR__ . '/../bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

$response->send();
$kernel->terminate($request, $response);