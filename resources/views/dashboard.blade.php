<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-bold text-3xl text-gray-800">📊 Dashboard</h2>
                <p class="text-gray-500 text-sm mt-1">Selamat datang, {{ Auth::user()->name }}</p>
            </div>
            <div class="text-gray-500 text-sm">
                {{ now()->format('l, d F Y') }}
            </div>
        </div>
    </x-slot>

    <div class="py-10 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <!-- Statistik Utama -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <!-- Total Penjualan Hari Ini -->
                <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl p-6 text-white shadow-lg">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-blue-100 text-sm font-semibold uppercase tracking-wide">Penjualan Hari Ini</p>
                            <h3 class="text-4xl font-black mt-2">Rp {{ number_format($totalHariIni, 0, ',', '.') }}</h3>
                        </div>
                        <div class="bg-white/20 p-3 rounded-xl">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                    </div>
                    <p class="text-blue-100 text-xs mt-4">{{ $transaksiHariIni }} transaksi</p>
                </div>

                <!-- Total Penjualan Bulan Ini -->
                <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-2xl p-6 text-white shadow-lg">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-purple-100 text-sm font-semibold uppercase tracking-wide">Penjualan Bulan Ini</p>
                            <h3 class="text-4xl font-black mt-2">Rp {{ number_format($totalBulanIni, 0, ',', '.') }}</h3>
                        </div>
                        <div class="bg-white/20 p-3 rounded-xl">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                        </div>
                    </div>
                </div>

                <!-- Total Produk -->
                <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-2xl p-6 text-white shadow-lg">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-green-100 text-sm font-semibold uppercase tracking-wide">Total Produk</p>
                            <h3 class="text-4xl font-black mt-2">{{ $totalProduk }}</h3>
                        </div>
                        <div class="bg-white/20 p-3 rounded-xl">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m0 0l8 4m-8-4v10l8 4m0-10l8 4m-8-4v10l8-4M7 11l-4 2m0 0l4 2"></path></svg>
                        </div>
                    </div>
                </div>

                <!-- Stok Rendah Warning -->
                <div class="bg-gradient-to-br from-red-500 to-red-600 rounded-2xl p-6 text-white shadow-lg">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-red-100 text-sm font-semibold uppercase tracking-wide">Stok Rendah</p>
                            <h3 class="text-4xl font-black mt-2">{{ $stokRendah }}</h3>
                        </div>
                        <div class="bg-white/20 p-3 rounded-xl">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4v.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                    </div>
                    <p class="text-red-100 text-xs mt-4">⚠️ Perlu restock</p>
                </div>
            </div>

            <!-- Grafik & Daftar -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
                <!-- Grafik Penjualan 7 Hari -->
                <div class="lg:col-span-2 bg-white rounded-2xl shadow-lg p-8">
                    <h3 class="text-xl font-bold text-gray-800 mb-6">📈 Penjualan 7 Hari Terakhir</h3>
                    <div class="relative" style="height: 300px;">
                        <canvas id="chartPenjualan"></canvas>
                    </div>
                </div>

                <!-- Top Produk -->
                <div class="bg-white rounded-2xl shadow-lg p-8">
                    <h3 class="text-xl font-bold text-gray-800 mb-6">🏆 Top 5 Produk</h3>
                    <div class="space-y-4">
                        @forelse($topProduk as $index => $produk)
                        <div class="flex items-center gap-4 p-3 bg-gray-50 rounded-xl hover:bg-gray-100 transition">
                            <div class="bg-gradient-to-br from-indigo-400 to-indigo-600 text-white rounded-full w-8 h-8 flex items-center justify-center font-bold text-sm">
                                {{ $index + 1 }}
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="font-semibold text-gray-800 truncate text-sm">{{ $produk->product_name }}</p>
                                <p class="text-gray-500 text-xs">{{ $produk->total_qty }} pcs terjual</p>
                            </div>
                            <div class="text-right">
                                <p class="font-bold text-indigo-600 text-sm">Rp {{ number_format($produk->total_sales, 0, ',', '.') }}</p>
                            </div>
                        </div>
                        @empty
                        <p class="text-gray-400 text-center py-8">Belum ada penjualan</p>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Stok Rendah & Transaksi Terakhir -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Produk Stok Rendah -->
                <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                    <div class="bg-gradient-to-r from-orange-500 to-red-500 p-6 text-white">
                        <h3 class="text-xl font-bold">⚠️ Produk Stok Rendah</h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead class="bg-gray-50 border-b border-gray-200">
                                <tr class="text-gray-600 text-xs font-bold uppercase tracking-wider">
                                    <th class="px-6 py-4 text-left">Produk</th>
                                    <th class="px-6 py-4 text-center">Stok</th>
                                    <th class="px-6 py-4 text-center">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @forelse($produkStokRendah as $produk)
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-6 py-4 font-semibold text-gray-800">{{ $produk->name }}</td>
                                    <td class="px-6 py-4 text-center">
                                        <span class="bg-orange-100 text-orange-800 px-3 py-1 rounded-full text-sm font-bold">
                                            {{ $produk->stock }} pcs
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        @if($produk->stock < 5)
                                            <span class="text-xs font-bold text-red-600 bg-red-100 px-3 py-1 rounded-full">🔴 KRITIS</span>
                                        @else
                                            <span class="text-xs font-bold text-orange-600 bg-orange-100 px-3 py-1 rounded-full">🟠 RENDAH</span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="px-6 py-12 text-center text-gray-400">✅ Semua stok aman</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Transaksi Terakhir -->
                <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                    <div class="bg-gradient-to-r from-blue-500 to-blue-600 p-6 text-white">
                        <h3 class="text-xl font-bold">📋 Transaksi Terakhir</h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead class="bg-gray-50 border-b border-gray-200">
                                <tr class="text-gray-600 text-xs font-bold uppercase tracking-wider">
                                    <th class="px-6 py-4 text-left">Invoice</th>
                                    <th class="px-6 py-4 text-right">Total</th>
                                    <th class="px-6 py-4 text-center">Waktu</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @forelse($transaksiTerakhir as $transaksi)
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-6 py-4 font-mono font-bold text-indigo-600">#{{ $transaksi->invoice_number }}</td>
                                    <td class="px-6 py-4 text-right font-bold text-gray-800">Rp {{ number_format($transaksi->total_price, 0, ',', '.') }}</td>
                                    <td class="px-6 py-4 text-center text-gray-500 text-xs">{{ $transaksi->created_at->diffForHumans() }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="px-6 py-12 text-center text-gray-400">Belum ada transaksi</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Chart.js Library -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.js"></script>
    <script>
        // Grafik Penjualan 7 Hari
        const chartCanvas = document.getElementById('chartPenjualan');
        const chartData = @json($grafikPenjualan);
        
        new Chart(chartCanvas, {
            type: 'line',
            data: {
                labels: chartData.labels,
                datasets: [{
                    label: 'Penjualan (Rp)',
                    data: chartData.data,
                    borderColor: '#5D5FEF',
                    backgroundColor: 'rgba(93, 95, 239, 0.1)',
                    borderWidth: 3,
                    tension: 0.4,
                    fill: true,
                    pointRadius: 6,
                    pointBackgroundColor: '#5D5FEF',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointHoverRadius: 8,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        labels: { font: { size: 12, weight: 'bold' } }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return 'Rp ' + value.toLocaleString('id-ID');
                            }
                        }
                    }
                }
            }
        });
    </script>
</x-app-layout>
