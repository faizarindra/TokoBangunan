<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    use HasFactory;
    protected $table = 'transaksi';
    protected $fillable = [
        'kode',
        'id_user',
        'id_toko',
        'id_alamat',
        'id_pembayaran',
        'ongkir',
        'total_harga',
        'total',
        'resi',
        'snap_token',
        'status'
    ];
}
