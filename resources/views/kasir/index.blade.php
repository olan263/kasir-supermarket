<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between w-full">
            <div class="flex items-center gap-3">
                <span class="text-3xl">🛒</span>
                <h2 class="font-black text-2xl text-gray-800 tracking-tight">MENU KASIR</h2>
            </div>
            <div class="text-right">
                <div id="real-time-clock" class="font-black text-indigo-600 text-xl tracking-tighter"></div>
                <div id="real-time-date" class="text-[10px] font-bold text-gray-400 uppercase tracking-widest"></div>
            </div>
        </div>
    </x-slot>

    <div class="py-10 bg-[#F8FAFC] min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
                
                <div class="lg:col-span-8 space-y-6">
                    <div class="bg-white p-8 rounded-[2rem] shadow-sm border border-gray-100">
                        <label class="block text-[11px] font-black text-indigo-500 uppercase tracking-[0.2em] mb-4">Cari Produk / Scan Barcode</label>
                        <div class="relative">
                            <select id="product-select" class="w-full pl-6 pr-12 py-5 bg-gray-50 border-2 border-gray-50 rounded-2xl focus:ring-4 focus:ring-indigo-500/5 focus:border-indigo-500 transition-all appearance-none text-gray-600 font-semibold">
                                <option value="">-- Masukkan Nama Barang atau Scan Barcode --</option>
                                @foreach($products as $product)
                                    <option value="{{ $product->id }}">
                                        {{ $product->barcode }} - {{ $product->name }} (Stok: {{ $product->stock }})
                                    </option>
                                @endforeach
                            </select>
                            <div class="absolute right-5 top-1/2 -translate-y-1/2 pointer-events-none text-gray-400">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-[2rem] shadow-sm border border-gray-100 overflow-hidden">
                        <div class="p-8 border-b border-gray-50 flex justify-between items-center">
                            <h3 class="font-bold text-gray-800">Daftar Item</h3>
                            <span class="bg-gray-100 px-4 py-1 rounded-full text-[10px] font-black text-gray-500 uppercase tracking-widest" id="item-count">0 Items</span>
                        </div>
                        
                        <div class="overflow-x-auto">
                            <table class="w-full text-left" id="cart-table">
                                <thead class="bg-gray-50/50">
                                    <tr class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">
                                        <th class="py-5 px-10">Produk</th>
                                        <th class="py-5 text-center">Qty</th>
                                        <th class="py-5 text-right">Subtotal</th>
                                        <th class="py-5 px-10 text-center">Opsi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-50"></tbody>
                            </table>
                        </div>

                        <div id="empty-state" class="py-24 text-center hidden">
                            <div class="inline-flex items-center justify-center w-20 h-20 bg-gray-50 rounded-full mb-4">
                                <svg class="w-10 h-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                            </div>
                            <p class="text-gray-400 font-semibold">Belum ada barang dipilih.</p>
                        </div>
                    </div>
                </div>

                <div class="lg:col-span-4">
                    <div class="bg-[#5D5FEF] rounded-[2.5rem] p-10 text-white shadow-2xl shadow-indigo-200 sticky top-8">
                        <div class="mb-10">
                            <p class="text-indigo-100/70 text-[11px] font-black uppercase tracking-[0.2em] mb-2">Total Belanja</p>
                            <div class="flex items-baseline gap-2">
                                <span class="text-2xl font-bold text-indigo-200">Rp</span>
                                <span id="grand-total" class="text-6xl font-black tracking-tighter">0</span>
                            </div>
                        </div>

                        <form action="{{ route('kasir.store') }}" method="POST" id="checkout-form">
                            @csrf
                            <input type="hidden" name="total_price" id="total_price_hidden">
                            <div class="space-y-8">
                                <div class="bg-white/10 p-6 rounded-[2rem] backdrop-blur-md border border-white/10">
                                    <label class="text-[10px] font-black text-indigo-100 uppercase tracking-widest block mb-4">Nominal Bayar</label>
                                    <div class="relative">
                                        <span class="absolute left-0 top-1/2 -translate-y-1/2 text-2xl font-black text-white/50">Rp</span>
                                        <input type="number" name="cash_amount" id="cash-amount" 
                                               class="w-full bg-transparent border-none focus:ring-0 text-white text-4xl font-black p-0 pl-12 placeholder-white/20" 
                                               placeholder="0" required>
                                    </div>
                                </div>

                                <div class="px-2">
                                    <div class="flex justify-between text-indigo-100/60 text-[11px] font-black uppercase tracking-widest mb-2">
                                        <span>Kembalian</span>
                                        <span id="change-label-status" class="hidden text-red-300 font-black">UANG KURANG!</span>
                                    </div>
                                    <div class="text-4xl font-black text-white" id="change-amount">Rp 0</div>
                                </div>

                                <button type="button" id="btn-proses" onclick="openConfirmModal()" 
                                        class="w-full bg-white text-[#5D5FEF] py-6 rounded-2xl font-black text-lg hover:bg-indigo-50 active:scale-[0.98] transition-all shadow-xl uppercase tracking-widest">
                                    Proses Transaksi
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="mt-16 bg-white rounded-[2.5rem] shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-8 border-b border-gray-50 flex justify-between items-center bg-gray-50/30">
                    <h3 class="font-black text-gray-800 text-lg uppercase tracking-tight">Riwayat Terakhir</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead class="bg-gray-50">
                            <tr class="text-[10px] font-black text-gray-400 uppercase tracking-widest">
                                <th class="px-10 py-5">Invoice</th>
                                <th class="px-10 py-5 text-right">Total</th>
                                <th class="px-10 py-5 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($history ?? [] as $item)
                            <tr class="hover:bg-gray-50/50 transition">
                                <td class="px-10 py-5">
                                    <div class="font-black text-indigo-600">#{{ $item->invoice_number }}</div>
                                    <div class="text-[11px] text-gray-400 font-bold mt-1">{{ $item->created_at->format('d M, H:i') }}</div>
                                </td>
                                <td class="px-10 py-5 text-right font-black text-gray-800">Rp {{ number_format($item->total_price, 0, ',', '.') }}</td>
                                <td class="px-10 py-5 text-center">
                                    <a href="{{ route('kasir.pdf', $item->id) }}" class="inline-flex items-center gap-2 px-6 py-2.5 bg-red-50 text-red-600 rounded-xl text-[10px] font-black hover:bg-red-100 transition-all uppercase">
                                        Cetak PDF
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="px-10 py-12 text-center text-gray-400 font-semibold italic">Belum ada transaksi.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div id="confirm-modal" class="fixed inset-0 z-50 hidden">
        <div class="absolute inset-0 bg-gray-900/60 backdrop-blur-sm" onclick="closeConfirmModal()"></div>
        <div class="flex items-center justify-center min-h-screen px-4">
            <div class="bg-white w-full max-w-sm rounded-[3rem] p-12 text-center shadow-2xl relative z-10">
                <div class="w-20 h-20 bg-green-50 rounded-full flex items-center justify-center mx-auto mb-8 text-4xl">✅</div>
                <h3 class="text-2xl font-black text-gray-900 mb-3">Simpan Transaksi?</h3>
                <p class="text-gray-400 text-sm font-bold mb-10 leading-relaxed">Pastikan pembayaran sudah diterima dengan benar.</p>
                <div class="space-y-4">
                    <button type="button" onclick="submitFinal()" class="w-full py-5 bg-[#5D5FEF] text-white rounded-2xl font-black hover:bg-indigo-700 transition shadow-lg shadow-indigo-100 tracking-widest uppercase">Simpan & Cetak</button>
                    <button type="button" onclick="closeConfirmModal()" class="w-full py-3 text-gray-400 font-black hover:text-gray-600 transition uppercase text-[10px] tracking-widest">Batal</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        // --- LOGIKA REAL-TIME CLOCK ---
        function updateLiveTime() {
            const now = new Date();
            const hours = String(now.getHours()).padStart(2, '0');
            const minutes = String(now.getMinutes()).padStart(2, '0');
            const seconds = String(now.getSeconds()).padStart(2, '0');
            const timeString = `${hours}:${minutes}:${seconds}`;

            const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
            const dateString = now.toLocaleDateString('id-ID', options);

            const clockElement = document.getElementById('real-time-clock');
            const dateElement = document.getElementById('real-time-date');
            
            if(clockElement) clockElement.innerText = timeString;
            if(dateElement) dateElement.innerText = dateString;
        }
        setInterval(updateLiveTime, 1000);
        updateLiveTime();

        // --- LOGIKA KASIR ---
        const productSelect = document.getElementById('product-select');
        const cashInput = document.getElementById('cash-amount');
        const cartTableBody = document.querySelector('#cart-table tbody');
        const emptyState = document.getElementById('empty-state');
        const grandTotalDisplay = document.getElementById('grand-total');
        const totalInputHidden = document.getElementById('total_price_hidden');
        const itemCountLabel = document.getElementById('item-count');
        const changeDisplay = document.getElementById('change-amount');
        const changeLabel = document.getElementById('change-label-status');

        let state = {
            cart: @json(session()->get('cart', [])),
            total: {{ array_sum(array_map(fn($item) => $item['subtotal'], session()->get('cart', []))) ?: 0 }}
        };

        const formatter = new Intl.NumberFormat('id-ID');
        const getCsrfToken = () => document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        function updateUI() {
            cartTableBody.innerHTML = '';
            const items = Object.entries(state.cart);
            
            if (items.length > 0) {
                emptyState.classList.add('hidden');
                items.forEach(([id, item]) => {
                    const row = `
                        <tr class="hover:bg-gray-50/50 transition-colors border-b border-gray-50">
                            <td class="py-6 px-10">
                                <div class="font-black text-gray-800 text-sm">${item.name}</div>
                                <div class="text-[10px] font-black text-indigo-400 mt-1 uppercase tracking-widest">
                                    Rp ${formatter.format(item.price)}
                                </div>
                            </td>
                            <td class="py-6 text-center">
                                <span class="bg-gray-100 px-4 py-1.5 rounded-xl text-[11px] font-black text-gray-600">${item.quantity}</span>
                            </td>
                            <td class="py-6 text-right font-black text-gray-900">
                                Rp ${formatter.format(item.subtotal)}
                            </td>
                            <td class="py-6 px-10 text-center">
                                <button type="button" onclick="handleRemove('${id}')" class="text-red-400 hover:text-red-600 transition-all font-black text-[10px] uppercase tracking-tighter">Hapus</button>
                            </td>
                        </tr>`;
                    cartTableBody.insertAdjacentHTML('beforeend', row);
                });
            } else {
                emptyState.classList.remove('hidden');
            }

            grandTotalDisplay.innerText = formatter.format(state.total);
            totalInputHidden.value = state.total;
            itemCountLabel.innerText = `${items.length} Items`;
            calculateChange();
        }

        productSelect.addEventListener('change', async function() {
            const productId = this.value;
            if (!productId) return;
            productSelect.disabled = true;

            try {
                const response = await fetch("{{ route('kasir.add') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": getCsrfToken(),
                        "Accept": "application/json"
                    },
                    body: JSON.stringify({ product_id: productId })
                });

                if (!response.ok) {
                    const errorData = await response.json().catch(() => null);
                    throw new Error(errorData?.message || "Gagal menambah produk");
                }

                const data = await response.json();
                state.cart = data.cart;
                state.total = data.total;
                updateUI();
            } catch (error) {
                alert(error.message);
            } finally {
                productSelect.disabled = false;
                productSelect.value = '';
                productSelect.focus();
            }
        });

        async function handleRemove(id) {
            if(!confirm('Hapus item ini?')) return;
            try {
                const response = await fetch(`/kasir/remove/${id}`, {
                    method: "DELETE",
                    headers: { 
                        "X-CSRF-TOKEN": getCsrfToken(),
                        "Accept": "application/json" 
                    }
                });
                const data = await response.json();
                state.cart = data.cart;
                state.total = data.total;
                updateUI();
            } catch (error) {
                console.error("Gagal hapus:", error);
            }
        }

        function calculateChange() {
            const bayar = parseFloat(cashInput.value) || 0;
            const kembalian = bayar - state.total;
            if (bayar > 0) {
                changeDisplay.innerText = 'Rp ' + formatter.format(Math.abs(kembalian));
                if (kembalian >= 0) {
                    changeDisplay.className = 'text-4xl font-black text-white';
                    changeLabel.classList.add('hidden');
                } else {
                    changeDisplay.className = 'text-4xl font-black text-red-300';
                    changeLabel.classList.remove('hidden');
                }
            } else {
                changeDisplay.innerText = 'Rp 0';
                changeDisplay.className = 'text-4xl font-black text-white';
                changeLabel.classList.add('hidden');
            }
        }

        cashInput.addEventListener('input', calculateChange);
        document.addEventListener('DOMContentLoaded', updateUI);

        function openConfirmModal() {
            const bayar = parseFloat(cashInput.value) || 0;
            const items = Object.keys(state.cart).length;

            if (items === 0) {
                alert('❌ Keranjang kosong!');
                return;
            }
            if (bayar < state.total) {
                alert('❌ Uang pembayaran kurang!');
                return;
            }
            document.getElementById('confirm-modal').classList.remove('hidden');
        }

        function closeConfirmModal() {
            document.getElementById('confirm-modal').classList.add('hidden');
        }

        function submitFinal() {
            const form = document.getElementById('checkout-form');
            const btn = document.getElementById('btn-proses');
            
            document.getElementById('total_price_hidden').value = state.total;
            btn.disabled = true;
            btn.innerText = '⏳ Memproses...';
            
            setTimeout(() => { form.submit(); }, 300);
        }
    </script>
</x-app-layout>