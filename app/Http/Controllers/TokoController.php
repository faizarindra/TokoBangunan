<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use App\Models\Produk;
use App\Models\Toko;
use App\Models\Ulasan;
use Illuminate\Http\Request;

class TokoController extends Controller
{
    public function getToko($prefix)
    {
        $toko = Toko::where('prefix', $prefix)->first();
        $products = Produk::where('id_toko', $toko->id)->paginate(8);
        $products->map(function ($product) {
            $product['reviews'] = round(Ulasan::where('id_produk', $product->id)->avg('star'), 1);
        });
        $total_product = Produk::where('id_toko', $toko->id)->count();
        $categories = Kategori::select('kategori.id', 'kategori.nama_kategori', 'kategori.prefix', 'produk.id_kategori')
            ->join('produk', 'kategori.id', '=', 'produk.id_kategori')
            ->groupBy('id', 'nama_kategori', 'prefix', 'produk.id_kategori')
            ->get();
        $reviews = round(Ulasan::where('id_toko', $toko->id)
            ->avg('star'), 1);
        $total_review = Ulasan::where('id_toko', $toko->id)->count();
        return view('toko', compact('toko', 'products', 'categories', 'reviews', 'total_product', 'total_review'));
    }
}
