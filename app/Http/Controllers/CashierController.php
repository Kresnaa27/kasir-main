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
    public function index(Request $request)
    {
        $categories = Category::all();
        $selectedCategory = $request->input('category');
        $search = $request->input('search');

        $products = Product::when($selectedCategory, function($query) use ($selectedCategory) {
            return $query->where('category_id', $selectedCategory);
        })
        ->when($search, function($query) use ($search) {
            return $query->where('name', 'like', '%' . $search . '%');
        })
        ->get();

        return view('cashier.index', compact('categories', 'products', 'selectedCategory', 'search'));
    }
     public function getOrderHistory()
    {
        $orders = Order::with('items')->latest()->take(20)->get();
        return response()->json($orders);
    }
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
            $tax = $subtotal * 0.10;
            $grandTotal = $subtotal + $tax; // Diubah dari $total ke $grandTotal

            $order = Order::create([
                'order_number' => 'ORD-' . strtoupper(uniqid()),
                'order_type' => $request->order_type,
                'subtotal' => $subtotal,
                'tax' => $tax,
                'grand_total' => $grandTotal, // Sesuaikan dengan nama kolom database
                'payment_method' => $request->payment_method,
                'status' => 'Completed'
            ]);

            foreach ($request->items as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['product_id'] ?? $item['id'], // Menangani kedua kemungkinan key
                    'product_name' => $item['product_name'] ?? 'Produk', // Menyimpan nama produk jika ada kolomnya
                    'quantity' => $item['quantity'],
                    'price' => $item['price']
                ]);
            }

            DB::commit();
            return response()->json([
                'success' => true, 
                'message' => 'Transaksi berhasil!', 
                'order' => $order
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false, 
                'message' => $e->getMessage()
            ], 500);
        }
    }
}