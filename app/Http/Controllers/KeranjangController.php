<?php

namespace App\Http\Controllers;

use App\Models\Alamat;
use App\Models\Keranjang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KeranjangController extends Controller
{
    public function tambahKeranjang(Request $request)
    {
        $auth = Auth::user();
        if (!$auth) {
            return redirect()->route('login');
        }

        $cek = Keranjang::where('id_user', $auth->id)->where('id_produk', $request->id_produk)->first();
        if ($cek) {
            //remove
            Keranjang::where('id_user', $auth->id)->where('id_produk', $request->id_produk)->update(['qty' => $cek->qty + 1]);
        } else {
            //create
            Keranjang::create([
                'id_user' => $auth->id,
                'id_produk' => $request->id_produk,
                'qty' => 1
            ]);
        }

        return redirect()->back();
    }

    public function tambahKeranjangApi(Request $request)
    {
        $auth = $request->id_user;
        if (!$auth) {
            // return redirect()->route('login');
            return response()->json([
                'success' => false,
                'msg' => 'Unauthorized',
                'data' => []
            ]);
        }

        $cek = Keranjang::where('id_user', $auth)->where('id_produk', $request->id_produk)->first();
        if ($cek) {
            //remove
            Keranjang::where('id_user', $auth)->where('id_produk', $request->id_produk)->update(['qty' => $cek->qty + 1]);
        } else {
            //create
            Keranjang::create([
                'id_user' => $auth,
                'id_produk' => $request->id_produk,
                'qty' => 1
            ]);
        }

        $carts = \App\Models\Keranjang::select('keranjang.*', 'produk.*', 'toko.nama_toko as nama_toko')
            ->where('keranjang.id_user', $auth)
            ->join('produk', 'keranjang.id_produk', 'produk.id')
            ->join('toko', 'produk.id_toko', 'toko.id')
            ->get();
        $carts = $carts->map(function ($crt) {
            $crt['url_gambar'] = asset('img/produk/' . $crt->gambar_produk);
            $crt['url_produk'] = url('produk/' . $crt->prefix);
            return $crt;
        });

        $cartNum = \App\Models\Keranjang::where('keranjang.id_user', $auth)
            ->join('produk', 'keranjang.id_produk', 'produk.id')
            ->get();
        $cartNum = $cartNum->map(function ($crt) {
            $produk = \App\Models\Produk::where('id', $crt->id_produk)->first();
            $crt['total'] = $produk->harga_produk * $crt->qty;
            return $crt;
        });

        return response()->json([
            'success' => true,
            'msg' => 'success',
            'data' => ['cart' => $carts, 'cartNum' => $cartNum]
        ]);
    }

    public function getKeranjang()
    {
        $auth = Auth::user();
        if (!$auth) {
            return redirect()->route('login');
        }

        $carts = \App\Models\Keranjang::select('keranjang.*', 'produk.*', 'toko.nama_toko as nama_toko')
            ->where('keranjang.id_user', $auth->id)
            ->join('produk', 'keranjang.id_produk', 'produk.id')
            ->join('toko', 'produk.id_toko', 'toko.id')
            ->get();
        $carts = $carts->map(function ($crt) {
            $crt['url_gambar'] = asset('img/produk/' . $crt->gambar_produk);
            $crt['url_produk'] = url('produk/' . $crt->prefix);
            return $crt;
        });

        $cartNum = \App\Models\Keranjang::where('keranjang.id_user', $auth->id)
            ->join('produk', 'keranjang.id_produk', 'produk.id')
            ->get();
        $cartNum = $cartNum->map(function ($crt) {
            $produk = \App\Models\Produk::where('id', $crt->id_produk)->first();
            $crt['total'] = $produk->harga_produk * $crt->qty;
            return $crt;
        });

        return view('keranjang', compact('carts', 'cartNum'));
    }

    public function hapusKeranjang(Request $request)
    {
        $keranjang = Keranjang::destroy($request->id_keranjang);
        $auth = $request->id_user;
        if ($keranjang) {
            $carts = \App\Models\Keranjang::select('keranjang.*', 'produk.*', 'toko.nama_toko as nama_toko')
                ->where('keranjang.id_user', $auth)
                ->join('produk', 'keranjang.id_produk', 'produk.id')
                ->join('toko', 'produk.id_toko', 'toko.id')
                ->get();
            $carts = $carts->map(function ($crt) {
                $crt['url_gambar'] = asset('img/produk/' . $crt->gambar_produk);
                $crt['url_produk'] = url('produk/' . $crt->prefix);
                return $crt;
            });

            $cartNum = \App\Models\Keranjang::where('keranjang.id_user', $auth)
                ->join('produk', 'keranjang.id_produk', 'produk.id')
                ->get();
            $cartNum = $cartNum->map(function ($crt) {
                $produk = \App\Models\Produk::where('id', $crt->id_produk)->first();
                $crt['total'] = $produk->harga_produk * $crt->qty;
                return $crt;
            });
            return response()->json([
                'success' => true,
                'msg' => 'success',
                'data' => ['cart' => $carts, 'cartNum' => $cartNum]
            ]);
        } else {
            return response()->json([
                'success' => false,
                'msg' => 'error tidak ketahui',
                'data' => []
            ]);
        }
    }

    public function index()
    {
        $auth = Auth::user();
        $carts = Keranjang::select('toko.id as id_toko', 'toko.nama_toko as nama_toko', 'toko.gambar_toko as gambar_toko')
            ->where('keranjang.id_user', $auth->id)
            ->join('produk', 'keranjang.id_produk', 'produk.id')
            ->join('toko', 'produk.id_toko', 'toko.id')
            ->groupBy('toko.id')
            ->get();
        $carts = $carts->map(function ($crt) {
            $crt['products'] = Keranjang::select('keranjang.*', 'keranjang.id as id_keranjang', 'produk.*')
                ->where('produk.id_toko', $crt->id_toko)
                ->join('produk', 'keranjang.id_produk', 'produk.id')
                ->get();
            $crt['products'] = $crt['products']->map(function ($prd) {
                $prd['total'] = $prd->harga_produk * $prd->qty;
                return $prd;
            });
            return $crt;
        });
        $alamats = Alamat::where('id_user', $auth->id)->get();
        return view('keranjang', compact('carts', 'alamats'));
    }
}
