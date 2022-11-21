<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

@php
    $kategori = \App\Models\Kategori::all();
    $kategori = $kategori->map(function ($cat) {
        $cat['merk'] = \App\Models\Merk::where('id_kategori', $cat->id)->get();
        return $cat;
    });
@endphp

@auth
    @php
        $favorite = \App\Models\Favorite::where('id_user', Auth::user()->id)->count();
        $cart = \App\Models\Keranjang::where('id_user', Auth::user()->id)->sum('qty');
        $carts = \App\Models\Keranjang::select('keranjang.*', 'produk.*', 'toko.nama_toko as nama_toko')
            ->where('keranjang.id_user', Auth::user()->id)
            ->join('produk', 'keranjang.id_produk', 'produk.id')
            ->join('toko', 'produk.id_toko', 'toko.id')
            ->get();
        $cartNum = \App\Models\Keranjang::where('keranjang.id_user', Auth::user()->id)
            ->join('produk', 'keranjang.id_produk', 'produk.id')
            ->get();
        $cartNum = $cartNum->map(function ($crt) {
            $produk = \App\Models\Produk::where('id', $crt->id_produk)->first();
            $crt['total'] = $produk->harga_produk * $crt->qty;
            return $crt;
        });
    @endphp
@endauth

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Mart Bangunan - @yield('title')</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Work+Sans:300,400,500,600,700&amp;amp;subset=latin-ext"
        rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('/css/font-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/css/demo.css') }}">
    <link rel="stylesheet" href="{{ mix('css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('/css/owl.carousel.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/css/owl.theme.default.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/css/slick.css') }}">
    <link rel="stylesheet" href="{{ asset('/css/nouislider.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/css/lightgallery.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/css/fontawesome-stars.css') }}">
    <link rel="stylesheet" href="{{ asset('/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('/css/autopart.css') }}">

    <script src="{{ mix('js/app.js') }}" defer></script>
</head>

<body>
    <header class="header header--standard header--autopart" data-sticky="true">
        <div class="header__top">
            <div class="container">
                <div class="header__left">
                    <p>Selamat Datang di Toko Bangunan</p>
                </div>
                <div class="header__right">
                    <ul class="header__top-links">
                        <li><a href="#">Lokasi Toko</a></li>
                        <li><a href="#">Lacak Kiriman</a></li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="header__content">
            <div class="container">
                <div class="header__content-left">
                    <a class="ps-logo" href="{{ url('/') }}"><img src="{{ asset('img/logo.png') }}"
                            alt=""></a>
                </div>
                <div class="header__content-center" id="id-search">
                    <form class="ps-form--quick-search" action="">
                        @csrf
                        <input class="form-control" type="text" name="search"
                            placeholder="Cari produk, toko, brand..." autocomplete="off" id="query-search">
                        <button type="button" id="btn-search">Cari</button>
                    </form>
                    <div class="bg-light p-4"
                        style="position: absolute;max-height:300px;width:600px;z-index:1;opacity:0;overflow:hidden;overflow-y:scroll"
                        id="box-search">
                    </div>
                </div>
                <div class="header__content-right">
                    <div class="header__actions">
                        @auth
                            <a class="header__extra" href="{{ url('favorite') }}">
                                <i class="icon-heart"></i>
                                @if ($favorite != 0)
                                    <span>
                                        <i>{{ $favorite }}</i>
                                    </span>
                                @endif
                            </a>
                            <div class="ps-cart--mini">
                                <a class="header__extra cart" href="#" id="cart"
                                    data-totalitems="{{ $cart }}">
                                    <i class="icon-bag2"></i>
                                </a>
                                <div class="ps-cart__content">
                                    <div class="ps-cart__items">
                                        @foreach ($carts as $keranjang)
                                            <div class="ps-product--cart-mobile">
                                                <div class="ps-product__thumbnail"><a href="#"><img
                                                            src="{{ asset('img/produk/' . $keranjang->gambar_produk) }}"
                                                            alt=""></a></div>
                                                <div class="ps-product__content"><a class="ps-product__remove"
                                                        href="#"><i class="icon-cross"></i></a><a
                                                        href="{{ url('produk/' . $keranjang->prefix) }}">{{ $keranjang->nama_produk }}</a>
                                                    <p><strong>Oleh:</strong> {{ $keranjang->nama_toko }}</p>
                                                    <small>{{ $keranjang->qty }}
                                                        x @currency($keranjang->harga_produk)</small>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                    <div class="ps-cart__footer">
                                        <h3>Sub Total:<strong id="sub-total_count">
                                                @currency($cartNum->sum('total'))</strong></h3>
                                        <figure><a class="ps-btn" href="shopping-cart.html">Lihat Keranjang</a><a
                                                class="ps-btn" href="checkout.html">Checkout</a></figure>
                                    </div>
                                </div>
                            </div>
                        @endauth
                        <div class="ps-block--user-header">
                            <div class="ps-block__left"><i class="icon-user"></i></div>
                            @auth
                                <div class="ps-block__right d-flex align-items-center">
                                    <a href="{{ url('profile') }}">Profile</a>
                                </div>
                            @endauth
                            @guest
                                <div class="ps-block__right d-flex align-items-center">
                                    <a href="{{ url('login') }}">Login</a>
                                    <span class="mx-2">/</span>
                                    <a href="{{ url('register') }}">Register</a>
                                </div>
                            @endguest
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <nav class="navigation">
            <div class="container">
                <div class="navigation__left">
                    <div class="menu--product-categories">
                        <div class="menu__toggle"><i class="icon-menu"></i><span>Kategori</span></div>
                        <div class="menu__content">
                            <ul class="menu--dropdown">
                                @foreach ($kategori as $item)
                                    <li
                                        class="{{ count($item->merk) == 0 ? ' ' : 'menu-item-has-children has-mega-menu' }}">
                                        <a
                                            href="{{ url('kategori/' . $item->prefix) }}">{{ $item->nama_kategori }}</a>
                                        @if (count($item->merk) > 1)
                                            <div class="mega-menu">
                                                <div class="mega-menu__column">
                                                    <h4>{{ $item->nama_kategori }}
                                                    </h4>
                                                    <ul class="mega-menu__list">
                                                        @foreach ($item->merk as $merk)
                                                            <li><a
                                                                    href="{{ url('merk/' . $merk->prefix) }}">{{ $merk->nama_merk }}</a>
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                </div>
                                            </div>
                                        @endif
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="navigation__right">
                    <ul class="menu">
                        <li class="menu-item"><a href="{{ url('/') }}">Home</a></li>
                        <li class="menu-item"><a href="{{ url('/about-us') }}">Tentang Kami</a></li>
                        <li class="menu-item"><a href="{{ url('/toc') }}">Terms & Condition</a></li>
                    </ul>
                    <div class="ps-block--header-hotline inline">
                        <p><i class="icon-telephone"></i>Hotline:<strong> +62 821-4365-2236</strong></p>
                    </div>
                </div>
            </div>
        </nav>
    </header>
    <header class="header header--mobile autopart" data-sticky="true">
        <div class="header__top">
            <div class="header__left">
                <p>Welcome to Martfury Online Shopping Store !</p>
            </div>
            <div class="header__right">
                <ul class="navigation__extra">
                    <li><a href="#">Sell on Martfury</a></li>
                    <li><a href="#">Tract your order</a></li>
                    <li>
                        <div class="ps-dropdown"><a href="#">US Dollar</a>
                            <ul class="ps-dropdown-menu">
                                <li><a href="#">Us Dollar</a></li>
                                <li><a href="#">Euro</a></li>
                            </ul>
                        </div>
                    </li>
                    <li>
                        <div class="ps-dropdown language"><a href="#"><img src="img/flag/en.png"
                                    alt="" />English</a>
                            <ul class="ps-dropdown-menu">
                                <li><a href="#"><img src="img/flag/germany.png" alt="" /> Germany</a>
                                </li>
                                <li><a href="#"><img src="img/flag/fr.png" alt="" /> France</a></li>
                            </ul>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
        <div class="navigation--mobile">
            <div class="navigation__left"><a class="ps-logo" href="index.html"><img
                        src="{{ asset('img/logo2.png') }}" alt="" /></a></div>
            <div class="navigation__right">
                <div class="header__actions">
                    <div class="ps-block--user-header">
                        <div class="ps-block__left"><a href="{{ url('account') }}"><i class="icon-user"></i></a>
                        </div>
                        <div class="ps-block__right"><a href="{{ url('login') }}">Login</a><a
                                href="{{ url('register') }}">Register</a></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="ps-search--mobile">
            <form class="ps-form--search-mobile" action="index.html" method="get">
                <div class="form-group--nest">
                    <input class="form-control" type="text" placeholder="Search something..." />
                    <button><i class="icon-magnifier"></i></button>
                </div>
            </form>
        </div>
    </header>

    @yield('content')

    <footer class="ps-footer ps-footer--2">
        <div class="container">
            <div class="ps-footer__content">
                <div class="row">
                    <div class="col-xl-3 col-lg-3 col-md-4 col-sm-6 col-6 ">
                        <aside class="widget widget_footer">
                            <h4 class="widget-title">Quick links</h4>
                            <ul class="ps-list--link">
                                <li><a href="policy.html">Policy</a></li>
                                <li><a href="term-condition.html">Term & Condition</a></li>
                                <li><a href="shipping.html">Shipping</a></li>
                                <li><a href="return.html">Return</a></li>
                                <li><a href="faqs.html">FAQs</a></li>
                            </ul>
                        </aside>
                    </div>
                    <div class="col-xl-3 col-lg-3 col-md-4 col-sm-6 col-6 ">
                        <aside class="widget widget_footer">
                            <h4 class="widget-title">Company</h4>
                            <ul class="ps-list--link">
                                <li><a href="about-us.html">About Us</a></li>
                                <li><a href="affilate.html">Affilate</a></li>
                                <li><a href="shipping.html">Career</a></li>
                                <li><a href="contact.html">Contact</a></li>
                            </ul>
                        </aside>
                    </div>
                    <div class="col-xl-3 col-lg-3 col-md-4 col-sm-6 col-12 ">
                        <aside class="widget widget_footer">
                            <h4 class="widget-title">Bussiness</h4>
                            <ul class="ps-list--link">
                                <li><a href="our-press.html">Our Press</a></li>
                                <li><a href="checkout.html">Checkout</a></li>
                                <li><a href="my-account.html">My account</a></li>
                                <li><a href="shop.html">Shop</a></li>
                            </ul>
                        </aside>
                    </div>
                    <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-12 ">
                        <aside class="widget widget_newletters">
                            <h4 class="widget-title">Newsletter</h4>
                            <form class="ps-form--newletter" action="#" method="get">
                                <div class="form-group--nest">
                                    <input class="form-control" type="text" placeholder="Email Address">
                                    <button class="ps-btn">Subscribe</button>
                                </div>
                                <ul class="ps-list--social">
                                    <li><a class="facebook" href="#"><i class="fa fa-facebook"></i></a></li>
                                    <li><a class="twitter" href="#"><i class="fa fa-twitter"></i></a></li>
                                    <li><a class="google-plus" href="#"><i class="fa fa-google-plus"></i></a>
                                    </li>
                                    <li><a class="instagram" href="#"><i class="fa fa-instagram"></i></a></li>
                                </ul>
                            </form>
                        </aside>
                    </div>
                </div>
            </div>
            <div class="ps-footer__copyright">
                <p>© 2021 Martfury. All Rights Reserved</p>
                <p><span>We Using Safe Payment For:</span><a href="#"><img src="img/payment-method/1.jpg"
                            alt=""></a><a href="#"><img src="img/payment-method/2.jpg"
                            alt=""></a><a href="#"><img src="img/payment-method/3.jpg"
                            alt=""></a><a href="#"><img src="img/payment-method/4.jpg"
                            alt=""></a><a href="#"><img src="img/payment-method/5.jpg"
                            alt=""></a></p>
            </div>
        </div>
    </footer>
    <div id="back2top"><i class="icon icon-arrow-up"></i></div>
    <div class="ps-site-overlay"></div>
    <div class="ps-panel--sidebar" id="cart-mobile">
        <div class="ps-panel__header">
            <h3>Shopping Cart</h3>
        </div>
        <div class="navigation__content">
            <div class="ps-cart--mobile">
                <div class="ps-cart__content">
                    <div class="ps-product--cart-mobile">
                        <div class="ps-product__thumbnail"><a href="#"><img src="img/products/clothing/7.jpg"
                                    alt=""></a></div>
                        <div class="ps-product__content"><a class="ps-product__remove" id="remove-cart_items"><i
                                    class="icon-cross"></i></a><a href="product-default.html">MVMTH Classical Leather
                                Watch In Black</a>
                            <p><strong>Sold by:</strong> YOUNG SHOP</p><small>1 x $59.99</small>
                        </div>
                    </div>
                </div>
                <div class="ps-cart__footer">
                    <h3>Sub Total:<strong>$59.99</strong></h3>
                    <figure><a class="ps-btn" href="shopping-cart.html">View Cart</a><a class="ps-btn"
                            href="checkout.html">Checkout</a></figure>
                </div>
            </div>
        </div>
    </div>
    <!--include ../../data/menu/menu-product-categories-->
    <div class="ps-panel--sidebar" id="navigation-mobile">
        <div class="ps-panel__header">
            <h3>Categories</h3>
        </div>
        <div class="ps-panel__content">
            <div class="menu--product-categories">
                <div class="menu__toggle"><i class="icon-menu"></i><span> Shop by Department</span></div>
                <div class="menu__content">
                    <ul class="menu--mobile">
                        <li><a href="#.html">Hot Promotions</a>
                        </li>
                        <li class="menu-item-has-children has-mega-menu"><a href="#">Consumer
                                Electronic</a><span class="sub-toggle"></span>
                            <div class="mega-menu">
                                <div class="mega-menu__column">
                                    <h4>Electronic<span class="sub-toggle"></span></h4>
                                    <ul class="mega-menu__list">
                                        <li><a href="#.html">Home Audio &amp; Theathers</a>
                                        </li>
                                        <li><a href="#.html">TV &amp; Videos</a>
                                        </li>
                                        <li><a href="#.html">Camera, Photos &amp; Videos</a>
                                        </li>
                                        <li><a href="#.html">Cellphones &amp; Accessories</a>
                                        </li>
                                        <li><a href="#.html">Headphones</a>
                                        </li>
                                        <li><a href="#.html">Videosgames</a>
                                        </li>
                                        <li><a href="#.html">Wireless Speakers</a>
                                        </li>
                                        <li><a href="#.html">Office Electronic</a>
                                        </li>
                                    </ul>
                                </div>
                                <div class="mega-menu__column">
                                    <h4>Accessories &amp; Parts<span class="sub-toggle"></span></h4>
                                    <ul class="mega-menu__list">
                                        <li><a href="#.html">Digital Cables</a>
                                        </li>
                                        <li><a href="#.html">Audio &amp; Video Cables</a>
                                        </li>
                                        <li><a href="#.html">Batteries</a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </li>
                        <li><a href="#.html">Clothing &amp; Apparel</a>
                        </li>
                        <li><a href="#.html">Home, Garden &amp; Kitchen</a>
                        </li>
                        <li><a href="#.html">Health &amp; Beauty</a>
                        </li>
                        <li><a href="#.html">Yewelry &amp; Watches</a>
                        </li>
                        <li class="menu-item-has-children has-mega-menu"><a href="#">Computer &amp;
                                Technology</a><span class="sub-toggle"></span>
                            <div class="mega-menu">
                                <div class="mega-menu__column">
                                    <h4>Computer &amp; Technologies<span class="sub-toggle"></span></h4>
                                    <ul class="mega-menu__list">
                                        <li><a href="#.html">Computer &amp; Tablets</a>
                                        </li>
                                        <li><a href="#.html">Laptop</a>
                                        </li>
                                        <li><a href="#.html">Monitors</a>
                                        </li>
                                        <li><a href="#.html">Networking</a>
                                        </li>
                                        <li><a href="#.html">Drive &amp; Storages</a>
                                        </li>
                                        <li><a href="#.html">Computer Components</a>
                                        </li>
                                        <li><a href="#.html">Security &amp; Protection</a>
                                        </li>
                                        <li><a href="#.html">Gaming Laptop</a>
                                        </li>
                                        <li><a href="#.html">Accessories</a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </li>
                        <li><a href="#.html">Babies &amp; Moms</a>
                        </li>
                        <li><a href="#.html">Sport &amp; Outdoor</a>
                        </li>
                        <li><a href="#.html">Phones &amp; Accessories</a>
                        </li>
                        <li><a href="#.html">Books &amp; Office</a>
                        </li>
                        <li><a href="#.html">Cars &amp; Motocycles</a>
                        </li>
                        <li><a href="#.html">Home Improments</a>
                        </li>
                        <li><a href="#.html">Vouchers &amp; Services</a>
                        </li>
                    </ul>
                </div>
            </div>
            <!--+createMenu(product_categories, 'menu--mobile')-->
        </div>
    </div>
    <div class="navigation--list">
        <div class="navigation__content"><a class="navigation__item ps-toggle--sidebar" href="#menu-mobile"><i
                    class="icon-menu"></i><span> Menu</span></a><a class="navigation__item ps-toggle--sidebar"
                href="#navigation-mobile"><i class="icon-list4"></i><span> Categories</span></a><a
                class="navigation__item ps-toggle--sidebar" href="#search-sidebar"><i
                    class="icon-magnifier"></i><span> Search</span></a><a class="navigation__item ps-toggle--sidebar"
                href="#cart-mobile"><i class="icon-bag2"></i><span> Cart</span></a></div>
    </div>
    <div class="ps-panel--sidebar" id="search-sidebar">
        <div class="ps-panel__header">
            <form class="ps-form--search-mobile" action="index.html" method="get">
                <div class="form-group--nest">
                    <input class="form-control" type="text" placeholder="Search something...">
                    <button><i class="icon-magnifier"></i></button>
                </div>
            </form>
        </div>
        <div class="navigation__content"></div>
    </div>
    <div class="ps-panel--sidebar" id="menu-mobile">
        <div class="ps-panel__header">
            <h3>Menu</h3>
        </div>
        <div class="ps-panel__content">
            <ul class="menu--mobile">
                <li class="menu-item-has-children"><a href="index">Home</a><span class="sub-toggle"></span>
                    <ul class="sub-menu">
                        <li><a href="index.html">Marketplace Full Width</a>
                        </li>
                        <li><a href="home-autopart.html">Home Auto Parts</a>
                        </li>
                        <li><a href="home-technology.html">Home Technology</a>
                        </li>
                        <li><a href="home-organic.html">Home Organic</a>
                        </li>
                        <li><a href="home-marketplace.html">Home Marketplace V1</a>
                        </li>
                        <li><a href="home-marketplace-2.html">Home Marketplace V2</a>
                        </li>
                        <li><a href="home-marketplace-3.html">Home Marketplace V3</a>
                        </li>
                        <li><a href="home-marketplace-4.html">Home Marketplace V4</a>
                        </li>
                        <li><a href="home-electronic.html">Home Electronic</a>
                        </li>
                        <li><a href="home-furniture.html">Home Furniture</a>
                        </li>
                        <li><a href="home-kid.html">Home Kids</a>
                        </li>
                        <li><a href="homepage-photo-and-video.html">Home photo and picture</a>
                        </li>
                        <li><a href="home-medical.html">Home Medical</a>
                        </li>
                    </ul>
                </li>
                <li class="menu-item-has-children has-mega-menu"><a href="shop-default">Shop</a><span
                        class="sub-toggle"></span>
                    <div class="mega-menu">
                        <div class="mega-menu__column">
                            <h4>Catalog Pages<span class="sub-toggle"></span></h4>
                            <ul class="mega-menu__list">
                                <li><a href="shop-default.html">Shop Default</a>
                                </li>
                                <li><a href="shop-default.html">Shop Fullwidth</a>
                                </li>
                                <li><a href="shop-categories.html">Shop Categories</a>
                                </li>
                                <li><a href="shop-sidebar.html">Shop Sidebar</a>
                                </li>
                                <li><a href="shop-sidebar-without-banner.html">Shop Without Banner</a>
                                </li>
                                <li><a href="shop-carousel.html">Shop Carousel</a>
                                </li>
                            </ul>
                        </div>
                        <div class="mega-menu__column">
                            <h4>Product Layout<span class="sub-toggle"></span></h4>
                            <ul class="mega-menu__list">
                                <li><a href="product-default.html">Default</a>
                                </li>
                                <li><a href="product-extend.html">Extended</a>
                                </li>
                                <li><a href="product-full-content.html">Full Content</a>
                                </li>
                                <li><a href="product-box.html">Boxed</a>
                                </li>
                                <li><a href="product-sidebar.html">Sidebar</a>
                                </li>
                                <li><a href="product-default.html">Fullwidth</a>
                                </li>
                            </ul>
                        </div>
                        <div class="mega-menu__column">
                            <h4>Product Types<span class="sub-toggle"></span></h4>
                            <ul class="mega-menu__list">
                                <li><a href="product-default.html">Simple</a>
                                </li>
                                <li><a href="product-default.html">Color Swatches</a>
                                </li>
                                <li><a href="product-image-swatches.html">Images Swatches</a>
                                </li>
                                <li><a href="product-countdown.html">Countdown</a>
                                </li>
                                <li><a href="product-multi-vendor.html">Multi-Vendor</a>
                                </li>
                                <li><a href="product-instagram.html">Instagram</a>
                                </li>
                                <li><a href="product-affiliate.html">Affiliate</a>
                                </li>
                                <li><a href="product-on-sale.html">On sale</a>
                                </li>
                                <li><a href="product-video.html">Video Featured</a>
                                </li>
                                <li><a href="product-groupped.html">Grouped</a>
                                </li>
                                <li><a href="product-out-stock.html">Out Of Stock</a>
                                </li>
                            </ul>
                        </div>
                        <div class="mega-menu__column">
                            <h4>Woo Pages<span class="sub-toggle"></span></h4>
                            <ul class="mega-menu__list">
                                <li><a href="shopping-cart.html">Shopping Cart</a>
                                </li>
                                <li><a href="checkout.html">Checkout</a>
                                </li>
                                <li><a href="whishlist.html">Whishlist</a>
                                </li>
                                <li><a href="compare.html">Compare</a>
                                </li>
                                <li><a href="order-tracking.html">Order Tracking</a>
                                </li>
                                <li><a href="my-account.html">My Account</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </li>
                <li class="menu-item-has-children has-mega-menu"><a href="">Pages</a><span
                        class="sub-toggle"></span>
                    <div class="mega-menu">
                        <div class="mega-menu__column">
                            <h4>Basic Page<span class="sub-toggle"></span></h4>
                            <ul class="mega-menu__list">
                                <li><a href="about-us.html">About Us</a>
                                </li>
                                <li><a href="contact-us.html">Contact</a>
                                </li>
                                <li><a href="faqs.html">Faqs</a>
                                </li>
                                <li><a href="comming-soon.html">Comming Soon</a>
                                </li>
                                <li><a href="404-page.html">404 Page</a>
                                </li>
                            </ul>
                        </div>
                        <div class="mega-menu__column">
                            <h4>Vendor Pages<span class="sub-toggle"></span></h4>
                            <ul class="mega-menu__list">
                                <li><a href="become-a-vendor.html">Become a Vendor</a>
                                </li>
                                <li><a href="vendor-store.html">Vendor Store</a>
                                </li>
                                <li><a href="vendor-dashboard-free.html">Vendor Dashboard Free</a>
                                </li>
                                <li><a href="vendor-dashboard-pro.html">Vendor Dashboard Pro</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </li>
                <li class="menu-item-has-children has-mega-menu"><a href="">Blogs</a><span
                        class="sub-toggle"></span>
                    <div class="mega-menu">
                        <div class="mega-menu__column">
                            <h4>Blog Layout<span class="sub-toggle"></span></h4>
                            <ul class="mega-menu__list">
                                <li><a href="blog-grid.html">Grid</a>
                                </li>
                                <li><a href="blog-list.html">Listing</a>
                                </li>
                                <li><a href="blog-small-thumb.html">Small Thumb</a>
                                </li>
                                <li><a href="blog-left-sidebar.html">Left Sidebar</a>
                                </li>
                                <li><a href="blog-right-sidebar.html">Right Sidebar</a>
                                </li>
                            </ul>
                        </div>
                        <div class="mega-menu__column">
                            <h4>Single Blog<span class="sub-toggle"></span></h4>
                            <ul class="mega-menu__list">
                                <li><a href="blog-detail.html">Single 1</a>
                                </li>
                                <li><a href="blog-detail-2.html">Single 2</a>
                                </li>
                                <li><a href="blog-detail-3.html">Single 3</a>
                                </li>
                                <li><a href="blog-detail-4.html">Single 4</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </li>
            </ul>
        </div>
    </div>
    <div id="loader-wrapper">
        <div class="loader-section section-left"></div>
        <div class="loader-section section-right"></div>
    </div>
    <div class="ps-search" id="site-search"><a class="ps-btn--close" href="#"></a>
        <div class="ps-search__content">
            <form class="ps-form--primary-search" action="do_action" method="post">
                <input class="form-control" type="text" placeholder="Search for...">
                <button><i class="aroma-magnifying-glass"></i></button>
            </form>
        </div>
    </div>
    <div class="modal fade" id="product-quickview" tabindex="-1" role="dialog"
        aria-labelledby="product-quickview" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content"><span class="modal-close" data-dismiss="modal"><i
                        class="icon-cross2"></i></span>
                <article class="ps-product--detail ps-product--fullwidth ps-product--quickview">
                    <div class="ps-product__header">
                        <div class="ps-product__thumbnail" data-vertical="false">
                            <div class="ps-product__images" data-arrow="true">
                                <div class="item"><img src="img/products/detail/fullwidth/1.jpg" alt="">
                                </div>
                                <div class="item"><img src="img/products/detail/fullwidth/2.jpg" alt="">
                                </div>
                                <div class="item"><img src="img/products/detail/fullwidth/3.jpg" alt="">
                                </div>
                            </div>
                        </div>
                        <div class="ps-product__info">
                            <h1>Marshall Kilburn Portable Wireless Speaker</h1>
                            <div class="ps-product__meta">
                                <p>Brand:<a href="shop-default.html">Sony</a></p>
                                <div class="ps-product__rating">
                                    <select class="ps-rating" data-read-only="true">
                                        <option value="1">1</option>
                                        <option value="1">2</option>
                                        <option value="1">3</option>
                                        <option value="1">4</option>
                                        <option value="2">5</option>
                                    </select><span>(1 review)</span>
                                </div>
                            </div>
                            <h4 class="ps-product__price">$36.78 – $56.99</h4>
                            <div class="ps-product__desc">
                                <p>Sold By:<a href="shop-default.html"><strong> Go Pro</strong></a></p>
                                <ul class="ps-list--dot">
                                    <li> Unrestrained and portable active stereo speaker</li>
                                    <li> Free from the confines of wires and chords</li>
                                    <li> 20 hours of portable capabilities</li>
                                    <li> Double-ended Coil Cord with 3.5mm Stereo Plugs Included</li>
                                    <li> 3/4″ Dome Tweeters: 2X and 4″ Woofer: 1X</li>
                                </ul>
                            </div>
                            <div class="ps-product__shopping"><a class="ps-btn ps-btn--black" href="#">Add to
                                    cart</a><a class="ps-btn" href="#">Buy Now</a>
                                <div class="ps-product__actions"><a href="#"><i class="icon-heart"></i></a><a
                                        href="#"><i class="icon-chart-bars"></i></a></div>
                            </div>
                        </div>
                    </div>
                </article>
            </div>
        </div>
    </div>

    <script src="{{ asset('/js/jquery.min.js') }}"></script>
    <script src="{{ asset('/js/nouislider.min.js') }}"></script>
    <script src="{{ asset('/js/owl.carousel.min.js') }}"></script>
    <script src="{{ asset('/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('/js/slick.min.js') }}"></script>
    <script src="{{ asset('/js/slick.min.js') }}"></script>
    <script src="{{ asset('/js/jquery.barrating.min.js') }}"></script>
    <script src="{{ asset('/js/lightgallery-all.min.js') }}"></script>
    <script src="{{ asset('/js/sticky-sidebar.min.js') }}"></script>
    <script src="{{ asset('/js/select2.full.min.js') }}"></script>
    <script src="{{ asset('/js/main.js') }}"></script>
    <script>
        $(document).ready(function() {
            $("#btn-search").click(function() {
                var query = $("#query-search").val();
                if (!query) {
                    $("#box-search").stop().animate({
                        opacity: 0
                    }, 1);
                    return
                }
                let html = '';
                $.ajax({
                    url: "{{ route('api.search') }}",
                    method: "POST",
                    data: {
                        search: query
                    },
                    success: function(data) {
                        // console.log(data)
                        if (data.success) {
                            let toko = data.stores;
                            let produk = data.products;
                            if (toko.length == 0 && produk.length == 0) {
                                html +=
                                    '<div class="ps-cart-item"><p>Tidak ada hasil</p></div>';
                            }
                            if (toko.length > 0) {
                                html +=
                                    '<div class="ps-block--shop-features"><div class="ps-block__header justify-content-start align-items-center"><i class="mb-3 fa fa-shopping-basket" aria-hidden="true"></i>';
                                html +=
                                    '<h5 class="mb-3 ml-2">Toko </h5></div><div class="ps-block__content" ><ul class = "ps-list--link" >';
                                toko.forEach(function(item) {
                                    html += '<li><a href="/toko/' + item.prefix + '">' +
                                        item.nama_toko + '</a></li>';
                                });
                                html += '</ul></div></div>';
                            }
                            if (produk.length > 0) {
                                html +=
                                    '<div class="ps-block--shop-features"><div class="ps-block__header justify-content-start align-items-center"><i class="mb-3 fa fa-shopping-bag" aria-hidden="true"></i>';
                                html +=
                                    '<h5 class="mb-3 ml-2">Produk </h5></div><div class="ps-block__content" ><ul class = "ps-list--link" >';
                                produk.forEach(function(item) {
                                    html += '<li><a href="/produk/' + item.prefix +
                                        '">' +
                                        item.nama_produk + '</a></li>';
                                });
                                html += '</ul></div></div>';
                            }
                            $("#box-search").html(html);
                        }
                        // $("#box-search").html(data);
                        $("#box-search").stop().animate({
                            opacity: 1
                        }, 1);
                    }
                });
            });
        });

        const dropdown_menu = document.querySelector('#box-search');
        const dropdown_li = document.querySelector('#btn-search');

        dropdown_li.addEventListener('click', () => {
            document.addEventListener('mouseup', function(e) {
                if (!dropdown_menu.contains(e.target)) {
                    dropdown_menu.style.opacity = 0;
                }
            });

        });
    </script>

    @stack('customScript')
</body>

</html>
