<?php

namespace Database\Seeders;

use App\Models\Alamat;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AlamatSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        Alamat::create([
            'id_user' => 1,
            'nama_penerima' => 'Rizky',
            'no_hp' => '081234567890',
            'alamat' => 'Jl. Raya Cibaduyut No. 1',
            'latitude' => '-6.890123',
            'longitude' => '107.612345',
            'id_provinsi' => 1,
            'nama_provinsi' => 'Jawa Barat',
            'id_kota' => 1,
            'nama_kota' => 'Bandung',
            'id_kecamatan' => 1,
            'nama_kecamatan' => 'Cibaduyut',
            'kode_pos' => '40123',
        ]);
    }
}
