<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use App\Models\Merk;
use App\Models\Produk;
use App\Models\Ulasan;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $categories = Kategori::take(6)->get();
        $categories = $categories->map(function ($category) {
            $category['merks'] = Merk::where('id_kategori', $category->id)->take(4)->get();
            return $category;
        });
        $products = Produk::inRandomOrder()->limit(5)->get();
        $products->map(function ($product) {
            $product['reviews'] = round(Ulasan::where('id_produk', $product->id)->avg('star'), 1);
        });
        $allProducts = Produk::all();
        $allProducts->map(function ($product) {
            $product['reviews'] = round(Ulasan::where('id_produk', $product->id)->avg('star'), 1);
        });
        $brands = Merk::take(10)->get();
        return view('home', compact('categories', 'products', 'brands', 'allProducts'));
    }
}
