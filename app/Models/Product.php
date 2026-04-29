<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    // Pastikan semua kolom yang ada di database bisa diisi (Mass Assignment)
    protected $guarded = []; 
    // ATAU gunakan $fillable: 
    // protected $fillable = ['barcode', 'name', 'category_id', 'purchase_price', 'selling_price', 'stock'];

    // Menambahkan Relasi ke tabel Category
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}