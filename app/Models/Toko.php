<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Toko extends Model
{
    use HasFactory;
    protected $table = 'toko';
    protected $fillable = [
        'id_user',
        'nama_toko',
        'deskripsi_toko',
        'gambar_toko',
        'alamat_toko',
        'latitude',
        'longitude',
        'prefix',
        'id_provinsi',
        'nama_provinsi',
        'id_kota',
        'nama_kota',
        'id_kecamatan',
        'nama_kecamatan'
    ];
}
