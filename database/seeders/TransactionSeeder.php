<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use App\Models\Product;
use Carbon\Carbon;

class TransactionSeeder extends Seeder
{
    public function run()
    {
        // Ambil produk yang tersedia (pastikan seeder Product sudah dijalankan dulu)
        $products = Product::all();

        if ($products->isEmpty()) {
            $this->command->error("❌ Gagal: Tidak ada produk di database. Jalankan ProductSeeder dulu!");
            return;
        }

        // Buat 15 transaksi sample
        for ($i = 0; $i < 15; $i++) {
            // Membuat waktu random dalam 7 hari terakhir agar dashboard terlihat variatif
            $date = Carbon::now()->subDays(rand(0, 7))->subHours(rand(0, 23))->subMinutes(rand(0, 59));
            
            $totalPrice = 0;
            $items = [];

            // Pilih random 2-4 produk per transaksi
            $numItems = rand(2, 4);
            $randomProducts = $products->random(min($numItems, $products->count()));
            
            foreach ($randomProducts as $product) {
                $qty = rand(1, 5);
                $subtotal = $product->selling_price * $qty;
                $totalPrice += $subtotal;

                $items[] = [
                    'product_id'   => $product->id,
                    'product_name' => $product->name,
                    'quantity'     => $qty,
                    'price'        => $product->selling_price,
                    'subtotal'     => $subtotal,
                ];
            }

            $cashAmount = $totalPrice + (rand(0, 5) * 5000); // Simulasi uang bayar (kelipatan 5rb)
            $changeAmount = $cashAmount - $totalPrice;

            // 1. Simpan Header Transaksi
            $transaction = Transaction::create([
                'invoice_number' => 'INV-' . strtoupper(bin2hex(random_bytes(4))),
                'total_price'    => $totalPrice,
                'cash_amount'    => $cashAmount,
                'change_amount'  => $changeAmount,
                'created_at'     => $date,
                'updated_at'     => $date,
            ]);

            // 2. Simpan Detail Transaksi & Kurangi Stok
            foreach ($items as $item) {
                TransactionDetail::create([
                    'transaction_id' => $transaction->id,
                    'product_id'     => $item['product_id'],
                    'product_name'   => $item['product_name'],
                    'quantity'       => $item['quantity'],
                    'price'          => $item['price'],
                    'subtotal'       => $item['subtotal'],
                    'created_at'     => $date,
                    'updated_at'     => $date,
                ]);

                // Kurangi stok produk secara real
                Product::where('id', $item['product_id'])->decrement('stock', $item['quantity']);
            }
        }

        $this->command->info("✅ 15 Sample transaksi berhasil dibuat!");
    }
}