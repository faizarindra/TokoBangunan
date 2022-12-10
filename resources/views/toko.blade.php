@extends('layouts.app')

@section('title', 'Detail Toko')
@section('content')
    <div class="ps-page--single ps-page--vendor">
        <section class="ps-store-list">
            <div class="container">
                <aside class="ps-block--store-banner">
                    <div class="ps-block__content bg--cover"
                        data-background="http://nouthemes.net/html/martfury/img/vendor/store/default_banner.jpg"
                        style="background: url('http://nouthemes.net/html/martfury/img/vendor/store/default_banner.jpg');">
                        <h3>{{ $toko->nama_toko }}</h3>
                    </div>
                    <div class="ps-block__user">
                        <div class="ps-block__user-avatar"><img src="{{ asset('img/toko/' . $toko->gambar_toko) }}"
                                alt="">
                            <div class="br-wrapper br-theme-fontawesome-stars">
                                <div class="br-widget br-readonly"> {!! str_repeat(
                                    '<a href="#" data-rating-value="1" data-rating-text="1" class="br-selected br-current"></a>',
                                    $reviews,
                                ) !!}
                                    {!! str_repeat('<a href="#" data-rating-value="2" data-rating-text="5"></a>', 5 - floor($reviews)) !!}
                                    <div class="br-current-rating">{{ $reviews }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="ps-block__user-content">
                            <p><i class="icon-map-marker"></i> {{ $toko->alamat_toko }}, Kec. {{ $toko->nama_kecamatan }},
                                {{ $toko->nama_kota }}, {{ $toko->nama_provinsi }}</p>
                            <p>{{ $toko->deskripsi_toko }}</p>
                        </div>
                    </div>
                </aside>
                <div class="ps-section__wrapper">
                    <div class="ps-section__left">
                        <aside class="widget widget--vendor">
                            <h3 class="widget-title">Cari produk...</h3>
                            <div class="form-group--icon">
                                <input class="form-control" type="text" placeholder="Search..."><i
                                    class="icon-magnifier"></i>
                            </div>
                        </aside>
                        <aside class="widget widget--vendor">
                            <h3 class="widget-title">Kategori</h3>
                            <ul class="ps-list--arrow">
                                @foreach ($categories as $category)
                                    <li><a
                                            href="{{ url('kategori/' . $category->prefix) }}">{{ $category->nama_kategori }}</a>
                                    </li>
                                @endforeach
                            </ul>
                        </aside>
                    </div>
                    <div class="ps-section__right">
                        <nav class="ps-store-link">
                            <ul>
                                <li class="active"><a href="{{ url('toko/' . $toko->prefix) }}">Products</a></li>
                                <li><a href="store-detail.html">Ulasan({{ $total_review }})</a></li>
                            </ul>
                        </nav>
                        <div class="ps-shopping ps-tab-root">
                            <div class="ps-shopping__header">
                                <p><strong> {{ $total_product }}</strong> Produk ditemukan</p>
                            </div>
                            <div class="ps-tabs">
                                <div class="ps-tab active" id="tab-1">
                                    <div class="ps-shopping-product">
                                        <div class="row">
                                            @foreach ($products as $product)
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
                                                            <div class="ps-product__content hover"><a
                                                                    class="ps-product__title"
                                                                    href="{{ url('produk/' . $product->prefix) }}">{{ $product->nama_produk }}</a>
                                                                <p class="ps-product__price sale">@currency($product->harga_produk)</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
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
        </section>
    </div>
@endsection
