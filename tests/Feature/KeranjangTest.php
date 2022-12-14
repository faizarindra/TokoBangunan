<?php

namespace Tests\Feature;

use App\Models\Keranjang;
use App\Models\Produk;
use App\Models\Toko;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;
use Nette\Utils\Random;
use Tests\TestCase;

class KeranjangTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    use RefreshDatabase;

    public function test_user_dapat_memasukkan_produk_ke_keranjang()
    {
        $user = User::create([
            'email' => 'test@gmail.com',
            'name' => 'test',
            'password' => '12345678',
        ]);
        $produk = Produk::create([
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
        $this->actingAs($user);
        $response = $this->get('produk/' . $produk->prefix);
        $response->assertStatus(200);
        $response->assertSee('Semen Karungan');
    }

    public function test_user_tidak_dapat_memasukkan_produk_ke_keranjang_karena_tidak_login()
    {
        $produk = Produk::create([
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
        $response = $this->post('api/keranjang', [
            'id_produk' => $produk->id
        ]);
        $response->assertStatus(200);
        $response->assertSee('Unauthorized');
    }

    public function test_user_dapat_melihat_produk_di_keranjang()
    {
        $user = User::create([
            'email' => 'test@gmail.com',
            'name' => 'test',
            'password' => '12345678',
        ]);
        $toko = Toko::create([
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
        $produk = Produk::create([
            'id_toko' => $toko->id,
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
        Keranjang::create([
            'id_user' => $user->id,
            'id_produk' => $produk->id,
            'qty' => 1,
        ]);
        $this->actingAs($user);
        $response = $this->get('keranjang');
        $response->assertStatus(200);
        $response->assertSee('Semen Karungan');
    }
}
