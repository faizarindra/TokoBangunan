@extends('layouts.app')

@section('title', 'Pembayaran')

@section('content')
    <div class="ps-checkout ps-section--shopping">
        <div class="container">
            <div class="ps-section__header">
                <h1>Review Pesanan</h1>
            </div>
            <div class="ps-section__content">
                <form class="ps-form--checkout">
                    <div class="row">
                        <div class="col-xl-7 col-lg-8 col-md-12 col-sm-12  ">
                            <div class="ps-form__billing-info">
                                <h3 class="ps-form__heading">Data Pembeli</h3>
                                <div class="form-group">
                                    <label>Nama
                                    </label>
                                    <div class="form-group__content">
                                        <input class="form-control" type="text" readonly value="{{ $user->name }}">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>No HP
                                    </label>
                                    <div class="form-group__content">
                                        <input class="form-control" type="text" readonly value="{{ $user->no_telp }}">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Email
                                    </label>
                                    <div class="form-group__content">
                                        <input class="form-control" type="text" readonly value="{{ $user->email }}">
                                    </div>
                                </div>
                            </div>
                            <div class="ps-form__billing-info">
                                <h3 class="ps-form__heading">Data Alamat Antar</h3>
                                <div class="form-group">
                                    <label>Nama Penerima
                                    </label>
                                    <div class="form-group__content">
                                        <input class="form-control" type="text" readonly
                                            value="{{ $alamat->nama_penerima }}">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>No HP Penerima
                                    </label>
                                    <div class="form-group__content">
                                        <input class="form-control" type="text" readonly value="{{ $alamat->no_hp }}">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Alamat Lengkap
                                    </label>
                                    <div class="form-group__content">
                                        <textarea class="form-control" readonly rows="5">{{ $alamat->alamat }}, Kecamatan {{ $alamat->nama_kecamatan }},{{ $alamat->nama_kota }}, Provinsi {{ $alamat->nama_provinsi }}</textarea>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Kode POS
                                    </label>
                                    <div class="form-group__content">
                                        <input class="form-control" type="text" readonly value="{{ $alamat->kode_pos }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-5 col-lg-4 col-md-12 col-sm-12  ">
                            <div class="ps-form__total">
                                <h3 class="ps-form__heading">Pesanan Anda</h3>
                                <div class="content">
                                    <div class="ps-block--checkout-total">
                                        <div class="ps-block__header">
                                            <p>Produk</p>
                                            <p>Total</p>
                                        </div>
                                        <div class="ps-block__content">
                                            <table class="table ps-block__products">
                                                <tbody>
                                                    @foreach ($pesanans->data as $p)
                                                        <tr>
                                                            <td>
                                                                <a href="{{ url('produk/' . $p->prefix) }}">
                                                                    {{ $p->nama_produk }} ×{{ $p->qty }}
                                                                </a>
                                                                <p>Dijual oleh:<strong>{{ $p->nama_toko }}</strong>
                                                                </p>
                                                            </td>
                                                            <td class="text-right">@currency($p->total)</td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                            <h4 class="ps-block__title d-flex align-items-center justify-content-between"
                                                style="padding-right:.75rem">
                                                Subtotal <span>@currency($pesanans->total)</span>
                                            </h4>
                                            <div class="ps-block__shippings">
                                                @foreach ($transaksis->data as $transaksi)
                                                    <figure>
                                                        <h4>{{ $transaksi->nama_toko }}</h4>
                                                        <p>Ongkir: @currency($transaksi->ongkir)</p>
                                                        @foreach ($transaksi->products as $produk)
                                                            <a href="{{ url('produk/' . $produk->prefix) }}">
                                                                {{ $produk->nama_produk }} ×{{ $produk->qty }}</a>
                                                            <br>
                                                        @endforeach
                                                    </figure>
                                                @endforeach
                                            </div>
                                            <h4 class="mt-3 ps-block__title d-flex align-items-center justify-content-between"
                                                style="padding-right:.75rem">
                                                Total Ongkir <span>@currency($transaksis->ongkir)</span>
                                            </h4>
                                            <h3 class="d-flex align-items-center justify-content-between"
                                                style="padding-right:.75rem">Total
                                                <span>@currency($total_bayar)</span>
                                            </h3>
                                            @if ($midtrans)
                                                <div class="row">
                                                    <div class="col-12 col-md-6">
                                                        Metode Pembayaran
                                                    </div>
                                                    <div class="col-12 col-md-6 text-right">
                                                        @if ($midtrans->payment_type == 'bank' || $midtrans->payment_type == 'bank_transfer')
                                                            Transfer Bank
                                                        @else
                                                            {{ $midtrans->payment_type }}
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-12 col-md-6">
                                                        BANK
                                                    </div>
                                                    <div class="col-12 col-md-6 text-right">
                                                        @if ($midtrans->payment_type == 'bank' || $midtrans->payment_type == 'bank_transfer')
                                                            {{ strtoupper($midtrans->va_numbers[0]->bank) }}
                                                        @elseif ($midtrans->payment_type == 'echannel')
                                                            Mandiri
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-12 col-md-6">
                                                        No VA
                                                    </div>
                                                    <div class="col-12 col-md-6 text-right font-weight-bold">
                                                        @if ($midtrans->payment_type == 'bank' || $midtrans->payment_type == 'bank_transfer')
                                                            {{ $midtrans->va_numbers[0]->va_number }}
                                                        @elseif ($midtrans->payment_type == 'echannel')
                                                            {{ $midtrans->bill_key }}
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-12 col-md-6">
                                                        Status Pembayaran
                                                    </div>
                                                    <div class="col-12 col-md-6 text-right">
                                                        {{ App\Http\Controllers\TransaksiController::getMidtransStatus($midtrans->transaction_status) }}
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-12 col-md-6">
                                                        Status Transaksi
                                                    </div>
                                                    <div class="col-12 col-md-6 text-right">
                                                        {{ App\Http\Controllers\TransaksiController::getStatusPembayaran($pembayaran->status) }}
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    </div><button class="ps-btn ps-btn--fullwidth" id="button-bayar">BAYAR</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('customScript')
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}">
    </script>
    <script>
        const payButton = document.querySelector('#button-bayar');

        const showSnap = (token) => {
            snap.pay(token, {
                // Optional
                onSuccess: function(result) {
                    /* You may add your own js here, this is just example */
                    // document.getElementById('result-json').innerHTML += JSON.stringify(result, null, 2);
                    console.log(result)
                    location.reload();
                },
                // Optional
                onPending: function(result) {
                    /* You may add your own js here, this is just example */
                    // document.getElementById('result-json').innerHTML += JSON.stringify(result, null, 2);
                    console.log(result)
                    location.reload();
                },
                // Optional
                onError: function(result) {
                    /* You may add your own js here, this is just example */
                    // document.getElementById('result-json').innerHTML += JSON.stringify(result, null, 2);
                    console.log(result)
                    location.reload();
                },
                onClose: function(result) {
                    location.reload();
                }
            });
        }

        const callApi = () => {
            $.ajax({
                url: "{{ url('api/token') }}",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    id: "{{ request()->data }}"
                },
                beforeSend: function() {
                    $('#button-bayar').attr('disabled', true);
                },
                success: function(data) {
                    console.log(data)
                    showSnap(data.snap_token)
                    $('#button-bayar').attr('disabled', false);
                },
                error: function(data) {
                    console.log(data)
                }
            });
        }

        payButton.addEventListener('click', function(e) {
            e.preventDefault();
            let snap_token = '{{ $snap_token }}';
            if (snap_token != '') {
                showSnap(snap_token)
            } else {
                callApi()
            }
        });
    </script>
@endpush
