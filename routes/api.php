<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/keranjang', [App\Http\Controllers\KeranjangController::class, 'tambahKeranjangApi']);
Route::delete('/keranjang', [App\Http\Controllers\KeranjangController::class, "hapusKeranjang"]);
Route::post('/cari', [App\Http\Controllers\SearchController::class, 'index'])->name('api.search');
