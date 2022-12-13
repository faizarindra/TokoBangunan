@extends('profile.layouts.main')

@section('title', 'Pesanan')

@section('content_user')
    <div class="ps-shopping-product">
        <div class="ps-form--account-setting">
            <div class="ps-form__header">
                @if (session()->has('success'))
                    <div class="alert alert-warning py-3">
                        {{ session('success') }}
                    </div>
                @endif
                <div class="d-flex flex-row align-items-center mb-4">
                    <h3 class="mb-0"> Pesanan</h3>
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
                        @forelse ($pesanans as $pesanan)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $pesanan->kode }}</td>
                                <td>@date($pesanan->created_at)</td>
                                <td>@currency($pesanan->total)</td>
                                <td>
                                    @if ($pesanan->status == 0)
                                        <span class="badge badge-warning">Menunggu Pembayaran</span>
                                    @elseif ($pesanan->status == 1)
                                        <span class="badge badge-info">Pesanan Dibayar</span>
                                    @elseif($pesanan->status == 2)
                                        <span class="badge badge-primary">Pesanan Diproses Toko</span>
                                    @elseif ($pesanan->status == 3)
                                        <span class="badge badge-secondary">Pesanan Dikirim</span>
                                    @elseif ($pesanan->status == 4)
                                        <span class="badge badge-success">Pesanan Selesai</span>
                                    @elseif ($pesanan->status == 5)
                                        <span class="badge badge-danger">Pesanan Dibatalkan</span>
                                    @elseif ($pesanan->status == 6)
                                        <span class="badge badge-danger">Pesanan Kadaluarsa</span>
                                    @elseif ($pesanan->status == 7)
                                        <span class="badge badge-danger">Pesanan Ditolak Toko</span>
                                    @endif
                                </td>
                                <td class="text-right">
                                    @if ($pesanan->status == 3)
                                        <form action="{{ url('profile/pesanan/selesai') }}" method="POST"
                                            id="pesanan-selesai-{{ $pesanan->id }}">
                                            @csrf
                                            <input type="hidden" name="id" value="{{ $pesanan->id }}">
                                        </form>
                                        <a onclick="event.preventDefault(); document.getElementById('pesanan-selesai-{{ $pesanan->id }}').submit();"
                                            class="btn btn-sm btn-primary btn-rounded">Pesanan Diterima</a>
                                    @endif
                                    <a href="{{ url('profile/pesanan?detail=' . $pesanan->id) }}"
                                        class="btn btn-sm btn-danger btn-rounded">Detail</a>
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
            {{ $pesanans->links() }}
        </div>
    </div>
@endsection
