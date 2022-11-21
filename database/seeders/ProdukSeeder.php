<?php

namespace Database\Seeders;

use App\Models\Produk;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProdukSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Produk::insert(
            [
                [
                    'id_toko' => 1,
                    'id_user' => 1,
                    'id_kategori' => 1,
                    'id_merk' => 1,
                    'nama_produk' => 'Semen Karungan',
                    'deskripsi' => 'Semen yang asli yang ada badaknya!',
                    'gambar_produk' => 'semen1.jpg',
                    'harga_produk' => '60000',
                    'stok_raw' => 12,
                    'satuan_produk' => 'karung',
                    'prefix' => Str::random(5) . '-semen-karungan'
                ],
                [
                    'id_toko' => 1,
                    'id_user' => 1,
                    'id_kategori' => 1,
                    'id_merk' => 2,
                    'nama_produk' => 'Semen Karungan',
                    'deskripsi' => 'Semen ga yang asli yang ada badaknya!',
                    'gambar_produk' => 'semen2.jpg',
                    'harga_produk' => '65000',
                    'stok_raw' => 10,
                    'satuan_produk' => 'karung',
                    'prefix' => Str::random(5) . '-semen-karungan'
                ],
                [
                    'id_toko' => 1,
                    'id_user' => 1,
                    'id_kategori' => 1,
                    'id_merk' => 3,
                    'nama_produk' => 'Semen Sak',
                    'deskripsi' => 'Semen sak yang asli yang ada badaknya!',
                    'gambar_produk' => 'semen3.jpg',
                    'harga_produk' => '69000',
                    'stok_raw' => 9,
                    'satuan_produk' => 'karung',
                    'prefix' => Str::random(5) . '-semen-sak'
                ],
            ],
        );
    }
}
