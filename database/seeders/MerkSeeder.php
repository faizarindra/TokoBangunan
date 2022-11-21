<?php

namespace Database\Seeders;

use App\Models\Merk;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MerkSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Merk::insert([
            [
                'id_kategori' => 1,
                'nama_merk' => 'Tiga Roda',
                'prefix' => 'tiga-roda',
                'image' => 'tiga-roda.png'
            ],
            [
                'id_kategori' => 1,
                'nama_merk' => 'Semen Gresik',
                'prefix' => 'semen-gresik',
                'image' => 'gresik.png'
            ],
            [
                'id_kategori' => 1,
                'nama_merk' => 'Holcim',
                'prefix' => 'holcim',
                'image' => 'holcim.png'
            ],
            [
                'id_kategori' => 1,
                'nama_merk' => 'SCG',
                'prefix' => 'scg',
                'image' => 'scg.png'
            ]
        ]);
    }
}
