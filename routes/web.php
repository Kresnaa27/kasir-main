<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ShiftController;
use App\Http\Controllers\CashierController;
use App\Http\Controllers\AuthController;

// 1. Halaman utama (Akan otomatis mengecek apakah sudah login atau belum)
Route::get('/', function () {
    return auth()->check() ? redirect('/cashier') : redirect('/login');
});

// 2. Route untuk Tamu / Belum Login (Hanya bisa diakses jika belum login)
Route::middleware(['guest'])->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

// 3. Route yang Membutuhkan Login (Area Terproteksi Kasir)
Route::middleware(['auth'])->group(function () {
    // Route Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Halaman Utama Kasir
    Route::get('/cashier', [CashierController::class, 'index'])->name('cashier.index');

    // Endpoint Proses Checkout / Pembayaran
    Route::post('/cashier/checkout', [CashierController::class, 'checkout'])->name('cashier.checkout');
});