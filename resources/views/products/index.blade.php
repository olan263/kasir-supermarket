<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-bold text-2xl text-gray-800">📦 Manajemen Stok Barang</h2>
            <a href="{{ route('products.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2 rounded-lg shadow-md transition-all duration-200">
                + Tambah Barang
            </a>
        </div>
    </x-slot>

    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-xl">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white shadow-xl rounded-2xl overflow-hidden border border-gray-100">
                
                <div class="p-6 border-b border-gray-100">
                    <form action="{{ route('products.index') }}" method="GET" class="flex gap-2">
                        <div class="relative flex-1">
                            <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                                🔍
                            </span>
                            <input type="text" name="search" value="{{ $search ?? '' }}" 
                                   placeholder="Cari nama barang atau barcode..." 
                                   class="w-full pl-10 pr-4 py-2 border-gray-200 rounded-xl focus:ring-indigo-500 focus:border-indigo-500 transition">
                        </div>
                        <button type="submit" class="bg-gray-800 text-white px-6 py-2 rounded-xl font-semibold hover:bg-black transition">
                            Cari
                        </button>
                    </form>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="bg-gray-50 text-gray-500 uppercase text-xs tracking-wider">
                                <th class="py-4 px-6 font-bold">Produk</th>
                                <th class="py-4 px-6 font-bold text-center">Kategori</th>
                                <th class="py-4 px-6 font-bold text-right">Harga Jual</th>
                                <th class="py-4 px-6 font-bold text-center">Stok</th>
                                <th class="py-4 px-6 font-bold text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-700 text-sm divide-y divide-gray-100">
                            @forelse($products as $product)
                            <tr class="hover:bg-indigo-50/30 transition">
                                <td class="py-4 px-6">
                                    <div class="font-bold text-gray-900">{{ $product->name }}</div>
                                    <div class="text-xs text-gray-400 font-mono">{{ $product->barcode }}</div>
                                </td>
                                <td class="py-4 px-6 text-center">
                                    <span class="bg-blue-50 text-blue-600 px-3 py-1 rounded-full text-xs font-medium">
                                        {{ $product->category->name ?? 'Tanpa Kategori' }}
                                    </span>
                                </td>
                                <td class="py-4 px-6 text-right font-bold text-indigo-600">
                                    Rp {{ number_format($product->selling_price, 0, ',', '.') }}
                                </td>
                                <td class="py-4 px-6 text-center">
                                    @if($product->stock <= 0)
                                        <span class="bg-red-100 text-red-600 px-3 py-1 rounded-full text-xs font-bold">Habis</span>
                                    @elseif($product->stock < 10)
                                        <span class="bg-orange-100 text-orange-600 px-3 py-1 rounded-full text-xs font-bold text-nowrap">Sisa {{ $product->stock }}</span>
                                    @else
                                        <span class="bg-green-100 text-green-600 px-3 py-1 rounded-full text-xs font-bold">{{ $product->stock }}</span>
                                    @endif
                                </td>
                                <td class="py-4 px-6 text-center">
                                    <div class="flex justify-center gap-4">
                                        <a href="{{ route('products.edit', $product->id) }}" class="text-indigo-600 hover:text-indigo-900 font-semibold">Edit</a>
                                        <form action="{{ route('products.destroy', $product->id) }}" method="POST" class="inline">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="text-red-400 hover:text-red-600 font-semibold" 
                                                    onclick="return confirm('Hapus barang {{ $product->name }}?')">
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="py-10 text-center text-gray-400 italic">
                                    Barang tidak ditemukan...
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="p-6 bg-gray-50 border-t border-gray-100">
                    {{ $products->appends(['search' => $search])->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>