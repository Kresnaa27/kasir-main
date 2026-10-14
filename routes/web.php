<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ShiftController;
use App\Http\Controllers\CashierController;
use App\Http\Controllers\AuthController;

// 1. Halaman utama (Akan otomatis mengecek apakah sudah login atau belum)
Route::get('/', function () {
    return auth()->check() ? redirect('/cashier') : redirect('/login');
});

Route::middleware(['guest'])->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

Route::middleware(['auth'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/cashier', [CashierController::class, 'index'])->name('cashier.index');
    Route::post('/cashier/checkout', [CashierController::class, 'checkout'])->name('cashier.checkout');
    Route::get('/cashier/history', [CashierController::class, 'getOrderHistory']);
});