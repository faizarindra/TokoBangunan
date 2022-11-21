<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{

    public function index()
    {
        $auth = Auth::user();
        $favorites = Favorite::select('produk.*', 'toko.nama_toko', 'favorite.*', 'favorite.id as id_favorite')
            ->join('produk', 'produk.id', 'favorite.id_produk')
            ->join('toko', 'toko.id', 'produk.id_toko')
            ->where('favorite.id_user', $auth->id)
            ->get();
        return view('favorite', compact('favorites'));
    }

    public function tambahFavorite(Request $request)
    {
        $auth = Auth::user();
        if (!$auth) {
            return redirect()->route('login');
        }

        $cek = Favorite::where('id_user', $auth->id)->where('id_produk', $request->id_produk)->first();
        if ($cek) {
            //remove
            Favorite::destroy($cek->id);
        } else {
            //create
            Favorite::create([
                'id_user' => $auth->id,
                'id_produk' => $request->id_produk
            ]);
        }

        return redirect()->back();
    }

    public function deleteFavorite(Request $request)
    {
        $id = $request->id_favorite;
        Favorite::destroy($id);
        return redirect()->route('favorite');
    }
}
