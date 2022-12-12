@extends('profile.layouts.main')

@section('title', 'Terakhir Dilihat')

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
                    <h3 class="mb-0"> Terakhir Dilihat</h3>
                </div>
            </div>
        </div>
        <div class="row">
            @foreach ($terakhir_dilihats as $product)
                <div class="col-xl-3 col-lg-4 col-md-4 col-sm-6 col-6">
                    <div class="ps-product">
                        <div class="ps-product__thumbnail">
                            <a href="{{ url('produk/' . $product->prefix) }}">
                                <img src="{{ asset('img/produk/' . $product->gambar_produk) }}" alt="">
                            </a>
                        </div>
                        <div class="ps-product__container"><a class="ps-product__vendor" href="#"></a>
                            <div class="ps-product__content"><a class="ps-product__title"
                                    href="{{ url('produk/' . $product->prefix) }}">{{ $product->nama_produk }}</a>
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
                            <div class="ps-product__content hover"><a class="ps-product__title"
                                    href="{{ url('produk/' . $product->prefix) }}">{{ $product->nama_produk }}</a>
                                <p class="ps-product__price sale">@currency($product->harga_produk)</p>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection
