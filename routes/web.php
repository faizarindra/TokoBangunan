<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [App\Http\Controllers\HomeController::class, 'index']);

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/produk/{prefix}', [App\Http\Controllers\ProductController::class, 'detail']);
Route::get('/toko/{prefix}', [App\Http\Controllers\TokoController::class, 'getToko']);
Route::get('/favorite', [\App\Http\Controllers\FavoriteController::class, 'index'])->name('favorite');
Route::post('/favorite', [App\Http\Controllers\FavoriteController::class, 'tambahFavorite']);
Route::delete('/favorite', [\App\Http\Controllers\FavoriteController::class, 'deleteFavorite']);
Route::get('/kategori/{prefix}', [App\Http\Controllers\ProductController::class, 'getByKategori']);
Route::get('/merk/{prefix}', [App\Http\Controllers\ProductController::class, 'getByMerk']);
// Route::post('/keranjang', [App\Http\Controllers\KeranjangController::class, 'tambahKeranjang']);
