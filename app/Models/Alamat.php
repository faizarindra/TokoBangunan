<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Alamat extends Model
{
    use HasFactory;
    protected $table = 'alamat';
    protected $fillable = [
        'id_user',
        'nama_penerima',
        'no_hp',
        'alamat',
        'latitude',
        'longitude',
        'id_provinsi',
        'nama_provinsi',
        'id_kota',
        'nama_kota',
        'id_kecamatan',
        'nama_kecamatan',
        'kode_pos',
    ];
}
