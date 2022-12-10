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
Route::get('/keranjang', [\App\Http\Controllers\KeranjangController::class, 'index'])->name('keranjang');
Route::prefix('/profile')->middleware('auth')->group(function () {
    Route::get('/', [\App\Http\Controllers\ProfileController::class, 'index']);
    Route::get('/notifikasi', [\App\Http\Controllers\ProfileController::class, 'notifikasi']);
    Route::get('/pesanan', [\App\Http\Controllers\ProfileController::class, 'pesanan']);
    Route::get('/alamat', [\App\Http\Controllers\AlamatController::class, 'index'])->name('alamat-user');
    Route::get('/tambah-alamat', [\App\Http\Controllers\AlamatController::class, 'tambahAlamat']);
    Route::post('/tambah-alamat', [\App\Http\Controllers\AlamatController::class, 'tambahAlamatAction']);
    Route::get('/alamat/{id}', [\App\Http\Controllers\AlamatController::class, 'editAlamat']);
    Route::patch('/alamat', [\App\Http\Controllers\AlamatController::class, 'editAlamatAction']);
    Route::delete('/alamat/{id}', [\App\Http\Controllers\AlamatController::class, 'hapusAlamat']);
    Route::get('/terakhir-dilihat', [\App\Http\Controllers\ProfileController::class, 'terakhirDilihat']);
    Route::patch('/user', [\App\Http\Controllers\ProfileController::class, 'updateUser']);
    Route::patch('/photo', [\App\Http\Controllers\ProfileController::class, 'updatePhotoProfile']);
});

Route::prefix('/seller')->middleware('auth')->group(function () {
    Route::get('/', [\App\Http\Controllers\TokoController::class, 'checkToko']);
    Route::get('/buat-toko', [\App\Http\Controllers\TokoController::class, 'buatToko'])->name('buat-toko');
    Route::post('/buat-toko', [\App\Http\Controllers\TokoController::class, 'buatTokoAction']);
    Route::middleware('cek.toko')->group(function () {
        Route::get('/dashboard', [\App\Http\Controllers\TokoController::class, 'dashboardToko'])->name('dashboard-toko');
        Route::get('/edit-toko', [\App\Http\Controllers\TokoController::class, 'editToko']);
        Route::patch('/edit-toko', [\App\Http\Controllers\TokoController::class, 'editTokoAction']);
        Route::get('/produk', [\App\Http\Controllers\TokoController::class, 'produk'])->name('produk-toko');
        Route::get('/tambah-produk', [\App\Http\Controllers\TokoController::class, 'tambahProduk']);
        Route::post('/tambah-produk', [\App\Http\Controllers\TokoController::class, 'tambahProdukAction']);
        Route::get('/produk/{id}', [\App\Http\Controllers\TokoController::class, 'editProduk']);
        Route::patch('/produk', [\App\Http\Controllers\TokoController::class, 'editProdukAction']);
        Route::delete('/produk/{id}', [\App\Http\Controllers\TokoController::class, 'hapusProduk']);
        Route::get('/pesanan', [\App\Http\Controllers\TokoController::class, 'pesanan']);
        Route::get('/pesanan/{id}', [\App\Http\Controllers\TokoController::class, 'detailPesanan']);
        Route::patch('/pesanan/{id}', [\App\Http\Controllers\TokoController::class, 'updatePesanan']);
    });
});
