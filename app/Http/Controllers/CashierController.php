<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Shift;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class CashierController extends Controller
{
    // 1. Menampilkan Halaman Kasir
    public function index()
    {
        $products = Product::with('category')->get();
        $categories = Category::all();
        
        // Ambil riwayat transaksi HARI INI untuk kasir yang sedang login
        $todayTransactions = Transaction::with('details.product')
            ->where('user_id', Auth::id())
            ->whereDate('created_at', Carbon::today())
            ->orderBy('created_at', 'desc')
            ->get();

        // Pastikan nama view sesuai (contoh: cashier.index)
        return view('cashier.index', compact('products', 'categories', 'todayTransactions'));
    }

    // 2. Memproses Transaksi & Mengurangi Stok
    public function processTransaction(Request $request)
    {
        $request->validate([
            'cart' => 'required|array',
            'payment_method' => 'required|string',
            'paid_amount' => 'required|numeric'
        ]);

        DB::beginTransaction();
        try {
            $totalAmount = 0;
            
            // Hitung grand total dari keranjang
            foreach ($request->cart as $item) {
                $totalAmount += ($item['price'] * $item['qty']);
            }

            // Validasi uang kurang jika pembayaran tunai
            if ($request->payment_method == 'cash' && $request->paid_amount < $totalAmount) {
                return response()->json(['success' => false, 'message' => 'Uang tunai tidak mencukupi!']);
            }

            // Cari shift yang sedang aktif (jika ada) untuk mengisi shift_id
            $activeShift = Shift::where('status', 'active')->first();
            $shiftId = $activeShift ? $activeShift->id : null;

            $paidAmount = $request->payment_method == 'qris' ? $totalAmount : $request->paid_amount;
            $changeAmount = $request->payment_method == 'qris' ? 0 : ($request->paid_amount - $totalAmount);

            // Simpan Data Transaksi ke Database
            $transaction = Transaction::create([
                'shift_id' => $shiftId,
                'user_id' => Auth::id(),
                'invoice_number' => 'INV-' . time() . '-' . Auth::id(),
                'total_amount' => $totalAmount,
                'pay_amount' => $totalAmount, // Sesuai dengan fillable model Anda
                'paid_amount' => $paidAmount, 
                'change_amount' => $changeAmount,
                'payment_method' => $request->payment_method,
                'notes' => 'Transaksi via Kasir'
            ]);

            // Simpan Detail Transaksi & Kurangi Stok Produk
            foreach ($request->cart as $item) {
                // Lock row agar tidak ada bentrok saat transaksi bersamaan
                $product = Product::lockForUpdate()->find($item['id']);
                
                if ($product->stock < $item['qty']) {
                    throw new \Exception("Stok {$product->name} tidak mencukupi!");
                }

                // Mengurangi stok secara realtime
                $product->decrement('stock', $item['qty']);

                // Simpan barang yang dibeli ke tabel transaction_details
                TransactionDetail::create([
                    'transaction_id' => $transaction->id,
                    'product_id' => $product->id,
                    'quantity' => $item['qty'],
                    'price' => $item['price'],
                ]);
            }

            DB::commit();
            return response()->json([
                'success' => true, 
                'message' => 'Transaksi Berhasil Disimpan!',
                'transaction_id' => $transaction->id,
                'invoice' => $transaction->invoice_number
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    // 3. Fitur Tambah Stok Barang (Restock)
    public function restock(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'qty' => 'required|numeric|min:1'
        ]);

        $product = Product::find($request->product_id);
        $product->increment('stock', $request->qty);

        return response()->json([
            'success' => true,
            'message' => 'Stok ' . $product->name . ' berhasil ditambahkan. Total stok sekarang: ' . $product->stock,
            'new_stock' => $product->stock
        ]);
    }
}