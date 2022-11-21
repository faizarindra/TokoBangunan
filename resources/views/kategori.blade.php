@extends('layouts.app')

@section('title', 'Kategori')

@section('content')
    <div class="ps-page--shop" id="shop-sidebar">
        <div class="container">
            <div class="ps-layout--shop">
                <div class="ps-layout__left">
                    <aside class="widget widget_shop">
                        <h4 class="widget-title">Kategori</h4>
                        <ul class="ps-list--categories">
                            @foreach ($categories as $kategori)
                                <li>
                                    <a href="{{ url('kategori/' . $kategori->prefix) }}">{{ $kategori->nama_kategori }}</a>
                                </li>
                            @endforeach
                        </ul>
                    </aside>
                    <aside class="widget widget_shop">
                        <h4 class="widget-title">Merk</h4>
                        <form class="ps-form--widget-search" action="do_action" method="get">
                            <input class="form-control" type="text" placeholder="">
                            <button><i class="icon-magnifier"></i></button>
                        </form>
                        <figure class="ps-custom-scrollbar" data-height="250">
                            @foreach ($merks as $merk)
                                <a href="{{ url('merk/' . $merk->prefix) }}">
                                    <div class="ps-checkbox">
                                        {{-- <input class="form-control" type="checkbox" id="brand-1" name="brand"> --}}
                                        <label for="brand-1">{{ $merk->nama_merk }}</label>
                                    </div>
                                </a>
                            @endforeach
                        </figure>
                    </aside>
                </div>
                <div class="ps-layout__right">
                    <div class="ps-page__header mb-2">
                        <h1>Produk di dalam kategori {{ $category->nama_kategori }}</h1>
                    </div>
                    <div class="ps-shopping ps-tab-root">
                        <div class="ps-shopping__header">
                            <p><strong> {{ $total_product }}</strong> Produk ditemukan</p>
                        </div>
                        <div class="ps-tabs">
                            <div class="ps-tab active" id="tab-1">
                                <div class="ps-shopping-product">
                                    <div class="row">
                                        @forelse ($products as $product)
                                            <div class="col-xl-3 col-lg-4 col-md-4 col-sm-6 col-6">
                                                <div class="ps-product">
                                                    <div class="ps-product__thumbnail">
                                                        <a href="{{ url('produk/' . $product->prefix) }}">
                                                            <img src="{{ asset('img/produk/' . $product->gambar_produk) }}"
                                                                alt="">
                                                        </a>
                                                    </div>
                                                    <div class="ps-product__container"><a class="ps-product__vendor"
                                                            href="#"></a>
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
                                        @empty
                                            <div class="col-12">
                                                <div class="alert alert-danger text-center p-4" role="alert">
                                                    Produk tidak ditemukan
                                                </div>
                                            </div>
                                        @endforelse
                                    </div>
                                </div>
                                <div class="ps-pagination">
                                    {{ $products->links() }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
