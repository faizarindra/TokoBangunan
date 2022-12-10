<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use App\Models\Kategori;
use Illuminate\Http\Request;
use App\Models\Produk;
use App\Models\Toko;
use App\Models\Merk;
use App\Models\TerakhirDilihat;
use App\Models\Ulasan;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    public function detail($prefix)
    {
        $product = Produk::where('prefix', $prefix)
            ->first();
        $toko = Toko::where('id', $product->id_toko)
            ->first();
        $brand = Merk::where('id', $product->id_merk)
            ->first();
        $reviews = round(Ulasan::where('id_produk', $product->id)
            ->avg('star'), 1);
        $countReviews = Ulasan::where('id_produk', $product->id)
            ->count();
        $relates = Produk::where('id_kategori', $product->id_kategori)
            ->where('prefix', '!=', $prefix)
            ->inRandomOrder()
            ->limit(6)
            ->get();
        $relates->map(function ($product) {
            $product['reviews'] = round(Ulasan::where('id_produk', $product->id)->avg('star'), 1);
        });
        $sameBrands = Produk::select('produk.*', 'toko.nama_toko', 'toko.prefix as prefix_toko')
            ->where('produk.id_merk', $product->id_merk)
            ->join('toko', 'produk.id_toko', '=', 'toko.id')
            ->where('produk.prefix', '!=', $prefix)
            ->inRandomOrder()
            ->limit(2)
            ->get();
        $sameBrands->map(function ($product) {
            $product['reviews'] = round(Ulasan::where('id_produk', $product->id)
                ->avg('star'), 1);
            $product['countReviews'] = Ulasan::where('id_produk', $product->id)
                ->count();
        });
        if (Auth::user()) {
            $favorite = Favorite::where('id_user', Auth::user()->id)->where('id_produk', $product->id)->count();
        } else {
            $favorite = 0;
        }

        //cek apakah user login
        if (Auth::user()) {
            $last = TerakhirDilihat::where('id_user', Auth::user()->id)->where('id_produk', $product->id)->count();
            if ($last == 0) {
                $total_last = TerakhirDilihat::where('id_user', Auth::user()->id)->count();
                if ($total_last == 6) {
                    TerakhirDilihat::where('id_user', Auth::user()->id)->orderBy('updated_at', 'asc')->first()->delete();
                }
                TerakhirDilihat::create([
                    'id_user' => Auth::user()->id,
                    'id_produk' => $product->id
                ]);
            } else {
                TerakhirDilihat::where('id_user', Auth::user()->id)->where('id_produk', $product->id)->update([
                    'updated_at' => now()
                ]);
            }
        }
        return view('detail', compact('product', 'toko', 'brand', 'relates', 'sameBrands', 'reviews', 'countReviews', 'favorite'));
    }

    public function getByKategori($prefix)
    {
        $products = Produk::select('produk.*', 'toko.nama_toko', 'toko.prefix as prefix_toko')
            ->join('toko', 'produk.id_toko', '=', 'toko.id')
            ->join('kategori', 'produk.id_kategori', '=', 'kategori.id')
            ->where('kategori.prefix', $prefix)
            ->paginate(12);
        $products->map(function ($product) {
            $product['reviews'] = round(Ulasan::where('id_produk', $product->id)->avg('star'), 1);
            $product['countReviews'] = Ulasan::where('id_produk', $product->id)->count();
        });
        $total_product = Produk::select('produk.*', 'toko.nama_toko', 'toko.prefix as prefix_toko')
            ->join('toko', 'produk.id_toko', '=', 'toko.id')
            ->join('kategori', 'produk.id_kategori', '=', 'kategori.id')
            ->where('kategori.prefix', $prefix)
            ->count();
        $category = Kategori::where('prefix', $prefix)->first();
        $categories = Kategori::all();
        $merks = Merk::all();
        return view('kategori', compact('products', 'total_product', 'category', 'categories', 'merks'));
    }

    public function getByMerk($prefix)
    {
        $products = Produk::select('produk.*', 'toko.nama_toko', 'toko.prefix as prefix_toko')
            ->join('toko', 'produk.id_toko', '=', 'toko.id')
            ->join('merk', 'produk.id_merk', '=', 'merk.id')
            ->where('merk.prefix', $prefix)
            ->paginate(12);
        $products->map(function ($product) {
            $product['reviews'] = round(Ulasan::where('id_produk', $product->id)->avg('star'), 1);
            $product['countReviews'] = Ulasan::where('id_produk', $product->id)->count();
        });
        $total_product = Produk::select('produk.*', 'toko.nama_toko', 'toko.prefix as prefix_toko')
            ->join('toko', 'produk.id_toko', '=', 'toko.id')
            ->join('merk', 'produk.id_merk', '=', 'merk.id')
            ->where('merk.prefix', $prefix)
            ->count();
        $merek = Merk::where('prefix', $prefix)->first();
        $categories = Kategori::all();
        $merks = Merk::all();
        return view('merk', compact('products', 'total_product', 'merek', 'categories', 'merks'));
    }

    public function getMerkAPI($id)
    {
        $merks = Merk::where('id_kategori', $id)->get();
        return WilayahController::customResponse(true, 'success get merk', $merks);
    }
}
