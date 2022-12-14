<?php

namespace Tests\Feature;

use App\Models\Alamat;
use App\Models\Bank;
use App\Models\Pembayaran;
use App\Models\Produk;
use App\Models\Rekening;
use App\Models\Toko;
use App\Models\Transaksi;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Crypt;
use Nette\Utils\Random;
use Tests\TestCase;

class TokoTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    use RefreshDatabase;

    public function test_user_tidak_dapat_melihat_dashboard_toko_saat_belum_daftar_toko()
    {
        $user = User::create([
            'email' => 'test@gmail.com',
            'name' => 'test',
            'password' => '12345678',
        ]);
        $this->actingAs($user);
        $response = $this->get('/seller/dashboard');
        $response->assertStatus(302);
        $response->assertRedirect('/seller/buat-toko');
    }

    public function test_user_dapat_register_toko_saat_login()
    {
        $user = User::create([
            'email' => 'test@gmail.com',
            'name' => 'test',
            'password' => '12345678',
        ]);
        $this->actingAs($user);
        $response = $this->get('/seller/buat-toko');
        $response->assertStatus(200);
        $response->assertSee('Buat Toko');
        $response = $this->post('/seller/buat-toko', [
            'nama_toko' => 'Toko Test',
            'deskripsi_toko' => 'Toko ini adalah toko test',
            'gambar_toko' => 'gambar_toko.png',
            'alamat_toko' => 'Jl. Test',
            'latitude' => '-7.123456',
            'longitude' => '112.123456',
            'prefix' => Random::generate(5),
            'kecamatan' => '3538#Karas',
            'kota' => '251#Kabupaten Magetan',
            'provinsi' => '11#Jawa Timur',
            'kode_pos' => '63213',
        ], [
            'Referer' => 'seller/dashboard'
        ]);
        $response->assertStatus(302);
        $response->assertRedirect('/seller/dashboard');
    }

    public function test_user_tidak_dapat_akses_register_toko_saat_sudah_daftar_toko()
    {
        $user = User::create([
            'email' => 'test@gmail.com',
            'name' => 'test',
            'password' => '12345678',
        ]);
        $this->actingAs($user);
        Toko::create([
            'id_user' => $user->id,
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
        $response = $this->get('/seller/buat-toko');
        $response->assertStatus(302);
        $response->assertRedirect('/seller/dashboard');
    }

    public function test_user_dapat_mengedit_toko_yang_sudah_terdaftar()
    {
        $user = User::create([
            'email' => 'test@gmail.com',
            'name' => 'test',
            'password' => '12345678',
        ]);
        $this->actingAs($user);
        $toko = Toko::create([
            'id_user' => $user->id,
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
        $response = $this->get('/seller/edit-toko');
        $response->assertStatus(200);
        $file = UploadedFile::fake()->image('file.png', 600, 600);
        $response = $this->patch('seller/edit-toko', [
            'id' => $toko->id,
            'nama_toko' => 'Toko Test Edit',
            'deskripsi_toko' => 'Toko ini adalah toko test',
            'gambar_toko' => $file,
            'alamat_toko' => 'Jl. Test',
            'kecamatan' => '3538#Karas',
            'kota' => '251#Kabupaten Magetan',
            'provinsi' => '11#Jawa Timur',
            'kode_pos' => '63213',
        ], [
            'Referer' => '/seller/edit-toko'
        ]);
        $response->assertStatus(302);
        $response->assertRedirect('/seller/edit-toko');
        $toko->refresh();
        $response = $this->get('/seller/edit-toko');
        $response->assertSee('Toko Test Edit');
    }

    public function test_toko_dapat_mengakses_halaman_rekening_kosong()
    {
        $user = User::create([
            'email' => 'test@gmail.com',
            'name' => 'test',
            'password' => '12345678',
        ]);
        Toko::create([
            'id_user' => $user->id,
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
        $this->actingAs($user);
        $response = $this->get('/seller/rekening');
        $response->assertStatus(200);
        $response->assertSee('Tambah');
        $response->assertSee('Belum ada rekening yang disimpan');
    }

    public function test_toko_dapat_mengakses_halaman_rekening_yang_ada_isinya()
    {
        $user = User::create([
            'email' => 'test@gmail.com',
            'name' => 'test',
            'password' => '12345678',
        ]);
        $bank = Bank::create([
            'nama' => 'BRI',
            'logo' => 'bri.png'
        ]);
        $toko  = Toko::create([
            'id_user' => $user->id,
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
        Rekening::create([
            'id_bank' => $bank->id,
            'id_toko' => $toko->id,
            'no_rekening' => '12345',
            'atas_nama' => 'Aini',
            'cabang' => 'Magetan'
        ]);
        $this->actingAs($user);
        $response = $this->get('/seller/rekening');
        $response->assertStatus(200);
        $response->assertSee('Tambah');
        $response->assertSee('Bank BRI');
        $response->assertSee('12345 a/n Aini');
    }

    public function test_toko_dapat_membuat_rekening_baru()
    {
        $user = User::create([
            'email' => 'test@gmail.com',
            'name' => 'test',
            'password' => '12345678',
        ]);
        $bank = Bank::create([
            'nama' => 'BRI',
            'logo' => 'bri.png'
        ]);
        Toko::create([
            'id_user' => $user->id,
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
        $this->actingAs($user);
        $response = $this->get('/seller/tambah-rekening');
        $response->assertStatus(200);
        $response = $this->post('seller/tambah-rekening', [
            'id_bank' => $bank->id,
            'no_rekening' => '12345',
            'atas_nama' => 'Aini',
            'cabang' => 'Magetan'
        ]);
        $response->assertStatus(302);
        $response->assertRedirect('seller/rekening');
    }

    public function test_toko_dapat_mengedit_rekening_yang_ditambahkan()
    {
        $user = User::create([
            'email' => 'test@gmail.com',
            'name' => 'test',
            'password' => '12345678',
        ]);
        $bank = Bank::create([
            'nama' => 'BRI',
            'logo' => 'bri.png'
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
            'id_provinsi' => 1,
            'nama_provinsi' => 'Jawa Timur',
            'id_kota' => '1',
            'nama_kota' => 'Malang',
            'id_kecamatan' => 1,
            'nama_kecamatan' => 'Lowokwaru'
        ]);
        $rekening = Rekening::create([
            'id_bank' => $bank->id,
            'id_toko' => $toko->id,
            'no_rekening' => '12345',
            'atas_nama' => 'Aini',
            'cabang' => 'Magetan'
        ]);
        $this->actingAs($user);
        $request = $this->get('seller/rekening');
        $request->assertStatus(200);
        $request->assertSee('Aini');
        $request = $this->get('seller/rekening/' . Crypt::encrypt($rekening->id));
        $request->assertStatus(200);
        $response = $this->patch('seller/rekening', [
            'id' => $rekening->id,
            'id_bank' => $bank->id,
            'no_rekening' => '12345',
            'atas_nama' => 'Aini Edit',
            'cabang' => 'Magetan'
        ]);
        $response->assertStatus(302);
        $response->assertRedirect('seller/rekening');
        $rekening->refresh();
        $request = $this->get('seller/rekening');
        $request->assertStatus(200);
        $request->assertSee('Aini Edit');
    }

    public function test_toko_dapat_menghapus_rekening_yang_ditambahkan()
    {
        $user = User::create([
            'email' => 'test@gmail.com',
            'name' => 'test',
            'password' => '12345678',
        ]);
        $bank = Bank::create([
            'nama' => 'BRI',
            'logo' => 'bri.png'
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
            'id_provinsi' => 1,
            'nama_provinsi' => 'Jawa Timur',
            'id_kota' => '1',
            'nama_kota' => 'Malang',
            'id_kecamatan' => 1,
            'nama_kecamatan' => 'Lowokwaru'
        ]);
        $rekening = Rekening::create([
            'id_bank' => $bank->id,
            'id_toko' => $toko->id,
            'no_rekening' => '12345',
            'atas_nama' => 'Aini',
            'cabang' => 'Magetan'
        ]);
        $this->actingAs($user);
        $request = $this->get('seller/rekening');
        $request->assertStatus(200);
        $request->assertSee('Aini');
        $response = $this->delete('seller/rekening/' . $rekening->id);
        $response->assertStatus(302);
        $response->assertRedirect('seller/rekening');
        $request = $this->get('seller/rekening');
        $request->assertStatus(200);
        $request->assertDontSee('Aini');
    }

    public function test_toko_dapat_mengakses_halaman_produk_yang_tidak_ada_isinya()
    {
        $user = User::create([
            'email' => 'test@gmail.com',
            'name' => 'test',
            'password' => '12345678',
        ]);
        Toko::create([
            'id_user' => $user->id,
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
        $this->actingAs($user);
        $response = $this->get('seller/produk');
        $response->assertStatus(200);
        $response->assertSee('Tidak ada produk yang ditemukan');
    }

    public function test_toko_dapat_mengakses_halaman_produk_yang_ada_isinya()
    {
        $user = User::create([
            'email' => 'test@gmail.com',
            'name' => 'test',
            'password' => '12345678',
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
            'id_provinsi' => 1,
            'nama_provinsi' => 'Jawa Timur',
            'id_kota' => '1',
            'nama_kota' => 'Malang',
            'id_kecamatan' => 1,
            'nama_kecamatan' => 'Lowokwaru'
        ]);
        $file = UploadedFile::fake()->image('file.png', 600, 600);
        Produk::create([
            'id_toko' => $toko->id,
            'id_user' => $user->id,
            'id_kategori' => 1,
            'id_merk' => 1,
            'nama_produk' => 'Semen Sak',
            'deskripsi' => 'Menjual berbagai semen',
            'gambar_produk' => $file,
            'harga_produk' => 50000,
            'stok_raw' => 10,
            'satuan_produk' => 'Kg',
            'prefix' => Random::generate(5) . '-semen-sak'
        ]);
        $this->actingAs($user);
        $response = $this->get('seller/produk');
        $response->assertStatus(200);
        $response->assertSee('Semen Sak');
    }

    public function test_toko_dapat_membuat_produk_baru()
    {
        $user = User::create([
            'email' => 'test@gmail.com',
            'name' => 'test',
            'password' => '12345678',
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
            'id_provinsi' => 1,
            'nama_provinsi' => 'Jawa Timur',
            'id_kota' => '1',
            'nama_kota' => 'Malang',
            'id_kecamatan' => 1,
            'nama_kecamatan' => 'Lowokwaru'
        ]);
        $this->actingAs($user);
        $response = $this->get('/seller/tambah-produk');
        $response->assertStatus(200);
        $file = UploadedFile::fake()->image('file.png', 600, 600);
        $response = $this->post('seller/tambah-produk', [
            'id_toko' => $toko->id,
            'id_user' => $user->id,
            'id_kategori' => 1,
            'id_merk' => 1,
            'nama_produk' => 'Semen Sak',
            'deskripsi' => 'Menjual berbagai semen',
            'gambar_produk' => $file,
            'harga_produk' => 50000,
            'stok_raw' => 10,
            'satuan_produk' => 'Kg',
            'prefix' => Random::generate(5) . '-semen-sak'
        ]);
        $response->assertStatus(302);
        $response->assertRedirect('seller/produk');
    }

    public function test_toko_dapat_mengedit_produk_yang_ditambahkan()
    {
        $user = User::create([
            'email' => 'test@gmail.com',
            'name' => 'test',
            'password' => '12345678',
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
            'id_provinsi' => 1,
            'nama_provinsi' => 'Jawa Timur',
            'id_kota' => '1',
            'nama_kota' => 'Malang',
            'id_kecamatan' => 1,
            'nama_kecamatan' => 'Lowokwaru'
        ]);
        $file = UploadedFile::fake()->image('file.png', 600, 600);
        $produk = Produk::create([
            'id_toko' => $toko->id,
            'id_user' => $user->id,
            'id_kategori' => 1,
            'id_merk' => 1,
            'nama_produk' => 'Semen Sak',
            'deskripsi' => 'Menjual berbagai semen',
            'gambar_produk' => $file,
            'harga_produk' => 50000,
            'stok_raw' => 10,
            'satuan_produk' => 'Kg',
            'prefix' => Random::generate(5) . '-semen-sak'
        ]);
        $this->actingAs($user);
        $request = $this->get('seller/produk');
        $request->assertStatus(200);
        $request->assertSee('Semen Sak');
        $request = $this->get('seller/produk/' . Crypt::encrypt($produk->id));
        $request->assertStatus(200);
        $file = UploadedFile::fake()->image('file.png', 600, 600);
        $response = $this->withHeaders(['Content-Type' => 'multipart/form-data'])
            ->patch('seller/produk', [
                'id' => $produk->id,
                'id_toko' => $toko->id,
                'id_user' => $user->id,
                'id_kategori' => 1,
                'id_merk' => 1,
                'nama_produk' => 'Semen Karungan',
                'deskripsi' => 'Menjual berbagai semen',
                'harga_produk' => 50000,
                'stok_raw' => 10,
                'satuan_produk' => 'Kg'
            ], [
                'Referer' => 'seller/produk'
            ]);
        $response->assertStatus(302);
        $response->assertRedirect('seller/produk');
        $produk->refresh();
        $request = $this->get('seller/produk');
        $request->assertStatus(200);
        $request->assertSee('Semen Karungan');
    }

    public function test_toko_dapat_menghapus_produk_yang_ditambahkan()
    {
        $user = User::create([
            'email' => 'test@gmail.com',
            'name' => 'test',
            'password' => '12345678',
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
            'id_provinsi' => 1,
            'nama_provinsi' => 'Jawa Timur',
            'id_kota' => '1',
            'nama_kota' => 'Malang',
            'id_kecamatan' => 1,
            'nama_kecamatan' => 'Lowokwaru'
        ]);
        $file = UploadedFile::fake()->image('file.png', 600, 600);
        $produk = Produk::create([
            'id_toko' => $toko->id,
            'id_user' => $user->id,
            'id_kategori' => 1,
            'id_merk' => 1,
            'nama_produk' => 'Semen Sak',
            'deskripsi' => 'Menjual berbagai semen',
            'gambar_produk' => $file,
            'harga_produk' => 50000,
            'stok_raw' => 10,
            'satuan_produk' => 'Kg',
            'prefix' => Random::generate(5) . '-semen-sak'
        ]);
        $this->actingAs($user);
        $request = $this->get('seller/produk');
        $request->assertStatus(200);
        $request->assertSee('Semen Sak');
        $response = $this->delete('seller/produk/' . $produk->id);
        $response->assertStatus(302);
        $response->assertRedirect('seller/produk');
        $request = $this->get('seller/produk');
        $request->assertStatus(200);
        $request->assertDontSee('Semen Sak');
    }

    public function test_toko_dapat_melihat_penjualan_yang_kosong()
    {
        $user = User::create([
            'email' => 'test@gmail.com',
            'name' => 'test',
            'password' => '12345678',
        ]);
        Toko::create([
            'id_user' => $user->id,
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
        $this->actingAs($user);
        $request = $this->get('seller/penjualan');
        $request->assertStatus(200);
        $request->assertSee('Belum ada pesanan');
    }

    public function test_toko_dapat_melihat_penjualan_yang_ada_isinya()
    {
        $user = User::create([
            'email' => 'test@gmail.com',
            'name' => 'test',
            'password' => '12345678',
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
            'id_provinsi' => 1,
            'nama_provinsi' => 'Jawa Timur',
            'id_kota' => '1',
            'nama_kota' => 'Malang',
            'id_kecamatan' => 1,
            'nama_kecamatan' => 'Lowokwaru'
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
            'is_main' => 0,
        ]);
        $pembayaran = Pembayaran::create([
            'kode' => 'MTR-' . Random::generate(5),
            'id_user' => $user->id,
            'status' => 0,
        ]);
        $file = UploadedFile::fake()->image('file.png', 600, 600);
        $produk = Produk::create([
            'id_toko' => $toko->id,
            'id_user' => $user->id,
            'id_kategori' => 1,
            'id_merk' => 1,
            'nama_produk' => 'Semen Sak',
            'deskripsi' => 'Menjual berbagai semen',
            'gambar_produk' => $file,
            'harga_produk' => 50000,
            'stok_raw' => 10,
            'satuan_produk' => 'Kg',
            'prefix' => Random::generate(5) . '-semen-sak'
        ]);
        $transaksi = Transaksi::create([
            'id_pembayaran' => $pembayaran->id,
            'kode' => 'TBL-' . Random::generate(5),
            'id_user' => $user->id,
            'id_toko' => $toko->id,
            'id_alamat' => $alamat->id,
            'ongkir' => 8000,
            'total_harga' => 50000,
            'total' => 58000,
            'status' => 0,
        ]);
        $this->actingAs($user);
        $request = $this->get('seller/penjualan');
        $request->assertStatus(200);
        $request->assertSee($transaksi->kode);
    }

    public function test_toko_dapat_mengacc_penjualan()
    {
        $user = User::create([
            'email' => 'test@gmail.com',
            'name' => 'test',
            'password' => '12345678',
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
            'id_provinsi' => 1,
            'nama_provinsi' => 'Jawa Timur',
            'id_kota' => '1',
            'nama_kota' => 'Malang',
            'id_kecamatan' => 1,
            'nama_kecamatan' => 'Lowokwaru'
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
            'is_main' => 0,
        ]);
        $pembayaran = Pembayaran::create([
            'kode' => 'MTR-' . Random::generate(5),
            'id_user' => $user->id,
            'status' => 0,
        ]);
        $file = UploadedFile::fake()->image('file.png', 600, 600);
        $produk = Produk::create([
            'id_toko' => $toko->id,
            'id_user' => $user->id,
            'id_kategori' => 1,
            'id_merk' => 1,
            'nama_produk' => 'Semen Sak',
            'deskripsi' => 'Menjual berbagai semen',
            'gambar_produk' => $file,
            'harga_produk' => 50000,
            'stok_raw' => 10,
            'satuan_produk' => 'Kg',
            'prefix' => Random::generate(5) . '-semen-sak'
        ]);
        $transaksi = Transaksi::create([
            'id_pembayaran' => $pembayaran->id,
            'kode' => 'TBL-' . Random::generate(5),
            'id_user' => $user->id,
            'id_toko' => $toko->id,
            'id_alamat' => $alamat->id,
            'ongkir' => 8000,
            'total_harga' => 50000,
            'total' => 58000,
            'status' => 1,
        ]);
        $this->actingAs($user);
        $request = $this->get('seller/penjualan');
        $request->assertStatus(200);
        $request->assertSee('Pesanan Dibayar');
        $request = $this->post('seller/penjualan/acc', [
            'id' => $transaksi->id,
            'status' => 2
        ]);
        $request->assertStatus(302);
        $request->assertRedirect('seller/penjualan');
        $request = $this->get('seller/penjualan');
        $request->assertStatus(200);
        $request->assertSee('Pesanan Diproses Toko');
    }

    public function test_toko_dapat_menolak_penjualan()
    {
        $user = User::create([
            'email' => 'test@gmail.com',
            'name' => 'test',
            'password' => '12345678',
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
            'id_provinsi' => 1,
            'nama_provinsi' => 'Jawa Timur',
            'id_kota' => '1',
            'nama_kota' => 'Malang',
            'id_kecamatan' => 1,
            'nama_kecamatan' => 'Lowokwaru'
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
            'is_main' => 0,
        ]);
        $pembayaran = Pembayaran::create([
            'kode' => 'MTR-' . Random::generate(5),
            'id_user' => $user->id,
            'status' => 0,
        ]);
        $file = UploadedFile::fake()->image('file.png', 600, 600);
        $produk = Produk::create([
            'id_toko' => $toko->id,
            'id_user' => $user->id,
            'id_kategori' => 1,
            'id_merk' => 1,
            'nama_produk' => 'Semen Sak',
            'deskripsi' => 'Menjual berbagai semen',
            'gambar_produk' => $file,
            'harga_produk' => 50000,
            'stok_raw' => 10,
            'satuan_produk' => 'Kg',
            'prefix' => Random::generate(5) . '-semen-sak'
        ]);
        $transaksi = Transaksi::create([
            'id_pembayaran' => $pembayaran->id,
            'kode' => 'TBL-' . Random::generate(5),
            'id_user' => $user->id,
            'id_toko' => $toko->id,
            'id_alamat' => $alamat->id,
            'ongkir' => 8000,
            'total_harga' => 50000,
            'total' => 58000,
            'status' => 1,
        ]);
        $this->actingAs($user);
        $request = $this->get('seller/penjualan');
        $request->assertStatus(200);
        $request->assertSee('Pesanan Dibayar');
        $request = $this->post('seller/penjualan/tolak', [
            'id' => $transaksi->id,
            'status' => 7
        ]);
        $request->assertStatus(302);
        $request->assertRedirect('seller/penjualan');
        $request = $this->get('seller/penjualan');
        $request->assertStatus(200);
        $request->assertSee('Pesanan Ditolak Toko');
    }

    public function test_toko_dapat_mengubah_status_penjualan_menjadi_dikirim()
    {
        $user = User::create([
            'email' => 'test@gmail.com',
            'name' => 'test',
            'password' => '12345678',
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
            'id_provinsi' => 1,
            'nama_provinsi' => 'Jawa Timur',
            'id_kota' => '1',
            'nama_kota' => 'Malang',
            'id_kecamatan' => 1,
            'nama_kecamatan' => 'Lowokwaru'
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
            'is_main' => 0,
        ]);
        $pembayaran = Pembayaran::create([
            'kode' => 'MTR-' . Random::generate(5),
            'id_user' => $user->id,
            'status' => 0,
        ]);
        $file = UploadedFile::fake()->image('file.png', 600, 600);
        $produk = Produk::create([
            'id_toko' => $toko->id,
            'id_user' => $user->id,
            'id_kategori' => 1,
            'id_merk' => 1,
            'nama_produk' => 'Semen Sak',
            'deskripsi' => 'Menjual berbagai semen',
            'gambar_produk' => $file,
            'harga_produk' => 50000,
            'stok_raw' => 10,
            'satuan_produk' => 'Kg',
            'prefix' => Random::generate(5) . '-semen-sak'
        ]);
        $transaksi = Transaksi::create([
            'id_pembayaran' => $pembayaran->id,
            'kode' => 'TBL-' . Random::generate(5),
            'id_user' => $user->id,
            'id_toko' => $toko->id,
            'id_alamat' => $alamat->id,
            'ongkir' => 8000,
            'total_harga' => 50000,
            'total' => 58000,
            'status' => 1,
        ]);
        $this->actingAs($user);
        $request = $this->get('seller/penjualan');
        $request->assertStatus(200);
        $request->assertSee('Pesanan Dibayar');
        $request = $this->post('seller/penjualan/kirim', [
            'id' => $transaksi->id,
            'status' => 3
        ]);
        $request->assertStatus(302);
        $request->assertRedirect('seller/penjualan');
        $request = $this->get('seller/penjualan');
        $request->assertStatus(200);
        $request->assertSee('Pesanan Dikirim');
        $request->assertSee($transaksi->kode);
    }
}
