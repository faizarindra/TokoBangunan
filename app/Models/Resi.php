<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Resi extends Model
{
    use HasFactory;
    protected $table = 'resi';
    protected $fillable = [
        'resi',
        'id_transaksi',
        'deskripsi',
        'icon',
    ];
}
