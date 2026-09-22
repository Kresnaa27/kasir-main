<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Shift;
use Carbon\Carbon;

class ShiftController extends Controller
{
    public function getTodayShifts()
    {
        // Mendapatkan tanggal hari ini (format: YYYY-MM-DD)
        $today = Carbon::today()->toDateString();

        // Cek data shift di database berdasarkan tanggal hari ini
        $shifts = Shift::where('date', $today)->get();

        // Jika hari berganti dan belum ada data shift untuk hari ini, buat baru secara otomatis
        if ($shifts->isEmpty()) {
            $defaultShifts = [
                ['name' => 'Dewa', 'shift' => 'Shift Pagi', 'status' => 'active'],
                ['name' => 'Kresnaa', 'shift' => 'Shift Siang', 'status' => 'inactive'],
                ['name' => 'Surya', 'shift' => 'Shift Sore', 'status' => 'inactive'],
            ];

            foreach ($defaultShifts as $emp) {
                Shift::create([
                    'name' => $emp['name'],
                    'shift' => $emp['shift'],
                    'customers' => 0,
                    'total_sales' => 0,
                    'items_sold' => 'Belum mulai shift',
                    'date' => $today,
                    'status' => $emp['status']
                ]);
            }

            // Ambil ulang data yang baru saja dibuat
            $shifts = Shift::where('date', $today)->get();
        }

        return response()->json($shifts);
    }
}