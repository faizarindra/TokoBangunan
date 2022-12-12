<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransaksiItem extends Model
{
    use HasFactory;
    protected $table = 'transaksi_item';
    protected $fillable = [
        'id_transaksi',
        'id_produk',
        'qty',
        'harga',
        'total'
    ];
}
