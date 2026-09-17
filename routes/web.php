<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

// Halaman utama diarahkan ke login
Route::get('/', function () {
    return redirect('/login');
});

// Route Autentikasi
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login')->middleware('guest');
Route::post('/login', [AuthController::class, 'login'])->middleware('guest');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Route Area Kasir sementara (nanti kita buat view-nya)
Route::middleware(['auth'])->group(function () {
    Route::get('/cashier', function () {
        return "Halo " . auth()->user()->name . ", selamat datang di Halaman Kasir Modern Bergaya Minimarket!";
    });

    Route::get('/admin/dashboard', function () {
        return "Halo " . auth()->user()->name . ", ini halaman Dashboard Admin.";
    });
});