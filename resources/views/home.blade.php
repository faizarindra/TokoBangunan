@extends('layouts.app')

@section('title', 'Home')
@section('content')
    <div id="homepage-2">
        <div class="ps-home-banner">
            <div class="ps-carousel--nav-inside owl-slider owl-carousel owl-loaded owl-drag" data-owl-auto="true"
                data-owl-loop="true" data-owl-speed="5000" data-owl-gap="0" data-owl-nav="true" data-owl-dots="true"
                data-owl-item="1" data-owl-item-xs="1" data-owl-item-sm="1" data-owl-item-md="1" data-owl-item-lg="1"
                data-owl-duration="1000" data-owl-mousedrag="on" data-owl-animate-in="fadeIn"
                data-owl-animate-out="fadeOut">
                <div class="owl-stage-outer">
                    <div class="owl-stage"
                        style="transform: translate3d(-2602px, 0px, 0px); transition: all 0s ease 0s; width: 7806px;">
                        <div class="owl-item" style="width: 1301px;">
                            <div class="ps-banner--autopart" data-background="img/slider/autopart/1.jpg"
                                style="background: url(&quot;img/slider/1.jpg&quot;);"><img src="img/slider/1.jpg"
                                    alt="" style="height:430px">
                                <div class="ps-banner__content">
                                    <h4>Semen Terbaik 2022</h4>
                                    <h3>Semen <br> HOLCIM</h3>
                                    <p><strong>Kuat, Tahan Lama &amp; Harga Murah</strong></p><a class="ps-btn"
                                        href="#">Cek Sekarang</a>
                                </div>
                            </div>
                        </div>
                        <div class="owl-item" style="width: 1301px;">
                            <div class="ps-banner--autopart" data-background="img/slider/autopart/2.jpg"
                                style="background: url(&quot;img/slider/2.jpg&quot;);"><img src="img/slider/2.jpg"
                                    alt="" style="height:430px">
                                <div class="ps-banner__content">
                                    <h4>Semen Termurah 2022</h4>
                                    <h3>Semen <br> Tiga Roda</h3>
                                    <p><strong>Kuat, Tahan Lama &amp; Harga Murah</strong></p><a class="ps-btn"
                                        href="#">Cek Sekarang</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="owl-nav"><button type="button" role="presentation" class="owl-prev"><i
                            class="icon-chevron-left"></i></button><button type="button" role="presentation"
                        class="owl-next"><i class="icon-chevron-right"></i></button></div>
                <div class="owl-dots"><button role="button" class="owl-dot active"><span></span></button><button
                        role="button" class="owl-dot"><span></span></button></div>
            </div>
        </div>
        <div class="ps-top-categories">
            <div class="container">
                <div class="ps-section__header">
                    <h3>Kategori</h3>
                </div>
                <div class="ps-section__content"></div>
                <div class="row align-content-lg-stretch">
                    @foreach ($categories as $category)
                        <div class="col-xl-4 col-lg-4 col-md-4 col-sm-6 col-12 ">
                            <div class="ps-block--category-2 ps-block--category-auto-part" data-mh="categories"
                                style="height: 169.188px;">
                                <div class="ps-block__thumbnail">
                                    <img src="{{ asset('img/' . $category->image) }}" alt=""
                                        style="min-height: 100%;object-fit: cover;">
                                </div>
                                <div class="ps-block__content">
                                    <h4>{{ $category->nama_kategori }}</h4>
                                    <ul>
                                        @foreach ($category->merks as $merk)
                                            <li><a href="{{ url('merk/' . $merk->prefix) }}">{{ $merk->nama_merk }}</a>
                                            </li>
                                        @endforeach
                                        <li class="more"><a href="{{ url('kategori/' . $category->prefix) }}">More<i
                                                    class="icon-chevron-right"></i></a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        <div class="ps-product-list ps-recommend-for-you">
            <div class="container">
                <div class="ps-section__header">
                    <h3>PRODUK TERLARIS</h3>
                </div>
                <div class="ps-section__content">
                    <div class="ps-carousel--nav owl-slider owl-carousel owl-loaded owl-drag" data-owl-auto="false"
                        data-owl-loop="false" data-owl-speed="10000" data-owl-gap="30" data-owl-nav="true"
                        data-owl-dots="true" data-owl-item="5" data-owl-item-xs="2" data-owl-item-sm="2"
                        data-owl-item-md="2" data-owl-item-lg="3" data-owl-item-xl="5" data-owl-duration="1000"
                        data-owl-mousedrag="on">
                        <div class="owl-stage-outer">
                            <div class="owl-stage"
                                style="transform: translate3d(0px, 0px, 0px); transition: all 0s ease 0s; width: 1920px;">

                                @foreach ($products as $product)
                                    <div class="owl-item active" style="width: 290px; margin-right: 30px;">
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
                                                                {!! str_repeat(
                                                                    '<a href="#" data-rating-value="1" data-rating-text="1" class="br-selected br-current"></a>',
                                                                    $product->reviews,
                                                                ) !!}
                                                                {!! str_repeat('<a href="#" data-rating-value="2" data-rating-text="5"></a>', 5 - floor($product->reviews)) !!}
                                                                <div class="br-current-rating">{{ $product->reviews }}
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
                    </div>
                </div>
            </div>
        </div>
        <div class="ps-product-list ps-recommend-for-you mt-5">
            <div class="container">
                <div class="ps-section__header">
                    <h3>Semua Produk</h3>
                </div>
                <div class="ps-section__content">
                    <div class="container">
                        <div class="row">
                            @foreach ($allProducts as $product)
                                <div class="col-4">
                                    <div class="owl-item active" style="width: 290px; margin-right: 30px;">
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
                                                            <div class="br-widget br-readonly"> {!! str_repeat(
                                                                '<a href="#" data-rating-value="1" data-rating-text="1" class="br-selected br-current"></a>',
                                                                $product->reviews,
                                                            ) !!}
                                                                {!! str_repeat('<a href="#" data-rating-value="2" data-rating-text="5"></a>', 5 - floor($product->reviews)) !!}
                                                                <div class="br-current-rating">{{ $product->reviews }}
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
                                </div>
                            @endforeach
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <div class="ps-best-sale-brands ps-section--furniture">
        <div class="container">
            <div class="ps-section__header">
                <h3>MERK TERPOPULER</h3>
            </div>
            <div class="ps-section__content">
                <ul class="ps-image-list">
                    @foreach ($brands as $brand)
                        <li><a href="{{ url('merk/' . $brand->prefix) }}"><img
                                    src="{{ asset('img/merk/' . $brand->image) }}" alt=""></a></li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
    <div class="ps-site-features">
        <div class="container">
            <div class="ps-block--site-features ps-block--site-features-2">
                <div class="ps-block__item">
                    <div class="ps-block__left"><i class="icon-rocket"></i></div>
                    <div class="ps-block__right">
                        <h4>Free Delivery</h4>
                        <p>For all oders over $99</p>
                    </div>
                </div>
                <div class="ps-block__item">
                    <div class="ps-block__left"><i class="icon-sync"></i></div>
                    <div class="ps-block__right">
                        <h4>90 Days Return</h4>
                        <p>If goods have problems</p>
                    </div>
                </div>
                <div class="ps-block__item">
                    <div class="ps-block__left"><i class="icon-credit-card"></i></div>
                    <div class="ps-block__right">
                        <h4>Secure Payment</h4>
                        <p>100% secure payment</p>
                    </div>
                </div>
                <div class="ps-block__item">
                    <div class="ps-block__left"><i class="icon-bubbles"></i></div>
                    <div class="ps-block__right">
                        <h4>24/7 Support</h4>
                        <p>Dedicated support</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
@endsection
