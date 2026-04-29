<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    public function index()
    {
        $cart = session()->get('cart', []);
        // Hitung total dari session agar UI konsisten saat pertama kali load
        $total = array_sum(array_column($cart, 'subtotal'));
        
        $products = Product::where('stock', '>', 0)->latest()->get();
        $history = Transaction::latest()->take(10)->get(); 
        
        return view('kasir.index', compact('cart', 'total', 'products', 'history'));
    }

    public function addToCart(Request $request)
{
    $product = Product::find($request->product_id);

    if (!$product) {
        return response()->json(['message' => 'Produk ga ada!'], 404);
    }

    if ($product->stock <= 0) {
        return response()->json(['message' => 'Stok barang habis!'], 400);
    }

    $cart = session()->get('cart', []);

    // Cek jika sudah ada di cart, jangan melebihi stok
    $currentQty = isset($cart[$product->id]) ? $cart[$product->id]['quantity'] : 0;
    if ($currentQty + 1 > $product->stock) {
        return response()->json(['message' => 'Stok tidak cukup!'], 400);
    }

    if (isset($cart[$product->id])) {
        $cart[$product->id]['quantity']++;
    } else {
        $cart[$product->id] = [
            "name" => $product->name,
            "quantity" => 1,
            "price" => (float) $product->selling_price,
        ];
    }
    
    // Hitung ulang subtotal untuk tiap item agar sinkron
    foreach ($cart as $id => $item) {
        $cart[$id]['subtotal'] = $cart[$id]['quantity'] * $cart[$id]['price'];
    }

    session()->put('cart', $cart);

    return response()->json([
        'cart' => $cart,
        'total' => array_sum(array_column($cart, 'subtotal'))
    ]);
}
    public function removeFromCart(Request $request, $id)
    {
        $cart = session()->get('cart', []);
        
        if(isset($cart[$id])) {
            unset($cart[$id]);
            session()->put('cart', $cart);
        }

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'cart' => $cart,
                'total' => (float) array_sum(array_column($cart, 'subtotal')),
                'count' => count($cart)
            ]);
        }

        return redirect()->back()->with('success', 'Barang dihapus!');
    }

    public function store(Request $request)
    {
        $cart = session()->get('cart', []);
        
        if (empty($cart)) {
            return redirect()->back()->with('error', 'Keranjang masih kosong!');
        }

        // Validasi stok sebelum transaksi
        foreach ($cart as $id => $details) {
            $product = Product::find($id);
            if (!$product || $product->stock < $details['quantity']) {
                return redirect()->back()->with('error', 'Stok produk ' . ($product ? $product->name : 'ID ' . $id) . ' tidak cukup!');
            }
        }

        $request->validate([
            'total_price' => 'required|numeric',
            'cash_amount' => 'required|numeric|min:' . $request->total_price,
        ], [
            'cash_amount.min' => 'Uang pembayaran kurang!'
        ]);

        try {
            DB::transaction(function () use ($request, $cart) {
                $trx = Transaction::create([
                    'invoice_number' => 'INV-' . strtoupper(uniqid()),
                    'total_price' => $request->total_price,
                    'cash_amount' => $request->cash_amount,
                    'change_amount' => $request->cash_amount - $request->total_price,
                    'created_at' => now(), // waktu real-time
                    'updated_at' => now(),
                ]);

                foreach ($cart as $id => $details) {
                    TransactionDetail::create([
                        'transaction_id' => $trx->id,
                        'product_id'     => $id,
                        'product_name'   => $details['name'],
                        'quantity'       => $details['quantity'],
                        'price'          => $details['price'],
                        'subtotal'       => $details['subtotal'],
                    ]);

                    // Potong stok produk
                    Product::where('id', $id)->decrement('stock', $details['quantity']);
                }
            });

            // Hapus session setelah sukses
            session()->forget('cart');
            return redirect()->route('kasir.index')->with('success', 'Transaksi Berhasil!');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menyimpan transaksi: ' . $e->getMessage());
        }
    }

    public function downloadPdf($id)
    {
        // Gunakan eager loading 'details' agar data barang muncul di PDF
        $transaction = Transaction::with('details')->findOrFail($id);
        $now = now();
        $pdf = Pdf::loadView('kasir.pdf', [
            'transaction' => $transaction,
            'nowTime' => $now
        ]);
        return $pdf->download('Struk-'.$transaction->invoice_number.'.pdf');
    }
}