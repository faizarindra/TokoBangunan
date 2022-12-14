<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Saldo extends Model
{
    use HasFactory;
    protected $table = 'saldo';
    protected $fillable = [
        'id_toko',
        'id_transaksi',
        'nominal',
        'tanggal_selesai',
        'status',
        'is_cair',
    ];
    protected $dates = [
        'created_at',
        'updated_at',
        'tanggal_selesai',
    ];
}
