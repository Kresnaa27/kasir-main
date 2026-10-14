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
        $selectedCategory = $request->get('category');
        $search = $request->get('search');

        $products = Product::when($selectedCategory, function($query) use ($selectedCategory) {
            return $query->where('category_id', $selectedCategory);
        })
        ->when($search, function($query) use ($search) {
            return $query->where('name', 'like', '%' . $search . '%');
        })
        ->get();

        return view('cashier.index', compact('categories', 'products', 'selectedCategory', 'search'));
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
            $total = $subtotal + $tax;

            $order = Order::create([
                'order_number' => 'ORD-' . strtoupper(uniqid()),
                'order_type' => $request->order_type,
                'subtotal' => $subtotal,
                'tax' => $tax,
                'total' => $total,
                'payment_method' => $request->payment_method,
                'status' => 'Completed'
            ]);

            foreach ($request->items as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['id'],
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