@extends('toko.layouts.main')

@section('title', 'Saldo')

@section('content_toko')
    <div class="ps-shopping-product">
        <div class="ps-form--account-setting">
            <div class="ps-form__header">
                @if (session()->has('success'))
                    <div class="alert alert-warning py-3">
                        {{ session('success') }}
                    </div>
                @endif
                <div class="d-flex flex-row align-items-center mb-4">
                    <h3 class="mb-5"> Saldo Penjual</h3>
                    <a href="{{ url('profile/tambah-alamat') }}" class="ml-4 badge badge-pill px-4 py-2 mb-5"
                        style="background-color: #dd2400;color:white;font-size:12px">
                        Cairkan ke rekening</a>
                </div>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-md-4 col-sm-4 col-12 ">
                <div class="ps-block--dashboard alert-info d-flex justify-content-between align-items-center px-5 py-4">
                    <div class="ps-block__left"><i class="icon-bag-dollar" style="font-size: 4rem"></i></div>
                    <div class="ps-block__right text-right">
                        <h4 class="text-right">@currency($saldo->tersedia)</h4>
                        <p class="mb-0">Tersedia</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 col-sm-4 col-12 ">
                <div class="ps-block--dashboard alert-success d-flex justify-content-between align-items-center px-5 py-4">
                    <div class="ps-block__left"><i class="icon-stop-circle" style="font-size: 4rem"></i></div>
                    <div class="ps-block__right text-right">
                        <h4 class="text-right">@currency($saldo->tertahan)</h4>
                        <p class="mb-0">Tertahan</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 col-sm-4 col-12 ">
                <div class="ps-block--dashboard alert-primary d-flex justify-content-between align-items-center px-5 py-4">
                    <div class="ps-block__left"><i class="icon-coin-dollar" style="font-size: 4rem"></i></div>
                    <div class="ps-block__right text-right">
                        <h4 class="text-right">@currency($saldo->dicairkan)</h4>
                        <p class="mb-0">Dicairkan</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            @forelse ($saldos as $saldo)
                <div class="col-md-12">
                    <div class="alert alert-{{ $saldo->status == 0 ? 'info' : ($saldo->status == 1 ? 'success' : 'primary') }}"
                        role="alert">
                        <div class="d-flex align-items-center">
                            <i class="icon-{{ $saldo->status == 99 ? 'coin-dollar' : 'bag-dollar' }} mr-3"
                                style="font-size: 3rem"></i></i>
                            <div class="d-flex justify-content-between align-items-center flex-grow-1">
                                <div class="d-flex flex-column">
                                    @if ($saldo->status == 99)
                                        <span>Pencairan saldo pada <b>@date($saldo->created_at)</b></span>
                                    @else
                                        <span>Kode transaksi <span
                                                class="font-weight-bold">{{ $saldo->kode }}</span></span>
                                        <span class="mt-2">status saldo
                                            <b>
                                                @if ($saldo->status == 0)
                                                    masih Tertahan
                                                @elseif ($saldo->status == 1)
                                                    telah Diberikan
                                                @elseif ($saldo->status == 2)
                                                    telah Dicairkan
                                                @endif
                                            </b> {!! $saldo->is_cair ? ' dan <b>telah Dicairkan</b>' : '' !!}</strong>
                                    @endif
                                </div>
                                @currency($saldo->nominal)
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-md-12">
                    <div class="alert alert-danger text-center" role="alert">
                        <i class="fa fa-exclamation-triangle mr-2"></i> Tidak ada saldo tersimpan
                    </div>
                </div>
            @endforelse
        </div>
    </div>
    <div class="ps-pagination">
        {{ $saldos->links() }}
    </div>
@endsection
