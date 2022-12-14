<?php

namespace Tests\Feature;

use App\Models\Produk;
use App\Models\Toko;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Nette\Utils\Random;
use Tests\TestCase;

class SearchTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */

    use RefreshDatabase;

    public function test_user_dapat_mencari_produk()
    {
        Produk::create([
            'id_toko' => 1,
            'id_user' => 1,
            'id_kategori' => 1,
            'id_merk' => 1,
            'nama_produk' => 'Semen Karungan',
            'deskripsi' => 'Deskripsi',
            'gambar_produk' => 'holcim.jpg',
            'harga_produk' => 59000,
            'stok_raw' => 10,
            'satuan_produk' => 'Kg',
            'prefix' => Random::generate(5) . '-semen-holcim'
        ]);
        $response = $this->post('api/cari', [
            'search' => 'Karungan'
        ]);
        $response->assertStatus(200);
        $response->assertSee('Semen Karungan');
    }

    public function test_user_dapat_mencari_toko()
    {
        Toko::create([
            'id_user' => 1,
            'nama_toko' => 'Berkah Jaya',
            'deskripsi_toko' => 'Menjual semen',
            'gambar_toko' => 'berkah.jpg',
            'alamat_toko' => 'Jl Ngasem',
            'latitude' => 0,
            'longitude' => 0,
            'prefix' => Random::generate(5),
            'id_provinsi' => 1,
            'nama_provinsi' => 'Jawa Timur',
            'id_kota' => '1',
            'nama_kota' => 'Malang',
            'id_kecamatan' => 1,
            'nama_kecamatan' => 'Lowokwaru'
        ]);
        $response = $this->post('api/cari', [
            'search' => 'Berkah'
        ]);
        $response->assertStatus(200);
        $response->assertSee('Berkah Jaya');
    }
}
