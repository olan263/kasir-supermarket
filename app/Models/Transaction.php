<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_number', 
        'user_id',      // Tambahkan ini jika ingin mencatat kasir yang login
        'total_price', 
        'cash_amount', 
        'change_amount'
    ];

    /**
     * Relasi ke TransactionDetail (Satu transaksi punya banyak barang detail)
     */
    public function details()
    {
        return $this->hasMany(TransactionDetail::class, 'transaction_id');
    }

    /**
     * Relasi ke User (Transaksi ini dibuat oleh kasir siapa)
     * Opsional: tambahkan ini jika di tabel transactions ada user_id
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}