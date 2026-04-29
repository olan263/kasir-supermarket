<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    
    // --- Route Profile ---
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // --- Route Kasir (Transaction) ---
    Route::get('/kasir', [TransactionController::class, 'index'])->name('kasir.index');
    Route::post('/kasir/add', [TransactionController::class, 'addToCart'])->name('kasir.add');
    
    // PERBAIKAN DI SINI: Ganti get jadi delete, dan pastikan name-nya sama dengan yang dipanggil
    Route::delete('/kasir/remove/{id}', [TransactionController::class, 'removeFromCart'])->name('kasir.remove');
    
    Route::get('/kasir/clear', [TransactionController::class, 'clearCart'])->name('kasir.clear');
    Route::post('/kasir/store', [TransactionController::class, 'store'])->name('kasir.store');
    Route::get('/kasir/pdf/{id}', [TransactionController::class, 'downloadPdf'])->name('kasir.pdf');

    // --- Route Manajemen Barang ---
    Route::resource('products', ProductController::class);
});

require __DIR__.'/auth.php';