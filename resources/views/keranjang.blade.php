@extends('layouts.app')

@section('title', 'Keranjang')

@section('content')
    <div class="ps-section--shopping ps-shopping-cart">
        <div class="container">
            <div class="ps-section__header">
                <h1>Keranjang</h1>
            </div>
            {{-- @php
                print_r($carts);
                die();
            @endphp --}}
            <div class="ps-section__content">
                @forelse ($carts as $cart)
                    <div class="d-flex align-items-center mt-2">
                        <img src="{{ asset('img/toko/' . $cart->gambar_toko) }}" alt="" width="50px" height="50px">
                        <h5 class="ml-3">{{ $cart->nama_toko }}</h5>
                    </div>
                    <div class="table-responsive">
                        <table class="table ps-table--shopping-cart ps-table--responsive">
                            <thead>
                                <tr>
                                    <th>Nama Produk</th>
                                    <th>Harga</th>
                                    <th>Qty</th>
                                    <th>TOTAL</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody
                                @foreach ($cart->products as $product)
                                    <tr>
                                        <td data-label="Product">
                                            <div class="ps-product--cart">
                                                <input class="form-check-input" name="ctrl-product" type="checkbox" value="{{ $cart->id_toko . '#' . $product->id . '#' . $product->harga_produk . '#' . $product->qty . '#' . $product->id_keranjang }}">
                                                <div class="ps-product__thumbnail ml-3">
                                                    <a href="">
                                                        <img src="{{ asset('img/produk/' . $product->gambar_produk) }}"
                                                            alt="" height="75px" width="75">
                                                    </a>
                                                </div>
                                                <div class="ps-product__content">
                                                    <a href="{{ url('produk/' . $product->prefix) }}">
                                                        {{ $product->nama_produk }}
                                                    </a>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="price" data-label="Price">@currency($product->harga_produk)</td>
                                        <td data-label="Quantity">
                                            {{ $product->qty }}
                                        </td>
                                        <td data-label="Total">@currency($product->total)</td>
                                        <td data-label="Actions"><a href="#"><i class="icon-cross"></i></a></td>
                                    </tr> @endforeach
                                </tbody>
                        </table>
                    </div>
                @empty
                    <div class="alert alert-warning py-3">
                        Keranjang anda kosong
                    </div>
                @endforelse
            </div>
            <hr class="my-5">
            <div class="ps-section__footer">
                <div class="row">
                    <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12 col-12 ">
                        <figure>
                            <figcaption>Voucher</figcaption>
                            <div class="form-group">
                                <input class="form-control" type="text" placeholder="" readonly disabled>
                            </div>
                            <div class="form-group">
                                <button class="ps-btn ps-btn--outline text-white">Terapkan</button>
                            </div>
                        </figure>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12 col-12 ">
                        <figure>
                            <figcaption>Alamat</figcaption>
                            <div class="form-group">
                                <select class="ps-select select2-hidden-accessible" data-placeholder="All"
                                    data-select2-id="1" tabindex="-1" aria-hidden="true" id="alamat-check" disabled>
                                    <option value="0">--Pilih Alamat--</option>
                                    @foreach ($alamats as $alamat)
                                        <option value="{{ $alamat->id }}">
                                            {{ $alamat->nama_penerima }} - {{ $alamat->alamat }},
                                            {{ $alamat->nama_kecamatan }} {{ $alamat->nama_kota }},
                                            {{ $alamat->nama_provinsi }}, {{ $alamat->kode_pos }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </figure>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12 col-12 ">
                        <div class="ps-block--shopping-total">
                            <div class="ps-block__header">
                                <p>Subtotal <span class="font-weight-bold" id="subtotal">Rp. 0</span></p>
                            </div>
                            <div class="ps-block__content">
                                <ul class="ps-block__product" id="result-ongkir">
                                </ul>
                            </div>
                            <div class="ps-block__header">
                                <p>Subtotal ongkir <span class="font-weight-bold" id="subtotal-ongkir">Rp. 0</span></p>
                            </div>
                            <h3>Total <span id="total">Rp. 0</span></h3>
                        </div><button class="ps-btn ps-btn--fullwidth" id="btn-lanjut" disabled>Lanjut ke
                            pembayaran</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('customScript')
    <script>
        const encryptData = (data) => {
            let key = '{{ env('MIX_APP_KEY') }}';
            key = key.substr(7);
            console.log(key)
            let iv = CryptoJS.lib.WordArray.random(16);
            // key = CryptoJS.enc.Utf8.parse(this.key);
            let options = {
                iv: iv,
                mode: CryptoJS.mode.CBC,
                padding: CryptoJS.pad.Pkcs7,
            };
            let encrypted = CryptoJS.AES.encrypt(data, key, options);
            encrypted = encrypted.toString();
            iv = CryptoJS.enc.Base64.stringify(iv);
            let result = {
                iv: iv,
                value: encrypted,
                mac: CryptoJS.HmacSHA256(iv + encrypted, key).toString(),
            };
            result = JSON.stringify(result);
            result = CryptoJS.enc.Utf8.parse(result);
            return CryptoJS.enc.Base64.stringify(result);
        };
        const formatRupiah = (angka) => {
            if (angka == null) {
                return "Rp.-";
            }
            angka = parseInt(angka);
            angka = angka.toString();
            var number_string = angka.replace(/[^,\d]/g, "").toString(),
                split = number_string.split(","),
                sisa = split[0].length % 3,
                rupiah = split[0].substr(0, sisa),
                ribuan = split[0].substr(sisa).match(/\d{3}/gi);

            // tambahkan titik jika yang di input sudah menjadi angka ribuan
            if (ribuan) {
                let separator = sisa ? "." : "";
                rupiah += separator + ribuan.join(".");
            }

            rupiah = split[1] != undefined ? rupiah + "," + split[1] : rupiah;
            return "Rp. " + rupiah;
        };
        $(document).ready(function() {
            let arr = [];
            let subtotal_result = 0;
            let id_keranjang = [];
            let alamat_check = null;
            $('input[name="ctrl-product"]').on('change', function() {
                event.preventDefault();
                var searchIDs = $("input[name='ctrl-product']:checked").map(function() {
                    var id_toko = $(this).val().split('#')[0];
                    var id_produk = $(this).val().split('#')[1];
                    var harga = $(this).val().split('#')[2];
                    var qty = $(this).val().split('#')[3];
                    var id_keranjang = $(this).val().split('#')[4];
                    return {
                        id_toko: id_toko,
                        id_produk: id_produk,
                        harga: harga,
                        qty: qty,
                        id_keranjang: id_keranjang
                    }
                }).get();
                console.log(searchIDs);
                var keranjang = searchIDs.map(function(item) {
                    return item.id_keranjang;
                });
                console.log(keranjang);
                var result = searchIDs.reduce(function(r, a) {
                    r[a.id_toko] = r[a.id_toko] || [];
                    r[a.id_toko].push(a);
                    return r;
                }, Object.create(null));
                var result = Object.values(result);
                var result = result.map(function(item) {
                    return {
                        id_toko: item[0].id_toko,
                        products: item.map(function(item) {
                            return {
                                id_produk: item.id_produk,
                                harga: item.harga,
                                qty: item.qty
                            }
                        })
                    }
                });
                console.log(result);
                var subtotal = 0;
                result.forEach(function(item) {
                    var total = 0;
                    item.products.forEach(function(product) {
                        total += product.harga * product.qty;
                    });
                    subtotal += total;
                });
                $('#subtotal').html(formatRupiah(subtotal));
                arr = result;
                subtotal_result = subtotal;
                id_keranjang = keranjang;
                if (arr.length == 0) {
                    $("#alamat-check").attr("disabled", true);
                    $("#btn-lanjut").attr("disabled", true);
                } else {
                    $("#alamat-check").attr("disabled", false);
                    if (alamat_check == null) {
                        $("#btn-lanjut").attr("disabled", true);
                    } else {
                        $("#btn-lanjut").attr("disabled", false);
                    }
                }
            });

            let data_co = {};
            $('#alamat-check').on('change', function() {
                event.preventDefault();
                $("#btn-lanjut").attr("disabled", true);
                var id_alamat = $(this).val();
                var data = arr.map(function(item) {
                    return {
                        id_toko: item.id_toko,
                        products: item.products.map(function(product) {
                            return {
                                id_produk: product.id_produk,
                                harga: product.harga,
                                qty: product.qty
                            }
                        })
                    }
                });
                $.ajax({
                    url: "{{ url('api/ongkir') }}",
                    type: "POST",
                    contentType: 'application/json',
                    data: JSON.stringify({
                        _token: "{{ csrf_token() }}",
                        id_alamat: id_alamat,
                        id_user: "{{ Auth::user()->id }}",
                        data,
                        keranjang: id_keranjang,
                    }),
                    success: function(response) {
                        console.log(response);
                        var result = response.data.data;
                        var html = '';
                        result.forEach(function(item) {
                            html += '<li><span class="ps-block__shop">' + item
                                .nama_toko +
                                '</span><p class="d-flex justify-content-between">ongkir: <span class="text-right">' +
                                formatRupiah(item.ongkir) +
                                '</span></p></li>';
                        });
                        $('#result-ongkir').html(html);
                        var total = 0;
                        result.forEach(function(item) {
                            total += item.ongkir;
                        });
                        $('#total').html(formatRupiah(subtotal_result + total));
                        $('#subtotal-ongkir').html(formatRupiah(total));
                        data_co = response.data
                        $("#btn-lanjut").attr("disabled", false);
                    },
                    error: function(response) {
                        console.log(response);
                    }
                });
                alamat_check = id_alamat;
            });

            $('#btn-lanjut').on('click', function() {
                event.preventDefault();
                $.ajax({
                    url: "{{ url('api/transaction') }}",
                    type: "POST",
                    contentType: 'application/json',
                    data: JSON.stringify({
                        _token: "{{ csrf_token() }}",
                        ...data_co
                    }),
                    success: function(response) {
                        console.log(response);
                        let url = response.data.encrypt_pembayaran
                        window.location.href = "{{ url('pembayaran') }}?data=" + url;
                    },
                    error: function(response) {
                        console.log(response);
                    }
                });
                // let password = '{{ env('APP_KEY') }}'
                // let encrypted = CryptoJSAesJson.encrypt('asdf', password)
                // encrypted = JSON.parse(encrypted)
                // let fullData = encrypted.ct + 'TBL' + encrypted.iv + 'TBL' + encrypted.s
                // console.log(fullData)
                // window.location.href = "{{ url('pembayaran') }}?data=" + fullData;
            });
        });
    </script>
@endpush
