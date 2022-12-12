<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notifikasi extends Model
{
    use HasFactory;
    protected $table = 'notifikasi';
    protected $fillable = [
        'type',
        'id_user',
        'id_toko',
        'pesan',
        'icon',
        'bg_color',
        'text_color',
    ];
}
