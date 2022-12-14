<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AuthTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */

    use RefreshDatabase;

    public function test_user_dapat_register()
    {
        $response = $this->post(
            '/register',
            [
                'name' => 'test',
                'email' => 'test@gmail.com',
                'password' => '12345678',
                'password_confirmation' => '12345678',
            ]
        );
        $response->assertStatus(302);
        $response->assertRedirect('/home');
    }

    public function test_user_tidak_dapat_register_karena_password_kurang_dari_8_karakter()
    {
        $response =
            $response = $this->post(
                '/register',
                [
                    'name' => 'test',
                    'email' => 'test@gmail.com',
                    'password' => '123',
                    'password_confirmation' => '123',
                ],
                [
                    'Referer' => '/register',
                ],
            );
        $response->assertStatus(302);
        $response->assertRedirect('/register');
        $response->assertSessionHasErrors('password');
    }

    public function test_user_tidak_dapat_register_karena_password_tidak_sama()
    {
        $response = $this->post(
            '/register',
            [
                'name' => 'test',
                'email' => 'test@gmail.com',
                'password' => '12345678',
                'password_confirmation' => '87654321',
            ],
            [
                'Referer' => '/register',
            ],
        );
        $response->assertStatus(302);
        $response->assertRedirect('/register');
        $response->assertSessionHasErrors('password');
    }

    public function test_user_tidak_dapat_register_karena_email_sudah_terdaftar()
    {
        User::create([
            'email' => 'test@gmail.com',
            'name' => 'test',
            'password' => '12345678',
        ]);
        $response = $this->post(
            '/register',
            [
                'name' => 'test',
                'email' => 'test@gmail.com',
                'password' => '12345678',
                'password_confirmation' => '12345678',
            ],
            [
                'Referer' => '/register',
            ],
        );
        $response->assertStatus(302);
        $response->assertRedirect('/register');
        $response->assertSessionHasErrors('email');
    }

    public function test_user_dapat_login()
    {
        User::create([
            'email' => 'test@gmail.com',
            'name' => 'test',
            'password' => bcrypt('12345678'),
        ]);

        $response = $this->post(
            '/login',
            [
                'email' => 'test@gmail.com',
                'password' => '12345678',
                'url' => route('home'),
            ]
        );
        $this->assertAuthenticated();
        $response->assertStatus(302);
        $response->assertRedirect('/home');
    }

    public function test_user_tidak_dapat_login_karena_email_salah()
    {
        User::create([
            'email' => 'test@gmail.com',
            'name' => 'test',
            'password' => bcrypt('12345678'),
        ]);

        $response = $this->post(
            '/login',
            [
                'email' => 'test1@gmail.com',
                'password' => '12345678',
            ],
            [
                'Referer' => '/login',
            ],
        );
        $response->assertStatus(302);
        $response->assertRedirect('/login');
        $response->assertSessionHasErrors('email');
    }

    public function test_user_tidak_dapat_login_karena_password_salah()
    {
        User::create([
            'email' => 'test@gmail.com',
            'name' => 'test',
            'password' => bcrypt('12345678'),
        ]);

        $response = $this->post(
            '/login',
            [
                'email' => 'test1@gmail.com',
                'password' => '123456789',
            ],
            [
                'Referer' => '/login',
            ],
        );
        $response->assertStatus(302);
        $response->assertRedirect('/login');
    }

    public function test_user_dapat_logout()
    {
        $user = User::create([
            'email' => 'test@gmail.com',
            'name' => 'test',
            'password' => bcrypt('12345678'),
        ]);

        $this->actingAs($user);

        $response = $this->post('/logout');
        $this->assertGuest();
        $response->assertStatus(302);
        $response->assertRedirect('/');
    }
}
