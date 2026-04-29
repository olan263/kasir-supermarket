<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Category;

class ProductDatabaseSeeder extends Seeder
{
    public function run()
    {
        // Pastikan ada kategori 'Umum' atau sesuaikan dengan data SQL
        $category = Category::firstOrCreate(['name' => 'Umum']);

        $data = [
            ["U0343", "UNIK CAR WASH SPONGE FINGER", 6400, 7000, 10],
            ["10001", "123 BENDERA COKLAT 300G", 19218, 19600, 15],
            ["20020", "2.3.4 FILTER", 84870, 86000, 20],
            ["20021", "234 KERETEK", 11140, 12000, 50],
            ["20018", "234 KERETEK 12", 216000, 217000, 5],
            ["20019", "234 KERETEK 16", 125190, 126000, 10],
            ["20022", "26-PROMIL GOLD 400G", 97524, 98500, 8],
            ["20007", "2TANG AIR MINERAL 240ML", 833, 900, 100],
            ["70002", "7UP 330ML", 4000, 4400, 24],
            ["80002", "888 CALIFORNIA NEW ORANGE", 9653, 9800, 12],
            ["A2375", "A LICAFE 100G", 7350, 8000, 30],
            ["A2376", "A LICAFE 300G", 20850, 21500, 20],
            ["A2568", "A&W SARSAPARILA 330ML", 4489, 5000, 48],
            ["A2492", "A1 SHELLA SENBAI RICE 360G", 28000, 30500, 10],
            ["A2435", "ABC ALKALIN 9 VOLT", 18169, 19200, 15],
            ["A2545", "ABC AYAM GRENG 135ML", 4050, 4400, 48],
            ["A2546", "ABC AYAM GRENG 340ML", 8925, 9400, 24],
            ["A2692", "ABC BLACK GOLD 600ML", 16979, 17900, 12],
            ["A2689", "ABC BROWNIES 30S", 25345, 26700, 6],
            ["A2665", "ABC CUP SELERA PEDAS 65G SEMUR", 3254, 3600, 24],
            // ... (Data lainnya dari SQL Anda)
        ];

        foreach ($data as $item) {
            Product::create([
                'barcode' => $item[0],
                'name' => $item[1],
                'category_id' => $category->id,
                'purchase_price' => $item[2],
                'selling_price' => $item[3],
                'stock' => $item[4],
            ]);
        }
    }
}