<?php

namespace Tests\Feature;

use App\Models\Alamat;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Crypt;
use Tests\TestCase;

class AlamatTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    use RefreshDatabase;

    public function test_user_tidak_dapat_melihat_halaman_alamat_saya_saat_tidak_login()
    {
        $request = $this->get('profile/alamat');
        $request->assertStatus(302);
        $request->assertRedirect('login');
    }

    public function test_user_dapat_melihat_halaman_alamat_saya_saat_login()
    {
        $user = User::create([
            'email' => 'test@gmail.com',
            'name' => 'test',
            'password' => bcrypt('12345678'),
        ]);

        $this->actingAs($user);
        $request = $this->get('profile/alamat');
        $request->assertStatus(200);
        $request->assertSee('Alamat Saya');
    }

    public function test_user_dapat_menambahkan_alamat()
    {
        $user = User::create([
            'email' => 'test@gmail.com',
            'name' => 'test',
            'password' => bcrypt('12345678'),
        ]);

        $this->actingAs($user);
        $request = $this->post('profile/tambah-alamat', [
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
        ], [
            'Referer' => 'profile/alamat',
        ]);
        $request->assertStatus(302);
        $request->assertRedirect('profile/alamat');
    }

    public function test_user_dapat_melihat_alamat_yang_ditambahkan()
    {
        $user = User::create([
            'email' => 'test@gmail.com',
            'name' => 'test',
            'password' => bcrypt('12345678'),
        ]);

        $this->actingAs($user);
        Alamat::create([
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
        $request = $this->get('profile/alamat');
        $request->assertStatus(200);
        $request->assertSee('Dwi Elok');
        $request->assertSee('08123456789');
        $request->assertSee('Jl Raya Kedungsari');
        $request->assertSee('Kabupaten Magetan');
        $request->assertSee('Karas');
        $request->assertSee('63213');
    }

    public function test_user_dapat_membuka_halaman_edit_alamat()
    {
        $user = User::create([
            'email' => 'test@gmail.com',
            'name' => 'test',
            'password' => bcrypt('12345678'),
        ]);

        $this->actingAs($user);
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
        $request = $this->get('profile/alamat');
        $request->assertStatus(200);
        $request->assertSee('Dwi Elok');
        $request = $this->get('profile/alamat/' . Crypt::encrypt($alamat->id));
        $request->assertStatus(200);
        $request->assertSee('Edit Alamat');
    }

    public function test_user_dapat_mengedit_alamat_yang_ditambahkan()
    {
        $user = User::create([
            'email' => 'test@gmail.com',
            'name' => 'test',
            'password' => bcrypt('12345678'),
        ]);

        $this->actingAs($user);
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
        $request = $this->get('profile/alamat');
        $request->assertStatus(200);
        $request->assertSee('Dwi Elok');
        $request = $this->get('profile/alamat/' . Crypt::encrypt($alamat->id));
        $request->assertStatus(200);
        $request = $this->patch('profile/alamat', [
            'id' => $alamat->id,
            'latitude' => '0',
            'longitude' => '0',
            'nama_penerima' => 'Aini',
            'alamat' => 'Jl Raya Karas',
            'no_hp' => '08123456789',
            'kecamatan' => '3538#Karas',
            'kota' => '251#Kabupaten Magetan',
            'provinsi' => '11#Jawa Timur',
            'kode_pos' => '63213',
        ]);
        $request->assertStatus(302);
        $request->assertRedirect('profile/alamat');
        $alamat->refresh();
        $request = $this->get('profile/alamat');
        $request->assertStatus(200);
        $request->assertSee('Aini');
        $request->assertSee('Jl Raya Karas');
    }

    public function test_user_dapat_menghapus_alamat_yang_ditambahkan()
    {
        $user = User::create([
            'email' => 'test@gmail.com',
            'name' => 'test',
            'password' => bcrypt('12345678'),
        ]);

        $this->actingAs($user);
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
        $request = $this->get('profile/alamat');
        $request->assertStatus(200);
        $request->assertSee('Dwi Elok');
        $request = $this->delete('profile/alamat/' . $alamat->id);
        $request->assertStatus(302);
        $request->assertRedirect('profile/alamat');
        $request = $this->get('profile/alamat');
        $request->assertStatus(200);
        $request->assertDontSee('Dwi Elok');
    }

    public function test_user_dapat_menjadikan_alamat_utama()
    {
        $user = User::create([
            'email' => 'test@gmail.com',
            'name' => 'test',
            'password' => bcrypt('12345678'),
        ]);

        $this->actingAs($user);
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
        $request = $this->get('profile/alamat');
        $request->assertStatus(200);
        $request->assertSee('Dwi Elok');
        $request = $this->get('profile/alamat/' . Crypt::encrypt($alamat->id));
        $request->assertStatus(200);
        $request = $this->patch('profile/alamat', [
            'id' => $alamat->id,
            'latitude' => '0',
            'longitude' => '0',
            'nama_penerima' => 'Aini',
            'alamat' => 'Jl Raya Karas',
            'no_hp' => '08123456789',
            'kecamatan' => '3538#Karas',
            'kota' => '251#Kabupaten Magetan',
            'provinsi' => '11#Jawa Timur',
            'kode_pos' => '63213',
            'is_main' => 1,
        ]);
        $request->assertStatus(302);
        $request->assertRedirect('profile/alamat');
        $alamat->refresh();
        $request = $this->get('profile/alamat');
        $request->assertStatus(200);
        $request->assertSee('Aini');
        $request->assertSee('Jl Raya Karas');
        $request->assertSee('Utama');
    }
}
