@extends('toko.layouts.main')

@section('title', 'Produk')

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
                    <h3 class="mb-0"> Produk Saya</h3>
                    <a href="{{ url('seller/tambah-produk') }}" class="ml-4 badge badge-pill px-4 py-2"
                        style="background-color: #dd2400;color:white;font-size:12px"> +
                        Tambah</a>
                </div>
            </div>
        </div>
        <div class="row">
            @forelse ($products as $product)
                <div class="col-xl-3 col-lg-4 col-md-4 col-sm-6 col-6">
                    <div class="ps-product">
                        <div class="ps-product__thumbnail">
                            <a>
                                <img src="{{ asset('img/produk/' . $product->gambar_produk) }}" alt="">
                            </a>
                        </div>
                        <div class="ps-product__container"><a class="ps-product__vendor" href="#"></a>
                            <div class="ps-product__content"><a class="ps-product__title">{{ $product->nama_produk }}</a>
                                <div class="ps-product__rating">
                                    <div class="br-wrapper br-theme-fontawesome-stars">
                                        <div class="br-widget br-readonly">
                                            <div class="br-widget br-readonly">
                                                {!! str_repeat(
                                                    '<a href="#" data-rating-value="1" data-rating-text="1" class="br-selected br-current"></a>',
                                                    $product->reviews,
                                                ) !!}
                                                {!! str_repeat('<a href="#" data-rating-value="2" data-rating-text="5"></a>', 5 - floor($product->reviews)) !!}
                                                <div class="br-current-rating">
                                                    {{ $product->reviews }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <p class="ps-product__price sale">@currency($product->harga_produk)</p>
                            </div>
                            <div class="ps-product__content hover"><a
                                    class="ps-product__title">{{ $product->nama_produk }}</a>
                                <p class="ps-product__price sale">@currency($product->harga_produk)</p>
                                <div class="d-flex justify-content-center align-items-center">
                                    <a href="{{ url('seller/produk/' . Crypt::encrypt($product->id)) }}"
                                        class="badge badge-pill px-4 py-2 mr-2 badge-warning"
                                        style="color:white;font-size:12px">Ubah</a>
                                    <form id="hapus-produk-{{ $product->id }}"
                                        action="{{ url('/seller/produk/' . $product->id) }}" method="POST"
                                        style="display: none;">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                    <a onclick="event.preventDefault(); document.getElementById('hapus-produk-{{ $product->id }}').submit();"
                                        class="badge badge-pill px-4 py-2"
                                        style="background-color: #dd2400;color:white;font-size:12px;cursor:pointer;"><i
                                            class="fa fa-trash"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="alert alert-warning py-3">
                        Tidak ada produk yang ditemukan
                    </div>
                </div>
            @endforelse
        </div>
    </div>
    <div class="ps-pagination">
        {{ $products->links() }}
    </div>
@endsection
