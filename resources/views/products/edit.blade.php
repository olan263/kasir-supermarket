<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <span class="text-3xl">📝</span>
            <h2 class="font-black text-2xl text-gray-800 tracking-tight">EDIT BARANG</h2>
        </div>
    </x-slot>

    <div class="py-12 bg-[#F8FAFC] min-h-screen">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-8 border-b border-gray-50 bg-gray-50/30">
                    <h3 class="font-black text-gray-800 text-lg uppercase tracking-tight">Informasi Produk</h3>
                    <p class="text-gray-400 text-xs font-bold mt-1">Ubah detail barang: {{ $product->name }}</p>
                </div>

                <form action="{{ route('products.update', $product->id) }}" method="POST" class="p-10 space-y-8">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="space-y-2">
                            <label class="text-[11px] font-black text-indigo-500 uppercase tracking-widest ml-1">Barcode</label>
                            <input type="text" name="barcode" value="{{ $product->barcode }}" 
                                   class="w-full px-6 py-4 bg-gray-50 border-2 border-gray-50 rounded-2xl font-semibold text-gray-400 cursor-not-allowed" readonly>
                        </div>

                        <div class="space-y-2">
                            <label class="text-[11px] font-black text-indigo-500 uppercase tracking-widest ml-1">Kategori</label>
                            <select name="category_id" class="w-full px-6 py-4 bg-gray-50 border-2 border-gray-50 rounded-2xl focus:ring-4 focus:ring-indigo-500/5 focus:border-indigo-500 transition-all font-semibold text-gray-600">
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ $product->category_id == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="md:col-span-2 space-y-2">
                            <label class="text-[11px] font-black text-indigo-500 uppercase tracking-widest ml-1">Nama Produk</label>
                            <input type="text" name="name" value="{{ $product->name }}" required
                                   class="w-full px-6 py-4 bg-gray-50 border-2 border-gray-50 rounded-2xl focus:ring-4 focus:ring-indigo-500/5 focus:border-indigo-500 transition-all font-semibold text-gray-600">
                        </div>

                        <div class="space-y-2">
                            <label class="text-[11px] font-black text-indigo-500 uppercase tracking-widest ml-1">Harga Beli (Rp)</label>
                            <input type="number" name="purchase_price" value="{{ $product->purchase_price }}" required
                                   class="w-full px-6 py-4 bg-gray-50 border-2 border-gray-50 rounded-2xl focus:ring-4 focus:ring-indigo-500/5 focus:border-indigo-500 transition-all font-semibold text-gray-600">
                        </div>

                        <div class="space-y-2">
                            <label class="text-[11px] font-black text-indigo-500 uppercase tracking-widest ml-1">Harga Jual (Rp)</label>
                            <input type="number" name="selling_price" value="{{ $product->selling_price }}" required
                                   class="w-full px-6 py-4 bg-gray-50 border-2 border-gray-50 rounded-2xl focus:ring-4 focus:ring-indigo-500/5 focus:border-indigo-500 transition-all font-semibold text-gray-600">
                        </div>

                        <div class="space-y-2">
                            <label class="text-[11px] font-black text-indigo-500 uppercase tracking-widest ml-1">Jumlah Stok</label>
                            <input type="number" name="stock" value="{{ $product->stock }}" required
                                   class="w-full px-6 py-4 bg-gray-50 border-2 border-gray-50 rounded-2xl focus:ring-4 focus:ring-indigo-500/5 focus:border-indigo-500 transition-all font-semibold text-gray-600">
                        </div>
                    </div>

                    <div class="pt-6 flex gap-4">
                        <button type="submit" class="flex-1 bg-[#5D5FEF] text-white py-5 rounded-2xl font-black text-sm hover:bg-indigo-700 transition shadow-lg shadow-indigo-100 uppercase tracking-widest">
                            Simpan Perubahan
                        </button>
                        <a href="{{ route('products.index') }}" class="px-8 bg-gray-100 text-gray-500 py-5 rounded-2xl font-black text-sm hover:bg-gray-200 transition uppercase tracking-widest text-center">
                            Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>