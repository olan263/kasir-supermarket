<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-gray-800">➕ Tambah Barang Baru</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-xl rounded-2xl p-8 border border-gray-100">
                <form action="{{ route('products.store') }}" method="POST">
                    @csrf
                    <div class="grid grid-cols-1 gap-6">
                        <div>
                            <label class="block text-sm font-bold text-gray-700">Kode Barcode</label>
                            <input type="text" name="barcode" class="mt-1 w-full rounded-xl border-gray-200 focus:ring-indigo-500" required>
                            @error('barcode') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-700">Nama Barang</label>
                            <input type="text" name="name" class="mt-1 w-full rounded-xl border-gray-200 focus:ring-indigo-500" required>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-700">Kategori</label>
                            <select name="category_id" class="mt-1 w-full rounded-xl border-gray-200 focus:ring-indigo-500">
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-bold text-gray-700">Harga Beli</label>
                                <input type="number" name="purchase_price" class="mt-1 w-full rounded-xl border-gray-200 focus:ring-indigo-500" required>
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-gray-700">Harga Jual</label>
                                <input type="number" name="selling_price" class="mt-1 w-full rounded-xl border-gray-200 focus:ring-indigo-500" required>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-700">Stok Awal</label>
                            <input type="number" name="stock" class="mt-1 w-full rounded-xl border-gray-200 focus:ring-indigo-500" required>
                        </div>

                        <div class="flex justify-end gap-3 mt-4">
                            <a href="{{ route('products.index') }}" class="px-6 py-2 bg-gray-100 text-gray-600 rounded-xl font-bold">Batal</a>
                            <button type="submit" class="px-6 py-2 bg-indigo-600 text-white rounded-xl font-bold hover:bg-indigo-700 shadow-lg transition">
                                Simpan Barang
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>