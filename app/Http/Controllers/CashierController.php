<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItems; // <-- PERHATIKAN: Pakai 's' (OrderItems)
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CashierController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::all();
        $selectedCategory = $request->input('category');
        $search = $request->input('search');

        // Hitung nomor order harian secara real-time
        $todayCount = Order::whereDate('created_at', Carbon::today())->count();
        $nextOrderNumber = $todayCount + 1;

        $products = Product::when($selectedCategory, function($query) use ($selectedCategory) {
            return $query->where('category_id', $selectedCategory);
        })
        ->when($search, function($query) use ($search) {
            return $query->where('name', 'like', '%' . $search . '%');
        })
        ->get();

        return view('cashier.index', compact('categories', 'products', 'selectedCategory', 'search', 'nextOrderNumber'));
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
            $grandTotal = $subtotal + $tax;

            // Generate nomor order harian (Contoh: ORD-001, ORD-002, dst.)
            $todayCount = Order::whereDate('created_at', Carbon::today())->count();
            $orderNumber = 'ORD-' . str_pad($todayCount + 1, 3, '0', STR_PAD_LEFT);

            $order = Order::create([
                'order_number' => $orderNumber,
                'order_type' => $request->order_type,
                'subtotal' => $subtotal,
                'tax' => $tax,
                'grand_total' => $grandTotal,
                'payment_method' => $request->payment_method,
                'status' => 'Completed'
            ]);

            foreach ($request->items as $item) {
                OrderItems::create([ // <-- WAJIB ORDERITEMS (DENGAN 'S')
                    'order_id' => $order->id,
                    'product_id' => $item['product_id'] ?? $item['id'],
                    'product_name' => $item['product_name'] ?? 'Produk',
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