<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use App\Models\Merk;
use App\Models\Produk;
use App\Models\Toko;
use App\Models\Ulasan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class TokoController extends Controller
{
    public function __construct()
    {
        $this->RajaOngkirKey = env('RAJAONGKIR_KEY');
        $this->RajaOngkirUrl = env('RAJAONGKIR_URL');
    }

    public function getToko($prefix)
    {
        $toko = Toko::where('prefix', $prefix)->first();
        $products = Produk::where('id_toko', $toko->id)->paginate(8);
        $products->map(function ($product) {
            $product['reviews'] = round(Ulasan::where('id_produk', $product->id)->avg('star'), 1);
        });
        $total_product = Produk::where('id_toko', $toko->id)->count();
        $categories = Kategori::select('kategori.id', 'kategori.nama_kategori', 'kategori.prefix', 'produk.id_kategori')
            ->join('produk', 'kategori.id', '=', 'produk.id_kategori')
            ->groupBy('id', 'nama_kategori', 'prefix', 'produk.id_kategori')
            ->get();
        $reviews = round(Ulasan::where('id_toko', $toko->id)
            ->avg('star'), 1);
        $total_review = Ulasan::where('id_toko', $toko->id)->count();
        return view('toko', compact('toko', 'products', 'categories', 'reviews', 'total_product', 'total_review'));
    }

    public function checkToko()
    {
        $id = Auth::user()->id;
        $toko = Toko::where('id_user', $id)->first();
        if ($toko) {
            return redirect()->route('dashboard-toko');
        } else {
            return redirect()->route('buat-toko');
        }
    }

    public function dashboardToko()
    {
        $id = Auth::user()->id;
        $toko = Toko::where('id_user', $id)->first();
        $total_product = Produk::where('id_toko', $toko->id)->count();
        $total_pesanan = 0;
        $total_review = Ulasan::where('id_toko', $toko->id)->count();
        return view('toko.dashboard', compact('toko', 'total_product', 'total_pesanan', 'total_review'));
    }

    public function produk()
    {
        $id = Auth::user()->id;
        $toko = Toko::where('id_user', $id)->first();
        $products = Produk::where('id_toko', $toko->id)->paginate(8);
        $products->map(function ($product) {
            $product['reviews'] = round(Ulasan::where('id_produk', $product->id)->avg('star'), 1);
            $product['countReviews'] = Ulasan::where('id_produk', $product->id)->count();
        });
        return view('toko.produk.index', compact('toko', 'products'));
    }

    public function tambahProduk()
    {
        $id = Auth::user()->id;
        $toko = Toko::where('id_user', $id)->first();
        $categories = Kategori::all();
        return view('toko.produk.tambah', compact('toko', 'categories'));
    }

    public function tambahProdukAction(Request $request)
    {
        $id = Auth::user()->id;
        $toko = Toko::where('id_user', $id)->first();
        $request->validate(
            [
                'nama_produk' => 'required',
                'harga_produk' => 'required',
                'stok_raw' => 'required',
                'satuan_produk' => 'required',
                'deskripsi' => 'required',
                'gambar_produk' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:10240',
                'id_kategori' => 'required',
                'id_merk' => 'required',
            ],
            [
                'nama_produk.required' => 'Nama produk harus diisi',
                'harga_produk.required' => 'Harga produk harus diisi',
                'stok_raw.required' => 'Stok produk harus diisi',
                'satuan_produk.required' => 'Satuan produk harus diisi',
                'deskripsi.required' => 'Deskripsi produk harus diisi',
                'gambar_produk.required' => 'Gambar produk harus diisi',
                'gambar_produk.image' => 'Gambar produk harus berupa gambar',
                'gambar_produk.mimes' => 'Gambar produk harus berupa gambar dengan format jpeg, png, jpg, gif, svg',
                'gambar_produk.max' => 'Gambar produk maksimal berukuran 10MB',
                'id_kategori.required' => 'Kategori produk harus diisi',
                'id_merk.required' => 'Merk produk harus diisi',
            ]
        );
        $imageName = time() . '.' . $request->gambar_produk->extension();
        $request->gambar_produk->move(public_path('img/produk'), $imageName);
        $product = new Produk();
        $product->nama_produk = $request->nama_produk;
        $product->harga_produk = $request->harga_produk;
        $product->stok_raw = $request->stok_raw;
        $product->satuan_produk = $request->satuan_produk;
        $product->deskripsi = $request->deskripsi;
        $product->id_kategori = $request->id_kategori;
        $product->id_merk = $request->id_merk;
        $product->id_toko = $toko->id;
        $product->id_user = $id;
        $product->gambar_produk = $imageName;
        $product->prefix =  Str::random(5) . '-' . strtolower(str_replace(' ', '-', $request->nama_produk));
        $product->save();
        return redirect()->route('produk-toko')->with('success', 'Produk berhasil ditambahkan');
    }

    public function editProduk($id)
    {
        $id = Crypt::decrypt($id);
        $id_user = Auth::user()->id;
        $toko = Toko::where('id_user', $id_user)->first();
        $product = Produk::find($id);
        $categories = Kategori::all();
        $merks = Merk::all();
        return view('toko.produk.edit', compact('toko', 'product', 'categories', 'merks'));
    }

    public function editProdukAction(Request $request)
    {
        $id_product = $request->id;
        $id = Auth::user()->id;
        $toko = Toko::where('id_user', $id)->first();
        $request->validate(
            [
                'nama_produk' => 'required',
                'harga_produk' => 'required',
                'stok_raw' => 'required',
                'satuan_produk' => 'required',
                'deskripsi' => 'required',
                'gambar_produk' => 'image|mimes:jpeg,png,jpg,gif,svg|max:10240',
                'id_kategori' => 'required',
                'id_merk' => 'required',
            ],
            [
                'nama_produk.required' => 'Nama produk harus diisi',
                'harga_produk.required' => 'Harga produk harus diisi',
                'stok_raw.required' => 'Stok produk harus diisi',
                'satuan_produk.required' => 'Satuan produk harus diisi',
                'deskripsi.required' => 'Deskripsi produk harus diisi',
                'gambar_produk.image' => 'Gambar produk harus berupa gambar',
                'gambar_produk.mimes' => 'Gambar produk harus berupa gambar dengan format jpeg, png, jpg, gif, svg',
                'gambar_produk.max' => 'Gambar produk maksimal berukuran 10MB',
                'id_kategori.required' => 'Kategori produk harus diisi',
                'id_merk.required' => 'Merk produk harus diisi',
            ]
        );
        $product = Produk::find($id_product);
        if ($request->gambar_produk) {
            $imageName = time() . '.' . $request->gambar_produk->extension();
            $request->gambar_produk->move(public_path('img/produk'), $imageName);
            $product->gambar_produk = $imageName;
        }
        $product->nama_produk = $request->nama_produk;
        $product->harga_produk = $request->harga_produk;
        $product->stok_raw = $request->stok_raw;
        $product->satuan_produk = $request->satuan_produk;
        $product->deskripsi = $request->deskripsi;
        $product->id_kategori = $request->id_kategori;
        $product->id_merk = $request->id_merk;
        $product->id_toko = $toko->id;
        $product->id_user = $id;
        $product->save();
        return redirect()->route('produk-toko')->with('success', 'Produk berhasil diubah');
    }

    public function hapusProduk($id)
    {
        $product = Produk::find($id);
        $product->delete();
        return redirect()->route('produk-toko')->with('success', 'Produk berhasil dihapus');
    }

    public function buatToko()
    {
        $toko = Toko::where('id_user', Auth::user()->id)->first();
        if ($toko) {
            return redirect()->route('dashboard-toko');
        }
        $response = Http::withHeaders([
            'key' => $this->RajaOngkirKey
        ])->get($this->RajaOngkirUrl . 'province');
        $data = json_decode($response->body(), false);
        $provinsis = $data->rajaongkir->results;
        return view('toko.toko.buat', compact('provinsis'));
    }

    public function buatTokoAction(Request $request)
    {
        $id = Auth::user()->id;
        $request->validate(
            [
                'nama_toko' => 'required',
                'deskripsi_toko' => 'required',
                'alamat_toko' => 'required',
                'provinsi' => 'required',
                'kota' => 'required',
                'kecamatan' => 'required',
                'gambar_toko' => 'image|mimes:jpeg,png,jpg,gif,svg|max:10240',
            ],
            [
                'nama_toko.required' => 'Nama toko harus diisi',
                'deskripsi_toko.required' => 'Deskripsi toko harus diisi',
                'alamat_toko.required' => 'Alamat toko harus diisi',
                'provinsi.required' => 'Provinsi toko harus diisi',
                'kota.required' => 'Kota toko harus diisi',
                'kecamatan.required' => 'Kecamatan toko harus diisi',
                'gambar_toko.image' => 'Gambar toko harus berupa gambar',
                'gambar_toko.mimes' => 'Gambar toko harus berupa gambar dengan format jpeg, png, jpg, gif, svg',
                'gambar_toko.max' => 'Gambar toko maksimal berukuran 10MB',
            ]
        );
        $toko = new Toko();
        if ($request->gambar_toko) {
            $imageName = time() . '.' . $request->gambar_toko->extension();
            $request->gambar_toko->move(public_path('img/toko'), $imageName);
            $toko->gambar_toko = $imageName;
        }
        $toko->nama_toko = $request->nama_toko;
        $toko->alamat_toko = $request->alamat_toko;
        $toko->deskripsi_toko = $request->deskripsi_toko;
        $toko->latitude = 0;
        $toko->longitude = 0;
        $toko->prefix = Str::random(5);
        $toko->id_provinsi = explode('#', $request->provinsi)[0];
        $toko->nama_provinsi = explode('#', $request->provinsi)[1];
        $toko->id_kota = explode('#', $request->kota)[0];
        $toko->nama_kota = explode('#', $request->kota)[1];
        $toko->id_kecamatan = explode('#', $request->kecamatan)[0];
        $toko->nama_kecamatan = explode('#', $request->kecamatan)[1];
        $toko->id_user = $id;
        $toko->save();
        return redirect()->route('dashboard-toko')->with('success', 'Toko berhasil dibuat');
    }

    public function editToko()
    {
        $id = Auth::user()->id;
        $toko = Toko::where('id_user', $id)->first();
        $response = Http::withHeaders([
            'key' => $this->RajaOngkirKey
        ])->get($this->RajaOngkirUrl . 'province');
        $data = json_decode($response->body(), false);
        $provinsis = $data->rajaongkir->results;
        $response = Http::withHeaders([
            'key' => $this->RajaOngkirKey
        ])->get($this->RajaOngkirUrl . 'city?province=' . $toko->id_provinsi);
        $data = json_decode($response->body(), false);
        $kotas = $data->rajaongkir->results;
        $response = Http::withHeaders([
            'key' => $this->RajaOngkirKey
        ])->get($this->RajaOngkirUrl . 'subdistrict?city=' . $toko->id_kota);
        $data = json_decode($response->body(), false);
        $kecamatans = $data->rajaongkir->results;
        return view('toko.toko.edit', compact('toko', 'provinsis', 'kotas', 'kecamatans'));
    }

    public function editTokoAction(Request $request)
    {
        $id = Auth::user()->id;
        $toko = Toko::where('id_user', $id)->first();
        $request->validate(
            [
                'nama_toko' => 'required',
                'deskripsi_toko' => 'required',
                'alamat_toko' => 'required',
                'provinsi' => 'required',
                'kota' => 'required',
                'kecamatan' => 'required',
                'gambar_toko' => 'image|mimes:jpeg,png,jpg,gif,svg|max:10240',
            ],
            [
                'nama_toko.required' => 'Nama toko harus diisi',
                'deskripsi_toko.required' => 'Deskripsi toko harus diisi',
                'alamat_toko.required' => 'Alamat toko harus diisi',
                'provinsi.required' => 'Provinsi toko harus diisi',
                'kota.required' => 'Kota toko harus diisi',
                'kecamatan.required' => 'Kecamatan toko harus diisi',
                'gambar_toko.image' => 'Gambar toko harus berupa gambar',
                'gambar_toko.mimes' => 'Gambar toko harus berupa gambar dengan format jpeg, png, jpg, gif, svg',
                'gambar_toko.max' => 'Gambar toko maksimal berukuran 10MB',
            ]
        );
        if ($request->gambar_toko) {
            $imageName = time() . '.' . $request->gambar_toko->extension();
            $request->gambar_toko->move(public_path('img/toko'), $imageName);
            $toko->gambar_toko = $imageName;
        }
        $toko->nama_toko = $request->nama_toko;
        $toko->deskripsi_toko = $request->deskripsi_toko;
        $toko->alamat_toko = $request->alamat_toko;
        $toko->latitude = 0;
        $toko->longitude = 0;
        $toko->prefix = Str::random(5);
        $toko->id_provinsi = explode('#', $request->provinsi)[0];
        $toko->nama_provinsi = explode('#', $request->provinsi)[1];
        $toko->id_kota = explode('#', $request->kota)[0];
        $toko->nama_kota = explode('#', $request->kota)[1];
        $toko->id_kecamatan = explode('#', $request->kecamatan)[0];
        $toko->nama_kecamatan = explode('#', $request->kecamatan)[1];
        $toko->id_user = $id;
        $toko->save();
        return redirect()->back()->with('success', 'Toko berhasil diubah');
    }
}
