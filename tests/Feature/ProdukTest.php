<?php

namespace Tests\Feature;

use App\Models\Kategori;
use App\Models\Merk;
use App\Models\Produk;
use App\Models\Toko;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Nette\Utils\Random;
use Tests\TestCase;

class ProdukTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */

    use RefreshDatabase;

    public function test_user_dapat_melihat_halaman_detail_produk()
    {
        User::create([
            'email' => 'test@gmail.com',
            'name' => 'test',
            'password' => '12345678',
        ]);
        $kategori = Kategori::create([
            'nama_kategori' => 'Semen',
            'prefix' => 'semen',
            'image' => 'semen.jpg',
        ]);
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
        $merk = Merk::create([
            'id_kategori' => $kategori->id,
            'nama_merk' => 'Holcim',
            'prefix' => 'holcim',
            'image' => 'holcim.jpg'
        ]);
        $produk = Produk::create([
            'id_toko' => 1,
            'id_user' => 1,
            'id_kategori' => $kategori->id,
            'id_merk' => $merk->id,
            'nama_produk' => 'Semen Karungan',
            'deskripsi' => 'Deskripsi',
            'gambar_produk' => 'holcim.jpg',
            'harga_produk' => 59000,
            'stok_raw' => 10,
            'satuan_produk' => 'Kg',
            'prefix' => Random::generate(5) . '-semen-holcim'
        ]);
        $response = $this->get('merk/holcim');
        $response->assertStatus(200);
        $response->assertSee('Semen Karungan');
        $response->assertSee(url('produk/' . $produk->prefix));
        $response = $this->get('produk/' . $produk->prefix);
        $response->assertSee('Semen Karungan');
    }
}
