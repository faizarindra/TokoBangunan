<?php

namespace Database\Seeders;

use App\Models\Toko;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class TokoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Toko::create(
            [
                'id_user' => 1,
                'nama_toko' => 'Berkah Jaya',
                'gambar_toko' => 'berkah.jpg',
                'alamat_toko' => 'Jl. MT. Haryono, Dinoyo',
                'latitude' => '-7.9442633',
                'longitude' => '112.5965558',
                'prefix' => Str::random(10),
                'id_provinsi' => 0,
                'nama_provinsi' => 'Jawa Timur',
                'id_kota' => 0,
                'nama_kota' => 'Malang',
                'id_kecamatan' => 0,
                'nama_kecamatan' => 'Lowokwaru'
            ],
        );
    }
}
