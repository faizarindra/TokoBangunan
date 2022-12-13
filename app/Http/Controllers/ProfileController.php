<?php

namespace App\Http\Controllers;

use App\Models\Notifikasi;
use App\Models\Pembayaran;
use App\Models\Saldo;
use App\Models\TerakhirDilihat;
use App\Models\Transaksi;
use App\Models\TransaksiItem;
use App\Models\Ulasan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class ProfileController extends Controller
{

    private function complete_percentage($model, $table_name, $resource)
    {
        $pos_info =  DB::select(DB::raw("SHOW COLUMNS FROM " . $table_name . " WHERE FIELD != 'image'"));
        $base_columns = count($pos_info) - 3;
        $not_null = -3;
        foreach ($pos_info as $col) {
            $not_null += app('App\\' . $model)::selectRaw('SUM(CASE WHEN ' . $col->Field . ' IS NOT NULL THEN 1 ELSE 0 END) AS not_null')->where('id', '=', $resource->id)->first()->not_null;
        }

        $percent = round(($not_null / $base_columns) * 100);
        $null = $base_columns - $not_null;
        return [
            'percent' => $percent,
            'null' => $null,
            'base_columns' => $base_columns
        ];
    }

    public function index()
    {
        $auth = Auth::user();
        $percentage = $this->complete_percentage('Models\\User', 'users', $auth);
        return view('profile.index', compact('auth', 'percentage'));
    }

    public function updateUser(Request $request)
    {
        $auth = Auth::user();
        $user = User::find($auth->id);
        if (!$request->password) {
            unset($request['password']);
        } else {
            $request['password'] = bcrypt($request['password']);
        }
        $user->update($request->all());
        return redirect()->back()->with('success', 'Berhasil mengubah profile');
    }

    public function updatePhotoProfile(Request $request)
    {
        $auth = Auth::user();
        $user = User::find($auth->id);
        //create upload function
        $image = $request->file('image');
        if ($image) {
            $name = time() . '.' . $image->getClientOriginalExtension();
            $destinationPath = public_path('/img/pp/');
            $image->move($destinationPath, $name);
            $msg = 'Berhasil mengubah foto profile';
        } else {
            File::delete(public_path('/img/pp/' . $user->image));
            $name = '';
            $msg = 'Berhasil menghapus foto profile';
        }
        $user->image = $name;
        $user->save();
        return redirect()->back()->with('success', $msg);
    }

    public function terakhirDilihat()
    {
        $auth = Auth::user();
        $terakhir_dilihats = TerakhirDilihat::select('terakhir_dilihat.*', 'produk.*', 'toko.nama_toko as nama_toko')
            ->where('terakhir_dilihat.id_user', $auth->id)
            ->join('produk', 'produk.id', '=', 'terakhir_dilihat.id_produk')
            ->join('toko', 'toko.id', '=', 'produk.id_toko')
            ->orderBy('terakhir_dilihat.created_at', 'desc')
            ->get();
        $terakhir_dilihats->map(function ($product) {
            $product['reviews'] = round(Ulasan::where('id_produk', $product->id_produk)->avg('star'), 1);
            $product['countReviews'] = Ulasan::where('id_produk', $product->id_produk)->count();
        });
        return view('profile.terakhir_dilihat', compact('terakhir_dilihats', 'auth'));
    }

    public function notifikasi()
    {
        $auth = Auth::user();
        $notifikasis = Notifikasi::where('id_user', $auth->id)
            ->where('type', 'user')
            ->orderBy('id', 'desc')
            ->paginate(10);
        return view('profile.notifikasi', compact('notifikasis', 'auth'));
    }

    public function pesanan()
    {
        $auth = Auth::user();
        $pesanans = Transaksi::where('id_user', $auth->id)
            ->orderBy('id', 'desc')
            ->paginate(10);
        return view('profile.pesanan.index', compact('pesanans', 'auth'));
    }

    public function pesananDetail($id)
    {
        $auth = Auth::user();
        $pesanan = Transaksi::where('id_user', $auth->id)
            ->where('id', $id)
            ->first();
        $products = TransaksiItem::where('id_transaksi', $pesanan->id)
            ->get();
        return view('profile.pesanan.detail', compact('pesanan', 'auth', 'products'));
    }

    public function pembayaran()
    {
        $auth = Auth::user();
        $pembayarans = Pembayaran::where('id_user', $auth->id)
            ->orderBy('id', 'desc')
            ->paginate(10);
        $pembayarans = $pembayarans->map(function ($pembayaran) {
            $transaksi = Transaksi::where('id_pembayaran', $pembayaran->id)->get();
            $pembayaran->total = $transaksi->sum('total');
            $pembayaran->id_hash = Crypt::encryptString($pembayaran->id);
            return $pembayaran;
        });
        return view('profile.pembayaran', compact('auth', 'pembayarans'));
    }

    public function selesaiPesanan(Request $request)
    {
        $transaksi = Transaksi::find($request->id);
        $transaksi->status = 4;
        $transaksi->save();

        $saldo = Saldo::where('id_toko', $transaksi->id_toko)->first();
        $saldo->status = 1;
        $saldo->save();
        return redirect()->back()->with('success', 'Pesanan telah diterima');
    }
}
