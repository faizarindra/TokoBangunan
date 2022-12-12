<?php

namespace App\Http\Controllers;

use App\Models\Notifikasi;
use App\Models\Pembayaran;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Midtrans\Notification;
use Midtrans\Config;

class CallbackController extends Controller
{

    public function updateTransaksi($kode_pembayaran, $pembayaran, $id_transaksi, $transaksi)
    {
        Pembayaran::where('kode', $kode_pembayaran)->update(['status' => $pembayaran]);
        Transaksi::whereIn('id', $id_transaksi)->update(['status' => $transaksi]);
    }

    public function sendNotif($type, $id, $pesan, $icon, $bg_color, $text_color)
    {
        Notifikasi::create([
            'type' => 'user',
            'id_user' => $type == 'user' ? $id : null,
            'id_toko' => $type == 'toko' ? $id : null,
            'pesan' => $pesan,
            'icon' => $icon,
            'bg_color' => $bg_color,
            'text_color' => $text_color,
        ]);
    }

    public function callback_midtrans(Request $request)
    {
        Config::$serverKey = config('midtrans.server_key');
        $notif = new Notification();

        $transaction = $notif->transaction_status;
        $fraud = $notif->fraud_status;

        $pembayaran = Pembayaran::where('kode', $notif->order_id)->first();
        $id_items = Transaksi::where('id_pembayaran', $pembayaran->id)->get();
        $id_items = $id_items->map(
            function ($item) {
                return $item->id;
            }
        );

        error_log("Order ID $notif->order_id: " . "transaction status = $transaction, fraud staus = $fraud");
        //status = 0:waiting, 1:sukses, 2:processing, 3:cancel, 4:expired, 5:waiting payment
        if ($transaction == 'pending') {
            $this->updateTransaksi($notif->order_id, 4, $id_items, 0);
            $this->sendNotif('user', $pembayaran->id_user, 'Pembayaran ' . $pembayaran->kode . ' sedang diproses', 'fa fa-money', 'primary', 'text-white');
        } else if ($transaction == 'settlement') {
            $this->updateTransaksi($notif->order_id, 1, $id_items, 1);
            $this->sendNotif('user', $pembayaran->id_user, 'Pembayaran ' . $pembayaran->kode . ' telah dibayar', 'fa fa-money', 'success', 'text-white');
            $transaksi = Transaksi::where('id_pembayaran', $pembayaran->id)->get();
            foreach ($transaksi as $item) {
                $this->sendNotif('user', $item->id_user, 'Pesanan dengan kode ' . $item->kode . ' telah dibayar', 'fa fa-shopping-bag', 'primary', 'text-white');
            }
        } else if ($transaction == 'cancel') {
            $this->updateTransaksi($notif->order_id, 2, $id_items, 5);
            $this->sendNotif('user', $pembayaran->id_user, 'Pembayaran ' . $pembayaran->kode . ' telah dibatalkan', 'fa fa-money', 'danger', 'text-white');
            $transaksi = Transaksi::where('id_pembayaran', $pembayaran->id)->get();
            foreach ($transaksi as $item) {
                $this->sendNotif('user', $item->id_user, 'Pesanan dengan kode ' . $item->kode . ' telah dibatalkan', 'fa fa-shopping-bag', 'danger', 'text-white');
            }
        } else if ($transaction == 'deny') {
            $this->updateTransaksi($notif->order_id, 2, $id_items, 5);
            $this->sendNotif('user', $pembayaran->id_user, 'Pembayaran ' . $pembayaran->kode . ' telah ditolak', 'fa fa-money', 'danger', 'text-white');
            $transaksi = Transaksi::where('id_pembayaran', $pembayaran->id)->get();
            foreach ($transaksi as $item) {
                $this->sendNotif('user', $item->id_user, 'Pesanan dengan kode ' . $item->kode . ' telah ditolak', 'fa fa-shopping-bag', 'danger', 'text-white');
            }
        } else if ($transaction == 'expire') {
            $this->updateTransaksi($notif->order_id, 3, $id_items, 6);
            $this->sendNotif('user', $pembayaran->id_user, 'Pembayaran ' . $pembayaran->kode . ' telah kadaluarsa', 'fa fa-money', 'danger', 'text-white');
            $transaksi = Transaksi::where('id_pembayaran', $pembayaran->id)->get();
            foreach ($transaksi as $item) {
                $this->sendNotif('user', $item->id_user, 'Pesanan dengan kode ' . $item->kode . ' telah kadaluarsa', 'fa fa-shopping-bag', 'danger', 'text-white');
            }
        }
    }
}
