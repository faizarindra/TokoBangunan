<?php

namespace App\Http\Controllers;

use App\Models\Alamat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Http;

class AlamatController extends Controller
{
    public function __construct()
    {
        $this->RajaOngkirKey = env('RAJAONGKIR_KEY');
        $this->RajaOngkirUrl = env('RAJAONGKIR_URL');
    }

    public function index()
    {
        $auth = Auth::user();
        $alamats = Alamat::where('id_user', $auth->id)->paginate(6);
        return view('profile.alamat.index', compact('auth', 'alamats'));
    }
    public function tambahAlamat()
    {
        $auth = Auth::user();
        $response = Http::withHeaders([
            'key' => $this->RajaOngkirKey
        ])->get($this->RajaOngkirUrl . 'province');
        $data = json_decode($response->body(), false);
        $provinsis = $data->rajaongkir->results;
        return view('profile.alamat.tambah', compact('auth', 'provinsis'));
    }
    public function tambahAlamatAction(Request $request)
    {
        $auth = Auth::user();
        $is_main = $request->has('is_main') ? 1 : 0;
        $request->validate([
            'nama_penerima' => 'required',
            'no_hp' => 'required',
            'provinsi' => 'required',
            'kota' => 'required',
            'kecamatan' => 'required',
            'alamat' => 'required',
            'kode_pos' => 'required',
        ], [
            'nama_penerima.required' => 'Nama penerima harus diisi',
            'no_hp.required' => 'Nomor HP harus diisi',
            'provinsi.required' => 'Provinsi harus diisi',
            'kota.required' => 'Kota harus diisi',
            'kecamatan.required' => 'Kecamatan harus diisi',
            'alamat.required' => 'Alamat harus diisi',
            'kode_pos.required' => 'Kode pos harus diisi',
        ]);
        $mainAlamat = Alamat::where('id_user', $auth->id)->where('is_main', 1)->first();
        if ($mainAlamat && $is_main == 1) {
            Alamat::where('id_user', $auth->id)->where('is_main', 1)->update(['is_main' => 0]);
        }
        $request['id_user'] = $auth->id;
        $request['id_provinsi'] = explode('#', $request->provinsi)[0];
        $request['nama_provinsi'] = explode('#', $request->provinsi)[1];
        $request['id_kota'] = explode('#', $request->kota)[0];
        $request['nama_kota'] = explode('#', $request->kota)[1];
        $request['id_kecamatan'] = explode('#', $request->kecamatan)[0];
        $request['nama_kecamatan'] = explode('#', $request->kecamatan)[1];
        $request['is_main'] = $is_main;
        Alamat::create($request->all());
        return redirect()->route('alamat-user')->with('success', 'Berhasil menambah alamat');
    }
    public function editAlamat($id)
    {
        $id = Crypt::decrypt($id);
        $auth = Auth::user();
        $alamat = Alamat::where('id', $id)->first();
        $response = Http::withHeaders([
            'key' => $this->RajaOngkirKey
        ])->get($this->RajaOngkirUrl . 'province');
        $data = json_decode($response->body(), false);
        $provinsis = $data->rajaongkir->results;
        $response = Http::withHeaders([
            'key' => $this->RajaOngkirKey
        ])->get($this->RajaOngkirUrl . 'city?province=' . $alamat->id_provinsi);
        $data = json_decode($response->body(), false);
        $kotas = $data->rajaongkir->results;
        $response = Http::withHeaders([
            'key' => $this->RajaOngkirKey
        ])->get($this->RajaOngkirUrl . 'subdistrict?city=' . $alamat->id_kota);
        $data = json_decode($response->body(), false);
        $kecamatans = $data->rajaongkir->results;
        return view('profile.alamat.edit', compact('auth', 'alamat', 'provinsis', 'kotas', 'kecamatans'));
    }

    public function editAlamatAction(Request $request)
    {
        $id = $request->id;
        $is_main = $request->has('is_main') ? 1 : 0;
        $auth = Auth::user();
        $mainAlamat = Alamat::where('id_user', $auth->id)->where('is_main', 1)->first();
        if ($mainAlamat && $is_main == 1) {
            Alamat::where('id_user', $auth->id)->where('is_main', 1)->update(['is_main' => 0]);
        }
        $request['id_user'] = $auth->id;
        $request['id_provinsi'] = explode('#', $request->provinsi)[0];
        $request['nama_provinsi'] = explode('#', $request->provinsi)[1];
        $request['id_kota'] = explode('#', $request->kota)[0];
        $request['nama_kota'] = explode('#', $request->kota)[1];
        $request['id_kecamatan'] = explode('#', $request->kecamatan)[0];
        $request['nama_kecamatan'] = explode('#', $request->kecamatan)[1];
        $request['is_main'] = $is_main;
        unset($request['provinsi']);
        unset($request['kota']);
        unset($request['kecamatan']);
        Alamat::where('id', $id)->update($request->except(['_token', '_method']));
        return redirect()->route('alamat-user')->with('success', 'Berhasil mengubah alamat');
    }

    public function hapusAlamat($id)
    {
        $auth = Auth::user();
        $alamat = Alamat::find($id);
        $alamat->delete();
        return redirect()->back()->with('success', 'Berhasil menghapus alamat');
    }
}
