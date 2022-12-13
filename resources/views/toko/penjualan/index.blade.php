@extends('toko.layouts.main')

@section('title', 'Pesanan')

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
                    <h3 class="mb-0"> Penjualan</h3>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <table class="table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Kode Pesanan</th>
                            <th>Tanggal Pesanan</th>
                            <th>Total Harga</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($penjualans as $penjualan)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $penjualan->kode }}</td>
                                <td>@date($penjualan->created_at)</td>
                                <td>@currency($penjualan->total)</td>
                                <td>
                                    @if ($penjualan->status == 0)
                                        <span class="badge badge-warning">Menunggu Pembayaran</span>
                                    @elseif ($penjualan->status == 1)
                                        <span class="badge badge-info">Pesanan Dibayar</span>
                                    @elseif($penjualan->status == 2)
                                        <span class="badge badge-primary">Pesanan Diproses Toko</span>
                                    @elseif ($penjualan->status == 3)
                                        <span class="badge badge-secondary">Pesanan Dikirim</span>
                                    @elseif ($penjualan->status == 4)
                                        <span class="badge badge-success">Pesanan Selesai</span>
                                    @elseif ($penjualan->status == 5)
                                        <span class="badge badge-danger">Pesanan Dibatalkan</span>
                                    @elseif ($penjualan->status == 6)
                                        <span class="badge badge-danger">Pesanan Kadaluarsa</span>
                                    @elseif ($penjualan->status == 7)
                                        <span class="badge badge-danger">Pesanan Ditolak Toko</span>
                                    @endif
                                </td>
                                <td class="text-right">
                                    @if ($penjualan->status == 1)
                                        <form action="{{ url('seller/penjualan/acc') }}" method="POST"
                                            id="form-acc-{{ $penjualan->id }}">
                                            @csrf
                                            <input type="hidden" name="id" value="{{ $penjualan->id }}">
                                        </form>
                                        <form action="{{ url('seller/penjualan/tolak') }}" method="POST"
                                            id="form-tolak-{{ $penjualan->id }}">
                                            @csrf
                                            <input type="hidden" name="id" value="{{ $penjualan->id }}">
                                        </form>
                                        <a onclick="event.preventDefault(); document.getElementById('form-acc-{{ $penjualan->id }}').submit();"
                                            class="btn btn-sm btn-primary btn-rounded">Setujui</a>
                                        <a onclick="event.preventDefault(); document.getElementById('form-tolak-{{ $penjualan->id }}').submit();"
                                            class="btn btn-sm btn-danger btn-rounded">Tolak</a>
                                    @endif
                                    @if ($penjualan->status == 2)
                                        <form action="{{ url('seller/penjualan/kirim') }}" method="POST"
                                            id="form-kirim-{{ $penjualan->id }}">
                                            @csrf
                                            <input type="hidden" name="id" value="{{ $penjualan->id }}">
                                        </form>
                                        <a onclick="event.preventDefault(); document.getElementById('form-kirim-{{ $penjualan->id }}').submit();"
                                            class="btn btn-sm btn-primary btn-rounded">Kirim Barang</a>
                                    @endif
                                    <a href="{{ url('profile/pesanan?detail=' . $penjualan->id) }}"
                                        class="btn btn-sm btn-warning btn-rounded">Detail</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">Belum ada pesanan</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="ps-pagination">
            {{ $penjualans->links() }}
        </div>
    </div>
@endsection
