<?php

namespace Database\Seeders;

use App\Models\Kategori;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class KategoriSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Kategori::insert([
            [
                'nama_kategori' => 'Semen',
                'prefix' => 'semen'
            ],
            [
                'nama_kategori' => 'Cat Tembok',
                'prefix' => 'cat-tembok'
            ],
            [
                'nama_kategori' => 'Peralatan Tukang',
                'prefix' => 'peralatan-tukang'
            ]
        ]);
    }
}
