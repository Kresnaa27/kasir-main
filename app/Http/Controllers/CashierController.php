<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class CashierController extends Controller
{
    public function index()
    {
        $products = Product::with('category')->get();
        $categories = Category::withCount('products')->get();
        $todayTransactions = Transaction::with('details.product')
            ->whereDate('created_at', Carbon::today())
            ->latest()
            ->get();

        return view('cashier.index', compact('products', 'categories', 'todayTransactions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'pay_amount' => 'required|numeric',
            'cart' => 'required|array',
        ]);

        DB::beginTransaction();
        try {
            $totalAmount = 0;
            foreach ($request->cart as $item) {
                $totalAmount += $item['price'] * $item['quantity'];
            }

            // Generate nomor invoice unik otomatis
            $invoiceNumber = 'INV-' . date('YmdHis') . '-' . rand(100, 999);

            $transaction = Transaction::create([
                'invoice_number' => $invoiceNumber,
                'total_amount'   => $totalAmount,
                'paid_amount'    => $request->pay_amount,
                'change_amount'  => $request->pay_amount - $totalAmount,
                'payment_method' => $request->payment_method ?? 'cash',
            ]);

            foreach ($request->cart as $item) {
                $subtotal = $item['price'] * $item['quantity'];

                TransactionDetail::create([
                    'transaction_id' => $transaction->id,
                    'product_id'     => $item['id'],
                    'quantity'       => $item['quantity'],
                    'price'          => $item['price'],
                    'subtotal'       => $subtotal,
                ]);

                // Potong stok produk di database
                Product::where('id', $item['id'])->decrement('stock', $item['quantity']);
            }

            DB::commit();
            return response()->json(['success' => true, 'message' => 'Transaksi berhasil disimpan!']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}