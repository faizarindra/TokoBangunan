<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TerakhirDilihat extends Model
{
    use HasFactory;
    protected $table = 'terakhir_dilihat';
    protected $fillable = [
        'id_user',
        'id_produk'
    ];
}
