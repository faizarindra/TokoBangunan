@extends('profile.layouts.main')

@section('title', 'Alamat Saya')

@section('content_user')
    <div class="ps-shopping-product">
        <div class="ps-form--account-setting">
            <div class="ps-form__header">
                @if (session()->has('success'))
                    <div class="alert alert-warning py-3">
                        {{ session('success') }}
                    </div>
                @endif
                <div class="d-flex flex-row align-items-center mb-3">
                    <h3 class="mb-0"> Alamat Saya</h3>
                    <a href="{{ url('profile/tambah-alamat') }}" class="ml-4 badge badge-pill px-4 py-2"
                        style="background-color: #dd2400;color:white;font-size:12px"> +
                        Tambah</a>
                </div>
            </div>
        </div>
        <div class="row">
            @forelse ($alamats as $alamat)
                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-6 col-6">
                    <div class="ps-alamat">
                        <div class="ps-alamat__container">
                            <div class="ps-alamat__content"
                                style="border:1px solid silver;padding:20px;max-height:200px;height:200px">
                                <div class="d-flex align-items-center justify-content-between">
                                    <span>{{ $alamat->nama_penerima }}</span>
                                    @if ($alamat->is_main == 1)
                                        <span class="ml-3 badge badge-pill badge-warning">Utama</span>
                                    @endif
                                </div>
                                <br>
                                <span>{{ $alamat->no_hp }}</span>
                                <hr>
                                <p class="ps-alamat__price sale">
                                    {{ $alamat->alamat }}, {{ $alamat->nama_kecamatan }}, {{ $alamat->nama_kota }},
                                    {{ $alamat->nama_provinsi }}, {{ $alamat->kode_pos }}
                                </p>
                            </div>
                            <div class="ps-alamat__content hover">
                                <a href="{{ url('profile/alamat/' . Crypt::encrypt($alamat->id)) }}"
                                    class="badge badge-pill px-4 py-2 mr-2 badge-warning"
                                    style="color:white;font-size:12px">Ubah</a>
                                <form id="hapus-alamat-{{ $alamat->id }}"
                                    action="{{ url('/profile/alamat/' . $alamat->id) }}" method="POST"
                                    style="display: none;">
                                    @csrf
                                    @method('DELETE')
                                </form>
                                <a onclick="event.preventDefault(); document.getElementById('hapus-alamat-{{ $alamat->id }}').submit();"
                                    class="badge badge-pill px-4 py-2"
                                    style="background-color: #dd2400;color:white;font-size:12px;cursor:pointer;"><i
                                        class="fa fa-trash"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="ps-pagination">
                    {{ $alamats->links() }}
                </div>
            @empty
                <div class="alert alert-warning">
                    Anda belum memiliki alamat
                </div>
            @endforelse
        </div>
    </div>
@endsection
