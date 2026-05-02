<?php

// 1. Aktifkan Debugging Paksa
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// 2. Memanggil autoload dari folder vendor di root
require __DIR__ . '/../vendor/autoload.php';

// 3. Memanggil file bootstrap/app.php
$app = require_once __DIR__ . '/../bootstrap/app.php';

// 4. Proses Request menggunakan HTTP Kernel Laravel
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

// 5. Kirim Response ke Browser
$response->send();

// 6. Selesaikan Proses
$kernel->terminate($request, $response);