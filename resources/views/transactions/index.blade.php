<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-bold text-2xl text-gray-800">📦 Manajemen Stok Barang</h2>
            <a href="{{ route('products.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2 rounded-lg shadow-md transition">
                + Tambah Barang
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-xl rounded-2xl p-6">
                
                <form action="{{ route('products.index') }}" method="GET" class="mb-6 flex gap-2">
                    <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Cari nama barang atau barcode..." 
                           class="w-full border-gray-200 rounded-xl focus:ring-indigo-500 focus:border-indigo-500">
                    <button type="submit" class="bg-gray-100 px-6 py-2 rounded-xl font-semibold hover:bg-gray-200">Cari</button>
                </form>

                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="bg-gray-50 text-gray-600 uppercase text-sm leading-normal">
                                <th class="py-4 px-6 font-bold">Produk</th>
                                <th class="py-4 px-6 font-bold text-center">Kategori</th>
                                <th class="py-4 px-6 font-bold text-right">Harga Jual</th>
                                <th class="py-4 px-6 font-bold text-center">Stok</th>
                                <th class="py-4 px-6 font-bold text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-700 text-sm">
                            @foreach($products as $product)
                            <tr class="border-b border-gray-100 hover:bg-gray-50 transition">
                                <td class="py-4 px-6">
                                    <div class="font-bold text-gray-900">{{ $product->name }}</div>
                                    <div class="text-xs text-gray-400">{{ $product->barcode }}</div>
                                </td>
                                <td class="py-4 px-6 text-center">
                                    <span class="bg-gray-100 text-gray-600 px-3 py-1 rounded-full text-xs">
                                        {{ $product->category->name ?? 'Umum' }}
                                    </span>
                                </td>
                                <td class="py-4 px-6 text-right font-semibold">
                                    Rp {{ number_format($product->selling_price, 0, ',', '.') }}
                                </td>
                                <td class="py-4 px-6 text-center">
                                    @if($product->stock <= 0)
                                        <span class="bg-red-100 text-red-600 px-3 py-1 rounded-full text-xs font-bold">Habis</span>
                                    @elseif($product->stock < 10)
                                        <span class="bg-orange-100 text-orange-600 px-3 py-1 rounded-full text-xs font-bold">Sisa {{ $product->stock }}</span>
                                    @else
                                        <span class="bg-green-100 text-green-600 px-3 py-1 rounded-full text-xs font-bold">{{ $product->stock }}</span>
                                    @endif
                                </td>
                                <td class="py-4 px-6 text-center flex justify-center gap-3">
                                    <a href="{{ route('products.edit', $product->id) }}" class="text-indigo-600 hover:text-indigo-900">Edit</a>
                                    <form action="{{ route('products.destroy', $product->id) }}" method="POST">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-red-500 hover:text-red-700" onclick="return confirm('Hapus barang ini?')">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-6">
                    {{ $products->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>