<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\DB;

class CashierController extends Controller
{
    /**
     * Menampilkan halaman kasir, kategori, dan produk.
     */
    public function index(Request $request)
    {
        $categories = Category::all();
        $selectedCategory = $request->get('category');

        // Ambil produk berdasarkan kategori yang dipilih (jika ada)
        $products = Product::when($selectedCategory, function($query) use ($selectedCategory) {
            return $query->where('category_id', $selectedCategory);
        })->get();

        // Sesuaikan nama view dengan lokasi file blade Anda (misal: resources/views/cashier/index.blade.php)
        return view('cashier.index', compact('categories', 'products', 'selectedCategory'));
    }

    /**
     * Memproses transaksi/checkout yang dikirim dari keranjang kasir (AJAX).
     */
    public function checkout(Request $request)
    {
        $request->validate([
            'order_type' => 'required|in:Dine In,Takeaway',
            'payment_method' => 'required|string',
            'items' => 'required|array|min:1',
        ]);

        DB::beginTransaction();
        try {
            $subtotal = collect($request->items)->sum(fn($item) => $item['price'] * $item['quantity']);
            $tax = $subtotal * 0.10; // Pajak 10%
            $total = $subtotal + $tax;

            // Simpan data utama pesanan
            $order = Order::create([
                'order_number' => 'ORD-' . strtoupper(uniqid()),
                'order_type' => $request->order_type,
                'subtotal' => $subtotal,
                'tax' => $tax,
                'total' => $total,
                'payment_method' => $request->payment_method,
                'status' => 'Completed'
            ]);

            // Simpan detail item yang dibeli
            foreach ($request->items as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'notes' => $item['notes'] ?? null
                ]);
            }

            DB::commit();
            return response()->json([
                'success' => true, 
                'message' => 'Transaksi berhasil disimpan!', 
                'order' => $order
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false, 
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
}