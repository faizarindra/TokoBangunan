<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Models\Toko;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function index(Request $query)
    {
        $search = $query->search;
        $stores = Toko::where('nama_toko', 'LIKE', '%' . $search . '%')->get();
        $products = Produk::where('nama_produk', 'LIKE', '%' . $search . '%')->get();
        $result = [
            'success' => true,
            'stores' => $stores,
            'products' => $products
        ];
        return response()->json($result);
    }
}
