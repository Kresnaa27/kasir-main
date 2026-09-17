<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CashierController; // 1. Tambahkan ini di atas

// Halaman utama mengecek status login
Route::get('/', function () {
    if (auth()->check()) {
        return auth()->user()->role === 'admin' 
            ? redirect('/admin/dashboard') 
            : redirect('/cashier');
    }
    return redirect('/login');
});

// Route Autentikasi
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login')->middleware('guest');
Route::post('/login', [AuthController::class, 'login'])->middleware('guest');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Route Area Terproteksi (Login Required)
Route::middleware(['auth'])->group(function () {
    
    // Area Kasir (Menggunakan Controller yang baru dibuat)
    Route::get('/cashier', [CashierController::class, 'index'])->name('cashier.index');

    // Area Admin Dashboard
    Route::get('/admin/dashboard', function () {
        $namaAdmin = auth()->user()->name;
        return "Halo $namaAdmin, ini halaman Dashboard Admin.";
    });

});