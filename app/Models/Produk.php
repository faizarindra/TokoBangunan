<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    use HasFactory;
    protected $table = 'produk';
    protected $fillable = [
        'id_toko',
        'id_user',
        'id_kategori',
        'id_merk',
        'nama_produk',
        'deskripsi',
        'gambar_produk',
        'harga_produk',
        'stok_raw',
        'satuan_produk',
        'prefix'
    ];
}
