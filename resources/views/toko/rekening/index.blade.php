@extends('toko.layouts.main')

@section('title', 'Rekening')

@section('content_toko')
    <div class="ps-shopping-product">
        <div class="ps-form--account-setting">
            <div class="ps-form__header">
                @if (session()->has('success'))
                    <div class="alert alert-warning py-3">
                        {{ session('success') }}
                    </div>
                @endif
                <div class="d-flex flex-row align-items-center mb-3">
                    <h3 class="mb-5"> Rekening Penjual</h3>
                    <a href="{{ url('seller/tambah-rekening') }}" class="ml-4 badge badge-pill px-4 py-2 mb-5"
                        style="background-color: #dd2400;color:white;font-size:12px"> +
                        Tambah</a>
                </div>
            </div>
        </div>
        <div class="row">
            @forelse ($rekenings as $rekening)
                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-6 col-6">
                    <div class="ps-alamat">
                        <div class="ps-alamat__container">
                            <div class="ps-alamat__content"
                                style="border:1px solid silver;padding:20px;max-height:200px;height:200px">
                                <div class="d-flex align-items-center justify-content-center mt-3">
                                    <img src="{{ asset('img/bank/' . $rekening->logo) }}" alt=""
                                        style="width:50px;height:50px;object-fit:contain">
                                </div>
                                <hr>
                                <span>Bank {{ $rekening->nama }}</span>
                                <br>
                                <span>{{ $rekening->no_rekening }} a/n {{ $rekening->atas_nama }}</span>
                                <br>
                                <span>Cabang {{ $rekening->cabang }}</span>
                            </div>
                            <div class="ps-alamat__content hover">
                                <a href="{{ url('seller/rekening/' . Crypt::encrypt($rekening->id)) }}"
                                    class="badge badge-pill px-4 py-2 mr-2 badge-warning"
                                    style="color:white;font-size:12px">Ubah</a>
                                <form id="hapus-rekening-{{ $rekening->id }}"
                                    action="{{ url('seller/rekening/' . $rekening->id) }}" method="POST"
                                    style="display: none;">
                                    @csrf
                                    @method('DELETE')
                                </form>
                                <a onclick="event.preventDefault(); document.getElementById('hapus-rekening-{{ $rekening->id }}').submit();"
                                    class="badge badge-pill px-4 py-2"
                                    style="background-color: #dd2400;color:white;font-size:12px;cursor:pointer;"><i
                                        class="fa fa-trash"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="ps-pagination">
                    {{ $rekenings->links() }}
                </div>
            @empty
                <div class="col-md-12">
                    <div class="alert alert-danger text-center" role="alert">
                        <i class="fa fa-exclamation-triangle mr-2"></i> Belum ada rekening yang disimpan
                    </div>
                </div>
            @endforelse
        </div>
    </div>
@endsection
