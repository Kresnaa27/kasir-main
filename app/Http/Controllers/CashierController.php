<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CashierController extends Controller
{
    public function index()
    {
        // Ambil nama kasir yang sedang login
        $namaKasir = auth()->user()->name;

        // Tampilkan view index di dalam folder cashier
        return view('cashier.index', compact('namaKasir'));
    }
}