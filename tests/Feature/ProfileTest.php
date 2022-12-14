<?php

namespace Tests\Feature;

use App\Models\Produk;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Nette\Utils\Random;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    use RefreshDatabase;

    public function test_user_tidak_dapat_mengakses_halaman_profile_jika_tidak_login()
    {
        $response = $this->get('profile');
        $response->assertStatus(302);
        $response->assertRedirect('login');
    }

    public function test_user_dapat_mengakses_halaman_profile_saat_login()
    {
        $user = User::create([
            'email' => 'test@gmail.com',
            'name' => 'test',
            'password' => '12345678',
        ]);
        $this->actingAs($user);
        $response = $this->get('profile');
        $response->assertStatus(200);
        $response->assertSee('Halo, test');
        $response->assertSee('test@gmail.com');
        $response->assertSee('Informasi Akun');
    }

    public function test_user_dapat_merubah_data_diri()
    {
        $user = User::create([
            'email' => 'test@gmail.com',
            'name' => 'test',
            'password' => '12345678',
        ]);
        $this->actingAs($user);
        $response = $this->get('profile');
        $response->assertStatus(200);
        $response = $this->patch('profile/user', [
            'name' => 'test2',
            'email' => 'test2@gmail.com',
            'no_telp' => '081234567890',
            'dob' => '1999-01-01',
            'jenis_kelamin' => 0,
        ]);
        $response->assertStatus(302);
        $response->assertRedirect('profile');
        $user->refresh();
        $response = $this->get('profile');
        $response->assertSee('test2');
        $response->assertSee('test2@gmail.com');
        $response->assertSee('081234567890');
        $response->assertSee('1999-01-01');
        $response->assertSee('Perempuan');
    }

    public function test_user_dapat_merubah_foto_profile()
    {
        $user = User::create([
            'email' => 'test@gmail.com',
            'name' => 'test',
            'password' => '12345678',
        ]);
        $this->actingAs($user);
        $response = $this->get('profile');
        $response->assertStatus(200);
        $file = UploadedFile::fake()->image('file.png', 600, 600);
        $response = $this->patch('profile/photo', [
            'image' => $file,
        ]);
        $response->assertStatus(302);
        $response->assertRedirect('profile');
        $user->refresh();
        $response = $this->get('profile');
        $response->assertDontSeeText('http://nouthemes.net/html/martfury/img/users/3.jpg');
    }

    public function test_user_tidak_dapat_mengakses_halaman_terakhir_dilihat_jika_tidak_login()
    {
        $response = $this->get('profile/terakhir-dilihat');
        $response->assertStatus(302);
        $response->assertRedirect('login');
    }

    public function test_user_dapat_mengakses_halaman_terakhir_dilihat_ketika_tidak_ada_produk_saat_login()
    {
        $user = User::create([
            'email' => 'test@gmail.com',
            'name' => 'test',
            'password' => '12345678',
        ]);
        $this->actingAs($user);
        $response = $this->get('profile/terakhir-dilihat');
        $response->assertStatus(200);
        $response->assertSee('Tidak ada produk');
    }

    public function test_user_dapat_mengakses_halaman_terakhir_dilihat_ketika_ada_produk_saat_login()
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
        $response = $this->get('profile/terakhir-dilihat');
        $response->assertStatus(200);
        $response->assertSee('Semen Karungan');
    }
}
