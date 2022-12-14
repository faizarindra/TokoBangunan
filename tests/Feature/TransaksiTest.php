<?php

namespace Tests\Feature;

use App\Models\Alamat;
use App\Models\Kategori;
use App\Models\Keranjang;
use App\Models\Merk;
use App\Models\Pembayaran;
use App\Models\Produk;
use App\Models\Toko;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Nette\Utils\Random;
use Tests\TestCase;

class TransaksiTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    use RefreshDatabase;

    public function test_user_dapat_melakukan_transaksi_atau_checkout()
    {
        $user = User::create([
            'email' => 'test@gmail.com',
            'name' => 'test',
            'password' => '12345678',
        ]);
        $kategori = Kategori::create([
            'nama_kategori' => 'Semen',
            'prefix' => 'semen',
            'image' => 'semen.jpg',
        ]);
        $toko = Toko::create([
            'id_user' => $user->id,
            'nama_toko' => 'Berkah Jaya',
            'deskripsi_toko' => 'Menjual semen',
            'gambar_toko' => 'berkah.jpg',
            'alamat_toko' => 'Jl Ngasem',
            'latitude' => 0,
            'longitude' => 0,
            'prefix' => Random::generate(5),
            'id_provinsi' => 11,
            'nama_provinsi' => 'Jawa Timur',
            'id_kota' => 306,
            'nama_kota' => 'Kabupaten Ngawi',
            'id_kecamatan' => 4354,
            'nama_kecamatan' => 'Ngawi',
        ]);
        $merk = Merk::create([
            'id_kategori' => $kategori->id,
            'nama_merk' => 'Holcim',
            'prefix' => 'holcim',
            'image' => 'holcim.jpg'
        ]);
        $produk = Produk::create([
            'id_toko' => $toko->id,
            'id_user' => $user->id,
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
        $alamat = Alamat::create([
            'id_user' => $user->id,
            'nama_penerima' => 'Dwi Elok',
            'no_hp' => '08123456789',
            'alamat' => 'Jl Raya Kedungsari',
            'latitude' => 0,
            'longitude' => 0,
            'id_provinsi' => 11,
            'nama_provinsi' => 'Jawa Timur',
            'id_kota' => 251,
            'nama_kota' => 'Kabupaten Magetan',
            'id_kecamatan' => 3538,
            'nama_kecamatan' => 'Karas',
            'kode_pos' => '63213',
            'is_main' => 1,
        ]);
        $keranjang = Keranjang::create([
            'id_user' => $user->id,
            'id_produk' => $produk->id,
            'qty' => 1,
        ]);

        $data = [
            '_token' => 'AgdCNFw2JCvScexnPC25hHjbuLecIV8vxd7fXMVq',
            'keranjang' => ["1"],
            'data' => [
                [
                    'id_user' => $user->id,
                    'id_toko' => $toko->id,
                    'id_alamat' => $alamat->id,
                    'ongkir' => 8000,
                    'total_harga' => 59000,
                    'total' => 67000,
                    'products' => [
                        [
                            'id_produk' => $produk->id,
                            'qty' => 1,
                            'harga' => 59000,
                            'total' => 59000,
                        ]
                    ]
                ]
            ]
        ];
        $response = $this->json('post', '/api/transaction', $data);
        $response->assertStatus(200);
        $response->assertJson([
            'status' => true,
            'message' => 'Success'
        ]);
        $this->actingAs($user);
        $response = $this->get('pembayaran?data=' . $response->json('data')['encrypt_pembayaran']);
        $response->assertStatus(200);
        $response->assertSee('Review Pesanan');
    }

    public function test_user_tidak_dapat_melihat_notifikasi_jika_belum_login()
    {
        $response = $this->get('profile/notifikasi');
        $response->assertStatus(302);
        $response->assertRedirect('login');
    }

    public function test_user_dapat_melihat_notifikasi_setelah_melakukan_pesanan()
    {
        $user = User::create([
            'email' => 'test@gmail.com',
            'name' => 'test',
            'password' => '12345678',
        ]);
        $kategori = Kategori::create([
            'nama_kategori' => 'Semen',
            'prefix' => 'semen',
            'image' => 'semen.jpg',
        ]);
        $toko = Toko::create([
            'id_user' => $user->id,
            'nama_toko' => 'Berkah Jaya',
            'deskripsi_toko' => 'Menjual semen',
            'gambar_toko' => 'berkah.jpg',
            'alamat_toko' => 'Jl Ngasem',
            'latitude' => 0,
            'longitude' => 0,
            'prefix' => Random::generate(5),
            'id_provinsi' => 11,
            'nama_provinsi' => 'Jawa Timur',
            'id_kota' => 306,
            'nama_kota' => 'Kabupaten Ngawi',
            'id_kecamatan' => 4354,
            'nama_kecamatan' => 'Ngawi',
        ]);
        $merk = Merk::create([
            'id_kategori' => $kategori->id,
            'nama_merk' => 'Holcim',
            'prefix' => 'holcim',
            'image' => 'holcim.jpg'
        ]);
        $produk = Produk::create([
            'id_toko' => $toko->id,
            'id_user' => $user->id,
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
        $alamat = Alamat::create([
            'id_user' => $user->id,
            'nama_penerima' => 'Dwi Elok',
            'no_hp' => '08123456789',
            'alamat' => 'Jl Raya Kedungsari',
            'latitude' => 0,
            'longitude' => 0,
            'id_provinsi' => 11,
            'nama_provinsi' => 'Jawa Timur',
            'id_kota' => 251,
            'nama_kota' => 'Kabupaten Magetan',
            'id_kecamatan' => 3538,
            'nama_kecamatan' => 'Karas',
            'kode_pos' => '63213',
            'is_main' => 1,
        ]);
        $keranjang = Keranjang::create([
            'id_user' => $user->id,
            'id_produk' => $produk->id,
            'qty' => 1,
        ]);

        $data = [
            '_token' => 'AgdCNFw2JCvScexnPC25hHjbuLecIV8vxd7fXMVq',
            'keranjang' => ["1"],
            'data' => [
                [
                    'id_user' => $user->id,
                    'id_toko' => $toko->id,
                    'id_alamat' => $alamat->id,
                    'ongkir' => 8000,
                    'total_harga' => 59000,
                    'total' => 67000,
                    'products' => [
                        [
                            'id_produk' => $produk->id,
                            'qty' => 1,
                            'harga' => 59000,
                            'total' => 59000,
                        ]
                    ]
                ]
            ]
        ];
        $response = $this->json('post', '/api/transaction', $data);
        $response->assertStatus(200);
        $response->assertJson([
            'status' => true,
            'message' => 'Success'
        ]);
        $this->actingAs($user);
        $kode = $response->json('data')['data'][0]['kode'];
        $response = $this->get('profile/notifikasi');
        $response->assertStatus(200);
        $response->assertSee($kode);
    }

    public function test_user_tidak_dapat_melihat_pembayaran_jika_belum_login()
    {
        $response = $this->get('profile/pembayaran');
        $response->assertStatus(302);
        $response->assertRedirect('login');
    }

    public function test_user_dapat_melihat_pembayaran_setelah_melakukan_pesanan()
    {
        $user = User::create([
            'email' => 'test@gmail.com',
            'name' => 'test',
            'password' => '12345678',
        ]);
        $kategori = Kategori::create([
            'nama_kategori' => 'Semen',
            'prefix' => 'semen',
            'image' => 'semen.jpg',
        ]);
        $toko = Toko::create([
            'id_user' => $user->id,
            'nama_toko' => 'Berkah Jaya',
            'deskripsi_toko' => 'Menjual semen',
            'gambar_toko' => 'berkah.jpg',
            'alamat_toko' => 'Jl Ngasem',
            'latitude' => 0,
            'longitude' => 0,
            'prefix' => Random::generate(5),
            'id_provinsi' => 11,
            'nama_provinsi' => 'Jawa Timur',
            'id_kota' => 306,
            'nama_kota' => 'Kabupaten Ngawi',
            'id_kecamatan' => 4354,
            'nama_kecamatan' => 'Ngawi',
        ]);
        $merk = Merk::create([
            'id_kategori' => $kategori->id,
            'nama_merk' => 'Holcim',
            'prefix' => 'holcim',
            'image' => 'holcim.jpg'
        ]);
        $produk = Produk::create([
            'id_toko' => $toko->id,
            'id_user' => $user->id,
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
        $alamat = Alamat::create([
            'id_user' => $user->id,
            'nama_penerima' => 'Dwi Elok',
            'no_hp' => '08123456789',
            'alamat' => 'Jl Raya Kedungsari',
            'latitude' => 0,
            'longitude' => 0,
            'id_provinsi' => 11,
            'nama_provinsi' => 'Jawa Timur',
            'id_kota' => 251,
            'nama_kota' => 'Kabupaten Magetan',
            'id_kecamatan' => 3538,
            'nama_kecamatan' => 'Karas',
            'kode_pos' => '63213',
            'is_main' => 1,
        ]);
        $keranjang = Keranjang::create([
            'id_user' => $user->id,
            'id_produk' => $produk->id,
            'qty' => 1,
        ]);

        $data = [
            '_token' => 'AgdCNFw2JCvScexnPC25hHjbuLecIV8vxd7fXMVq',
            'keranjang' => ["1"],
            'data' => [
                [
                    'id_user' => $user->id,
                    'id_toko' => $toko->id,
                    'id_alamat' => $alamat->id,
                    'ongkir' => 8000,
                    'total_harga' => 59000,
                    'total' => 67000,
                    'products' => [
                        [
                            'id_produk' => $produk->id,
                            'qty' => 1,
                            'harga' => 59000,
                            'total' => 59000,
                        ]
                    ]
                ]
            ]
        ];
        $response = $this->json('post', '/api/transaction', $data);
        $response->assertStatus(200);
        $response->assertJson([
            'status' => true,
            'message' => 'Success'
        ]);
        $this->actingAs($user);
        $id_pembayaran = $response->json('data')['pembayaran'];
        $pembayaran = Pembayaran::find($id_pembayaran);
        $response = $this->get('profile/pembayaran');
        $response->assertStatus(200);
        $response->assertSee($pembayaran->kode);
    }

    public function test_user_tidak_dapat_melihat_pesanan_jika_belum_login()
    {
        $response = $this->get('profile/pesanan');
        $response->assertStatus(302);
        $response->assertRedirect('login');
    }

    public function test_user_dapat_melihat_pesanan_setelah_melakukan_pesanan()
    {
        $user = User::create([
            'email' => 'test@gmail.com',
            'name' => 'test',
            'password' => '12345678',
        ]);
        $kategori = Kategori::create([
            'nama_kategori' => 'Semen',
            'prefix' => 'semen',
            'image' => 'semen.jpg',
        ]);
        $toko = Toko::create([
            'id_user' => $user->id,
            'nama_toko' => 'Berkah Jaya',
            'deskripsi_toko' => 'Menjual semen',
            'gambar_toko' => 'berkah.jpg',
            'alamat_toko' => 'Jl Ngasem',
            'latitude' => 0,
            'longitude' => 0,
            'prefix' => Random::generate(5),
            'id_provinsi' => 11,
            'nama_provinsi' => 'Jawa Timur',
            'id_kota' => 306,
            'nama_kota' => 'Kabupaten Ngawi',
            'id_kecamatan' => 4354,
            'nama_kecamatan' => 'Ngawi',
        ]);
        $merk = Merk::create([
            'id_kategori' => $kategori->id,
            'nama_merk' => 'Holcim',
            'prefix' => 'holcim',
            'image' => 'holcim.jpg'
        ]);
        $produk = Produk::create([
            'id_toko' => $toko->id,
            'id_user' => $user->id,
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
        $alamat = Alamat::create([
            'id_user' => $user->id,
            'nama_penerima' => 'Dwi Elok',
            'no_hp' => '08123456789',
            'alamat' => 'Jl Raya Kedungsari',
            'latitude' => 0,
            'longitude' => 0,
            'id_provinsi' => 11,
            'nama_provinsi' => 'Jawa Timur',
            'id_kota' => 251,
            'nama_kota' => 'Kabupaten Magetan',
            'id_kecamatan' => 3538,
            'nama_kecamatan' => 'Karas',
            'kode_pos' => '63213',
            'is_main' => 1,
        ]);
        $keranjang = Keranjang::create([
            'id_user' => $user->id,
            'id_produk' => $produk->id,
            'qty' => 1,
        ]);

        $data = [
            '_token' => 'AgdCNFw2JCvScexnPC25hHjbuLecIV8vxd7fXMVq',
            'keranjang' => ["1"],
            'data' => [
                [
                    'id_user' => $user->id,
                    'id_toko' => $toko->id,
                    'id_alamat' => $alamat->id,
                    'ongkir' => 8000,
                    'total_harga' => 59000,
                    'total' => 67000,
                    'products' => [
                        [
                            'id_produk' => $produk->id,
                            'qty' => 1,
                            'harga' => 59000,
                            'total' => 59000,
                        ]
                    ]
                ]
            ]
        ];
        $response = $this->json('post', '/api/transaction', $data);
        $response->assertStatus(200);
        $response->assertJson([
            'status' => true,
            'message' => 'Success'
        ]);
        $this->actingAs($user);
        $kode = $response->json('data')['data'][0]['kode'];
        $response = $this->get('profile/pesanan');
        $response->assertStatus(200);
        $response->assertSee($kode);
    }
}
