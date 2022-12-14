<?php

namespace Tests\Feature;

use App\Models\Favorite;
use App\Models\Produk;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Nette\Utils\Random;
use Tests\TestCase;

class FavoriteTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    use RefreshDatabase;

    public function test_user_dapat_menambahkan_favorite()
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
        $response = $this->post('favorite', [
            'id_produk' => $produk->id
        ], [
            'Referer' => 'produk/' . $produk->prefix
        ]);
        $response->assertStatus(302);
        $response->assertRedirect('produk/' . $produk->prefix);
        $response = $this->get('produk/' . $produk->prefix);
        $response->assertSee('img/heart.svg');
        $response->assertDontSeeText('icon-heart');
    }

    public function test_user_dapat_menghapus_favorite()
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
        Favorite::create([
            'id_user' => $user->id,
            'id_produk' => $produk->id
        ]);
        $response = $this->post('favorite', [
            'id_produk' => $produk->id
        ], [
            'Referer' => 'produk/' . $produk->prefix
        ]);
        $response->assertStatus(302);
        $response->assertRedirect('produk/' . $produk->prefix);
        $response = $this->get('produk/' . $produk->prefix);
        $response->assertSee('icon-heart');
        $response->assertDontSeeText('img/heart.svg');
    }

    public function test_user_dapat_mengakses_favorite_dengan_login()
    {
        $user = User::create([
            'email' => 'test@gmail.com',
            'name' => 'test',
            'password' => '12345678',
        ]);
        $this->actingAs($user);
        $response = $this->get('favorite');
        $response->assertStatus(200);
        $response->assertSee('Tidak ada produk yang disimpan');
    }

    public function test_user_dapat_mengakses_favorite_berisi_produk_dengan_login()
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
        Favorite::create([
            'id_user' => $user->id,
            'id_produk' => $produk->id
        ]);
        $this->actingAs($user);
        $response = $this->get('favorite');
        $response->assertStatus(200);
        $response->assertSee('Semen Karungan');
    }

    public function test_user_tidak_dapat_mengakses_favorite_tanpa_login()
    {
        $response = $this->get('favorite');
        $response->assertStatus(302);
        $response->assertRedirect('login');
    }
}
