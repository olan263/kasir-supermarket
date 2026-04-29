<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Product;
use App\Models\TransactionDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Total Penjualan Hari Ini
        $today = now()->startOfDay();
        $totalHariIni = Transaction::whereDate('created_at', $today)
            ->sum('total_price');

        // Total Penjualan Bulan Ini
        $totalBulanIni = Transaction::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('total_price');

        // Total Penjualan Keseluruhan
        $totalKeseluruhan = Transaction::sum('total_price');

        // Jumlah Transaksi Hari Ini
        $transaksiHariIni = Transaction::whereDate('created_at', $today)->count();

        // Jumlah Produk
        $totalProduk = Product::count();

        // Stok Barang Rendah (< 10)
        $stokRendah = Product::where('stock', '<', 10)->count();

        // Data Grafik - Penjualan 7 Hari Terakhir
        $grafikPenjualan = $this->getGrafikPenjualan7Hari();

        // Top 5 Produk Terjual
        $topProduk = $this->getTopProduk(5);

        // Stok Barang Rendah Detail
        $produkStokRendah = Product::where('stock', '<', 10)
            ->orderBy('stock', 'asc')
            ->take(10)
            ->get();

        // Transaksi Terakhir
        $transaksiTerakhir = Transaction::latest()
            ->take(5)
            ->get();

        return view('dashboard', compact(
            'totalHariIni',
            'totalBulanIni',
            'totalKeseluruhan',
            'transaksiHariIni',
            'totalProduk',
            'stokRendah',
            'grafikPenjualan',
            'topProduk',
            'produkStokRendah',
            'transaksiTerakhir'
        ));
    }

    /**
     * Ambil data grafik penjualan 7 hari terakhir
     */
    private function getGrafikPenjualan7Hari()
    {
        $data = [];
        $labels = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $labels[] = $date->format('D, d M');

            $total = Transaction::whereDate('created_at', $date)
                ->sum('total_price');

            $data[] = (float)$total;
        }

        return [
            'labels' => $labels,
            'data' => $data
        ];
    }

    /**
     * Ambil top produk berdasarkan quantity terjual
     */
    private function getTopProduk($limit = 5)
    {
        return TransactionDetail::select('product_name', DB::raw('SUM(quantity) as total_qty'), DB::raw('SUM(subtotal) as total_sales'))
            ->groupBy('product_name')
            ->orderBy('total_qty', 'desc')
            ->take($limit)
            ->get();
    }
}
