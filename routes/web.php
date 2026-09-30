<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ShiftController;
use App\Http\Controllers\CashierController;
use App\Http\Controllers\AuthController;

// Halaman utama: Mengecek apakah user sudah login atau belum
Route::get('/', function () {
    if (auth()->check()) {
        // Cek role user (pastikan kolom 'role' ada di tabel users jika ingin dipakai)
        return (isset(auth()->user()->role) && auth()->user()->role === 'admin') 
            ? redirect('/admin/dashboard') 
            : redirect('/cashier');
    }
    return redirect('/login');
});

// Route Autentikasi (Hanya bisa diakses jika belum login / guest)
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login')->middleware('guest');
Route::post('/login', [AuthController::class, 'login'])->middleware('guest');

// Route Logout (Hanya bisa diakses jika sudah login)
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// ==========================================
// ROUTE AREA TERPROTEKSI (Wajib Login)
// ==========================================
Route::middleware(['auth'])->group(function () {
    
    // Halaman Utama Kasir
    Route::get('/cashier', [CashierController::class, 'index'])->name('cashier.index');
    
    // Rute API untuk memproses transaksi (Ajax dari JavaScript)
    Route::post('/kasir/transaction', [CashierController::class, 'processTransaction'])->name('cashier.transaction');
    
    // Rute API untuk menambah stok (Restock)
    Route::post('/kasir/restock', [CashierController::class, 'restock'])->name('cashier.restock');
    
    // Rute pendukung shift hari ini
    Route::get('/api/shifts-today', [ShiftController::class, 'getTodayShifts']);
    
    // Halaman Dashboard Admin
    Route::get('/admin/dashboard', function () {
        $namaAdmin = auth()->user()->name;
        return "Halo $namaAdmin, ini halaman Dashboard Admin.";
    });
   
});