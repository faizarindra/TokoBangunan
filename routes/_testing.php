<?php

use App\Models\Keranjang;
use App\Models\User;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::get('/login', function () {
    Auth::login(\App\Models\User::factory()->create());
});

Route::get('/create', function () {
    $modelClass = 'App\Models\\' . request('model');
    return $modelClass::factory()->create();
});

Route::get('/create-users', function () {
    User::create([
        'name' => 'Test User',
        'email' => 'aini@gmail.com',
        'password' => bcrypt('akuimuet123'),
    ]);

    return redirect()->route('home');
});

Route::get('/delete-users', function () {
    User::orderBy('id', 'desc')->limit(1)->delete();

    return redirect()->route('home');
});

Route::get('/delete-keranjang', function () {
    Keranjang::orderBy('id', 'desc')->limit(1)->delete();

    return redirect()->route('home');
});
