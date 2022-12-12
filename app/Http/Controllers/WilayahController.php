<?php

namespace App\Http\Controllers;

use App\Models\Alamat;
use App\Models\Produk;
use App\Models\Toko;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class WilayahController extends Controller
{
    public function __construct()
    {
        $this->RajaOngkirKey = env('RAJAONGKIR_KEY');
        $this->RajaOngkirUrl = env('RAJAONGKIR_URL');
    }

    public static function customResponse($status, $message, $data = null)
    {
        return response()->json([
            'status' => $status,
            'message' => $message,
            'data' => $data
        ]);
    }

    public function getProvinsi(Request $request)
    {
        $response = Http::withHeaders([
            'key' => $this->RajaOngkirKey
        ])->get($this->RajaOngkirUrl . 'province');
        $data = json_decode($response->body(), false);
        $provinsis = $data->rajaongkir->results;
        $data = array_map(function ($provinsi) {
            return [
                'id' => $provinsi->province_id,
                'nama' => $provinsi->province
            ];
        }, $provinsis);
        return $this->customResponse(true, 'Berhasil mengambil data provinsi', $data);
    }

    public function getKota($id)
    {
        $response = Http::withHeaders([
            'key' => $this->RajaOngkirKey
        ])->get($this->RajaOngkirUrl . 'city?province=' . $id);
        $data = json_decode($response->body(), false);
        $data = $data->rajaongkir->results;
        $data = array_map(function ($item) {
            return [
                'id' => $item->city_id,
                'nama' => $item->type . ' ' . $item->city_name,
                'kode_pos' => $item->postal_code
            ];
        }, $data);
        return $this->customResponse(true, 'Berhasil mengambil data kota', $data);
    }

    public function getKecamatan($id)
    {
        $response = Http::withHeaders([
            'key' => $this->RajaOngkirKey
        ])->get($this->RajaOngkirUrl . 'subdistrict?city=' . $id);
        $data = json_decode($response->body(), false);
        $data = $data->rajaongkir->results;
        $data = array_map(function ($item) {
            return [
                'id' => $item->subdistrict_id,
                'nama' => $item->subdistrict_name
            ];
        }, $data);
        return $this->customResponse(true, 'Berhasil mengambil data kecamatan', $data);
    }

    public function getOngkir(Request $request)
    {
        $request = (object) $request->json()->all();
        $dataKeranjang = $request->data;
        $dataKeranjang = json_decode(json_encode($dataKeranjang));
        $keranjang = $request->keranjang;
        $id_user = $request->id_user;
        $id_alamat = $request->id_alamat;
        $dataKeranjang = array_map(function ($item) use ($id_alamat, $id_user) {
            $alamat_asal = Alamat::where('id', $id_alamat)->first();
            $district_toko = Toko::where('id', $item->id_toko)->first();
            $sum_products = array_sum(array_map(function ($item) {
                return $item->qty;
            }, $item->products));
            $sum_harga = array_sum(array_map(function ($item) {
                return $item->harga;
            }, $item->products));
            $response = Http::withHeaders([
                'key' => $this->RajaOngkirKey
            ])->post($this->RajaOngkirUrl . 'cost', [
                'origin' => $alamat_asal->id_kecamatan,
                'originType' => 'subdistrict',
                'destination' => $district_toko->id_kecamatan,
                'destinationType' => 'subdistrict',
                'weight' => 1000,
                'courier' => 'jnt'
            ]);
            $data = json_decode($response->body(), false);
            $data = $data->rajaongkir->results;
            $products = collect($item->products);
            return [
                'id_user' => $id_user,
                'id_toko' => $district_toko->id,
                'id_alamat' => (int)$id_alamat,
                'nama_toko' => $district_toko->nama_toko,
                'ongkir' => $data[0]->costs[0]->cost[0]->value,
                'total_harga' => $sum_harga,
                'total' => $sum_harga + $data[0]->costs[0]->cost[0]->value,
                'products' => $products->map(function ($item) {
                    return [
                        'id_produk' => $item->id_produk,
                        'qty' => $item->qty,
                        'harga' => $item->harga,
                        'total' => $item->qty * $item->harga
                    ];
                })
            ];
        }, $dataKeranjang);

        $dataKeranjang = array(
            'keranjang' => $keranjang,
            'data' => $dataKeranjang,
        );
        return $this->customResponse(true, 'Berhasil mengambil data ongkir', $dataKeranjang);
    }
}
