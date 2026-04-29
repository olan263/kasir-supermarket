<x-app-layout>
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-6 shadow sm:rounded-lg">
                <h2 class="text-xl font-bold mb-4">Input Barang Baru</h2>

                <form action="{{ route('products.store') }}" method="POST">
                    @csrf
                    <div class="grid grid-cols-1 gap-4">
                        <div>
                            <label>Kode Barcode (Manual/Scan)</label>
                            <input type="text" name="barcode" class="w-full rounded border-gray-300" required>
                        </div>
                        <div>
                            <label>Nama Barang</label>
                            <input type="text" name="name" class="w-full rounded border-gray-300" required>
                        </div>
                        <div>
                            <label>Kategori</label>
                            <select name="category_id" class="w-full rounded border-gray-300">
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label>Harga Modal</label>
                                <input type="number" name="purchase_price" class="w-full rounded border-gray-300" required>
                            </div>
                            <div>
                                <label>Harga Jual</label>
                                <input type="number" name="selling_price" class="w-full rounded border-gray-300" required>
                            </div>
                        </div>
                        <div>
                            <label>Stok Awal</label>
                            <input type="number" name="stock" class="w-full rounded border-gray-300" required>
                        </div>
                        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded mt-4">Simpan Barang</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>