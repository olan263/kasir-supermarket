<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        
        $products = Product::with('category')
            ->when($search, function($query) use ($search) {
                return $query->where('name', 'like', "%{$search}%")
                             ->orWhere('barcode', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(10);

        return view('products.index', compact('products', 'search'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'barcode' => 'required|unique:products',
            'name' => 'required',
            'category_id' => 'required',
            'purchase_price' => 'required|numeric',
            'selling_price' => 'required|numeric',
            'stock' => 'required|numeric',
        ]);

        Product::create($request->all());

        return redirect()->route('products.index')->with('success', 'Barang berhasil ditambahkan!');
    }

    // PERBAIKAN: Fungsi edit sekarang berada di DALAM class
    public function edit($id)
    {
        $product = Product::findOrFail($id);
        $categories = Category::all(); // Tambahkan ini agar di halaman edit bisa ganti kategori
        
        return view('products.edit', compact('product', 'categories'));
    }

    // PERBAIKAN: Fungsi update sekarang berada di DALAM class
    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        
        $request->validate([
            'name' => 'required',
            'category_id' => 'required',
            'stock' => 'required|numeric',
            'purchase_price' => 'required|numeric',
            'selling_price' => 'required|numeric',
        ]);

        $product->update($request->all());

        return redirect()->route('products.index')->with('success', 'Stok berhasil diupdate!');
    }

    // Tambahkan juga fungsi destroy agar fitur hapus di Resource Route jalan
    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();

        return redirect()->route('products.index')->with('success', 'Barang berhasil dihapus!');
    }
} // Kurung kurawal penutup class harus di paling bawah