<?php

namespace App\Http\Controllers;

use App\Models\Alamat;
use App\Models\Keranjang;
use App\Models\Notifikasi;
use App\Models\Pembayaran;
use App\Models\Produk;
use App\Models\Toko;
use App\Models\Transaksi;
use App\Models\TransaksiItem;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Nette\Utils\Random;
use Nullix\CryptoJsAes\CryptoJsAes;
use App\Services\Midtrans\CreateSnapTokenService;

class TransaksiController extends Controller
{
    public function __construct()
    {
        $this->status = [
            0 => 'Menunggu Pembayaran',
            1 => 'Dibayar',
            2 => 'Diproses Toko',
            3 => 'Dikirim Ke Alamat',
            4 => 'Selesai',
            5 => 'Dibatalkan',
            6 => 'Pembayaran Kadaluarsa',
            7 => 'Ditolak Toko'
        ];

        $this->statusMidtrans = [
            'pending' => '0',
            'settlement' => '1',
            'capture' => '1',
            'deny' => '2',
            'cancel' => '2',
            'expire' => '3',
            'refund' => '2',
        ];

        $this->midtransParsing = [
            'pending' => 'Menunggu Pembayaran',
            'settlement' => 'Dibayar',
            'capture' => 'Dibayar',
            'deny' => 'Dibatalkan',
            'cancel' => 'Dibatalkan',
            'expire' => 'Pembayaran Kadaluarsa',
            'refund' => 'Dibatalkan',
        ];
        \Midtrans\Config::$serverKey = config('midtrans.server_key');
    }

    public static function getMidtransStatus($string)
    {
        $status = [
            'pending' => 'Menunggu Pembayaran',
            'settlement' => 'Dibayar',
            'capture' => 'Dibayar',
            'deny' => 'Dibatalkan',
            'cancel' => 'Dibatalkan',
            'expire' => 'Pembayaran Kadaluarsa',
            'refund' => 'Dibatalkan',
        ];
        $result = $status[$string];
        return $result;
    }

    public static function getStatusPembayaran($string)
    {
        $status = [
            0 => 'Menunggu Pembayaran',
            1 => 'Dibayar',
            2 => 'Diproses Toko',
            3 => 'Dikirim Ke Alamat',
            4 => 'Selesai',
            5 => 'Dibatalkan',
            6 => 'Pembayaran Kadaluarsa'
        ];
        $result = $status[$string];
        return $result;
    }


    public function createTransaction(Request $request)
    {
        $request = (object)$request->json()->all();
        $data = $request->data;
        $data = json_decode(json_encode($data));
        $data = (collect($data));
        $pembayaran = Pembayaran::create([
            'id_user' => $data[0]->id_user,
            'status' => 0,
            'kode' => 'MTR-' . Random::generate(5, '0-9A-Z')
        ]);

        Notifikasi::create([
            'type' => 'user',
            'id_user' => $data[0]->id_user,
            'pesan' => 'Pembayaran ' . $pembayaran->kode . ' berhasil dibuat',
            'icon' => 'fa fa-money',
            'bg_color' => 'info',
            'text_color' => 'text-white'
        ]);
        Keranjang::whereIn('id', $request->keranjang)->delete();
        $data = $data->map(
            function ($item) use ($pembayaran) {
                $kode = 'TBL-' . Random::generate(5, '0-9A-Z');
                $transaksi = Transaksi::create([
                    'id_user' => $item->id_user,
                    'id_alamat' => $item->id_alamat,
                    'id_toko' => $item->id_toko,
                    'id_pembayaran' => $pembayaran->id,
                    'ongkir' => $item->ongkir,
                    'total_harga' => $item->total_harga,
                    'total' => $item->total,
                    'kode' => $kode,
                ]);

                Notifikasi::create([
                    'type' => 'user',
                    'id_user' => $item->id_user,
                    'pesan' => 'Pesanan dengan kode ' . $kode . ' berhasil dibuat',
                    'icon' => 'fa fa-shopping-bag',
                    'bg_color' => 'warning',
                    'text_color' => 'text-dark'
                ]);

                Notifikasi::create([
                    'type' => 'toko',
                    'id_toko' => $item->id_toko,
                    'pesan' => 'Pesanan dengan kode ' . $kode . ' berhasil dibuat',
                    'icon' => 'fa fa-shopping-bag',
                    'bg_color' => 'warning',
                    'text_color' => 'text-dark'
                ]);

                // $id_keranjang = $item->id_keranjang;
                // Keranjang::where('id', $id_keranjang)->delete();

                $item->products = collect($item->products);
                $item->products->map(
                    function ($item) use ($transaksi) {
                        $id_transaksi = $transaksi->id;
                        TransaksiItem::create([
                            'id_transaksi' => $id_transaksi,
                            'id_produk' => $item->id_produk,
                            'qty' => $item->qty,
                            'harga' => $item->harga,
                            'total' => $item->total,
                        ]);
                    }
                );

                $transaksis = Transaksi::where('id', $transaksi->id)->first();
                $transaksis->products = TransaksiItem::where('id_transaksi', $transaksi->id)->get();
                return $transaksis;
            }
        );
        $data = array(
            'pembayaran' => $pembayaran->id,
            'encrypt_pembayaran' => Crypt::encryptString($pembayaran->id),
            'data' => $data
        );
        return WilayahController::customResponse(true, 'Success', $data);
    }

    public function pembayaran2(Request $request)
    {
        $data = $request->data;
        $explode = explode('TBL', $data);
        $ct = $explode[0];
        $iv = $explode[1];
        $s = $explode[2];
        $encrypted = '{"ct":"' . $ct . '","iv":"' . $iv . '","s":"' . $s . '"}';
        $password = env('APP_KEY');
        $decrypted = CryptoJsAes::decrypt($encrypted, $password);
        // $decrypted = json_decode($decrypted);
        // echo $decrypted;
        print_r($decrypted, false);
    }

    public function pesan(Request $request)
    {
        $id = Crypt::decryptString($request->data);
        $pembayaran = Pembayaran::where('id', $id)->first();
        $user = User::where('id', $pembayaran->id_user)->first();
        $transaksis = Transaksi::where('id_pembayaran', $pembayaran->id)->get();
        $alamat = Alamat::where('id', $transaksis[0]->id_alamat)->first();
        $id_items = Transaksi::where('id_pembayaran', $pembayaran->id)->get();
        $id_items = $id_items->map(
            function ($item) {
                return $item->id;
            }
        );
        $data_pesanans = TransaksiItem::whereIn('id_transaksi', $id_items)->get();
        $data_pesanans = $data_pesanans->map(
            function ($item) {
                $produk = Produk::where('id', $item->id_produk)->first();
                $item->nama_produk = $produk->nama_produk;
                $item->prefix = $produk->prefix;
                $item->nama_toko = Produk::join('toko', 'produk.id_toko', '=', 'toko.id')
                    ->select('toko.nama_toko')
                    ->where('produk.id', $item->id_produk)
                    ->first()->nama_toko;
                return $item;
            }
        );
        $pesanans = new Collection();
        $pesanans->data = $data_pesanans;
        $pesanans->total = $data_pesanans->sum('total');
        $transaksis = $transaksis->map(
            function ($item) {
                $item->nama_toko = Toko::where('id', $item->id_toko)->first()->nama_toko;
                $item->products = TransaksiItem::where('id_transaksi', $item->id)->get();
                $item->products = $item->products->map(
                    function ($item) {
                        $produk = Produk::where('id', $item->id_produk)->first();
                        $item->nama_produk = $produk->nama_produk;
                        $item->prefix = $produk->prefix;
                        return $item;
                    }
                );
                return $item;
            }
        );
        $transaksis->data = $transaksis;
        $transaksis->ongkir = $transaksis->sum('ongkir');

        $total_bayar = $pesanans->total + $transaksis->ongkir;

        $snap_token = null;
        if ($pembayaran->snap_token != null) {
            $snap_token = Pembayaran::where('id', $id)->first()->snap_token;
        }

        $data = array(
            'pembayaran' => $pembayaran,
            'alamat' => $alamat,
            'pesanan' => $pesanans,
            'transaksis' => $transaksis,
            'total_bayar' => $total_bayar,
        );

        // return response()->json($data);

        $midtrans = null;
        if ($pembayaran->status != 0) {
            $midtrans = \Midtrans\Transaction::status($pembayaran->kode);
        }

        return view('pembayaran', compact('pembayaran', 'alamat', 'transaksis', 'user', 'pesanans', 'total_bayar', 'snap_token', 'midtrans'));
    }

    public function createToken(Request $request)
    {
        $id = Crypt::decryptString($request->id);
        $pembayaran = Pembayaran::where('id', $id)->first();
        $user = User::where('id', $pembayaran->id_user)->first();
        $transaksis = Transaksi::where('id_pembayaran', $pembayaran->id)->get();
        $id_items = Transaksi::where('id_pembayaran', $pembayaran->id)->get();
        $id_items = $id_items->map(
            function ($item) {
                return $item->id;
            }
        );
        $data_pesanans = TransaksiItem::whereIn('id_transaksi', $id_items)->get();
        $data_pesanans = $data_pesanans->map(
            function ($item) {
                $produk = Produk::where('id', $item->id_produk)->first();
                $item->nama_produk = $produk->nama_produk;
                $item->prefix = $produk->prefix;
                $item->nama_toko = Produk::join('toko', 'produk.id_toko', '=', 'toko.id')
                    ->select('toko.nama_toko')
                    ->where('produk.id', $item->id_produk)
                    ->first()->nama_toko;
                return $item;
            }
        );
        $pesanans = new Collection();
        $pesanans->data = $data_pesanans;
        $pesanans->total = $data_pesanans->sum('total');
        $transaksis = $transaksis->map(
            function ($item) {
                $item->nama_toko = Toko::where('id', $item->id_toko)->first()->nama_toko;
                $item->products = TransaksiItem::where('id_transaksi', $item->id)->get();
                $item->products = $item->products->map(
                    function ($item) {
                        $produk = Produk::where('id', $item->id_produk)->first();
                        $item->nama_produk = $produk->nama_produk;
                        $item->prefix = $produk->prefix;
                        return $item;
                    }
                );
                return $item;
            }
        );
        $transaksis->data = $transaksis;
        $transaksis->ongkir = $transaksis->sum('ongkir');

        $total_bayar = $pesanans->total + $transaksis->ongkir;

        $customer = $user;

        $order = new Collection();
        $order->kode = $pembayaran->kode;
        $order->total = $total_bayar;

        $product = $pesanans->data;
        $product = $product->map(
            function ($item) {
                $product = [
                    'id' => $item->id_produk,
                    'name' => $item->nama_produk,
                    'price' => $item->harga,
                    'quantity' => $item->qty,
                ];
                return $product;
            }
        );
        $product->push(
            array(
                'id' => 0,
                'name' => 'Ongkos Kirim',
                'price' => $transaksis->ongkir,
                'quantity' => 1,
            )
        );

        $snap_token = $pembayaran->snap_token;
        if ($snap_token == null) {
            $midtrans = new CreateSnapTokenService($order, $product, $customer);
            $snapToken = $midtrans->getSnapToken();

            $pembayaran->snap_token = $snapToken;
            $pembayaran->save();
        }

        $newSnap = Pembayaran::where('id', $id)->first()->snap_token;

        return response()->json(array('snap_token' => $newSnap));
    }
}
