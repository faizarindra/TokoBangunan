@extends('layouts.app')

@section('title', 'Detail Produk')

@section('content')
    <div class="ps-page--product">
        <div class="container">
            <div class="ps-page__container">
                <div class="ps-page__left">
                    <div class="ps-product--detail">
                        <div class="ps-product__header">
                            <div class="ps-product__thumbnail" data-vertical="false">
                                <figure>
                                    <div class="ps-wrapper">
                                        <div class="ps-product__gallery slick-initialized slick-slider" data-arrow="true"><a
                                                href="#" class="slick-arrow slick-disabled" aria-disabled="true"
                                                style=""><i class="fa fa-angle-left"></i></a>
                                            <div aria-live="polite" class="slick-list draggable">
                                                <div class="slick-track" role="listbox" style="opacity: 1; width: 1137px;">
                                                    <div class="item slick-slide slick-current slick-active"
                                                        data-slick-index="0" aria-hidden="false" tabindex="-1"
                                                        role="option" aria-describedby="slick-slide00"
                                                        style="width: 379px; position: relative; left: 0px; top: 0px; z-index: 999; opacity: 1;">
                                                        <a href="{{ asset('img/produk/' . $product->gambar_produk) }}"
                                                            tabindex="0"><img
                                                                src="{{ asset('img/produk/' . $product->gambar_produk) }}"
                                                                alt=""></a>
                                                    </div>
                                                </div>
                                            </div>


                                            <a href="#" class="slick-arrow" style="" aria-disabled="false"><i
                                                    class="fa fa-angle-right"></i></a>
                                        </div>
                                    </div>
                                </figure>
                            </div>
                            <div class="ps-product__info">
                                <h1>{{ $product->nama_produk }}</h1>
                                <div class="ps-product__meta">
                                    <p>Brand:<a href="{{ url('merk/' . $brand->prefix) }}">{{ $brand->nama_merk }}</a></p>
                                    <div class="ps-product__rating">
                                        <div class="br-wrapper br-theme-fontawesome-stars"><select class="ps-rating"
                                                data-read-only="true" style="display: none;">
                                                <option value="1">1</option>
                                                <option value="1">2</option>
                                                <option value="1">3</option>
                                                <option value="1">4</option>
                                                <option value="2">5</option>
                                            </select>
                                            <div class="br-widget br-readonly">
                                                <div class="br-widget br-readonly"> {!! str_repeat(
                                                    '<a href="#" data-rating-value="1" data-rating-text="1" class="br-selected br-current"></a>',
                                                    $reviews,
                                                ) !!}
                                                    {!! str_repeat('<a href="#" data-rating-value="2" data-rating-text="5"></a>', 5 - floor($reviews)) !!}
                                                    <div class="br-current-rating">{{ $reviews }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div><span>({{ $countReviews < 100 ? $countReviews : '99+' }} ulasan)</span>
                                    </div>
                                </div>
                                <h4 class="ps-product__price">@currency($product->harga_produk)</h4>
                                <div class="ps-product__desc">
                                    <p>Dijual oleh:<a href={{ url('toko/' . $toko->prefix) }}><strong>
                                                {{ $toko->nama_toko }}</strong></a>
                                    </p>
                                </div>

                                <div class="ps-product__shopping extend" style="padding-bottom:0px">
                                    <div class="ps-product__btn-group">
                                        <div class="page-wrapper">
                                            <button style="cursor: pointer;min-width:293px" {{-- onclick="event.preventDefault(); document.getElementById('keranjang-form').submit();" --}}
                                                id="addtocart" class="ps-btn ps-btn--black d-block mr-4" href="#">
                                                <div class="text-state">
                                                    Tambahkan ke keranjang
                                                </div>
                                                <span class="cart-item"></span>
                                                <div class="spinner-state">
                                                    <div class="spinner-border text-light" role="status">
                                                        <span class="sr-only">Loading...</span>
                                                    </div>
                                                </div>
                                            </button>
                                        </div>
                                        <form action="{{ url('keranjang') }}" method="post" id="keranjang-form">
                                            @csrf
                                            <input type="hidden" name="id_produk" id="id_produk"
                                                value="{{ $product->id }}">

                                            <input type="hidden" name="id_user" id="id_user"
                                                value="{{ \Illuminate\Support\Facades\Auth::user() ? \Illuminate\Support\Facades\Auth::user()->id : null }}">
                                        </form>
                                        <div class="ps-product__actions">
                                            <form action="{{ url('favorite') }}" method="post" id="favorite-form">
                                                @csrf
                                                <input type="hidden" name="id_produk" value="{{ $product->id }}">
                                                <a style="cursor: pointer"
                                                    onclick="event.preventDefault(); document.getElementById('favorite-form').submit();">
                                                    @if ($favorite == 0)
                                                        <i class="icon-heart"></i>
                                                    @elseif ($favorite == 1)
                                                        <img height="26px" width="26px"
                                                            src="{{ asset('img/heart.svg') }}" alt="My Happy SVG" />
                                                    @endif
                                                </a>
                                            </form>
                                            {{-- <a href="#"><i class="icon-chart-bars"></i></a> --}}
                                        </div>
                                    </div>
                                    {{-- <a class="ps-btn" href="#">Buy Now</a> --}}
                                </div>
                            </div>
                        </div>
                        <div class="ps-product__content ps-tab-root">
                            <ul class="ps-tab-list">
                                <li class="active"><a href="#tab-1">Deskripsi</a></li>
                                <li class=""><a href="#tab-4">Ulasan
                                        ({{ $countReviews < 100 ? $countReviews : '99+' }})</a></li>
                            </ul>
                            <div class="ps-tabs">
                                <div class="ps-tab active" id="tab-1">
                                    <div class="ps-document">
                                        {{ $product->deskripsi }}
                                    </div>
                                </div>
                                <div class="ps-tab" id="tab-4">
                                    <div class="row">
                                        <div class="col-xl-5 col-lg-5 col-md-12 col-sm-12 col-12 ">
                                            <div class="ps-block--average-rating">
                                                <div class="ps-block__header">
                                                    <h3>{{ number_format((float) $reviews, 2, '.', '') }}</h3>
                                                    <div class="br-wrapper br-theme-fontawesome-stars">
                                                        <div class="br-widget br-readonly"> {!! str_repeat(
                                                            '<a href="#" data-rating-value="1" data-rating-text="1" class="br-selected br-current"></a>',
                                                            $reviews,
                                                        ) !!}
                                                            {!! str_repeat('<a href="#" data-rating-value="2" data-rating-text="5"></a>', 5 - floor($reviews)) !!}
                                                            <div class="br-current-rating">{{ $reviews }}
                                                            </div>
                                                        </div>
                                                    </div><span>{{ $countReviews < 100 ? $countReviews : '99+' }}
                                                        Review</span>
                                                </div>
                                                <div class="ps-block__star"><span>5 Star</span>
                                                    <div class="ps-progress" data-value="100"><span
                                                            style="width: 100%;"></span></div><span>100%</span>
                                                </div>
                                                <div class="ps-block__star"><span>4 Star</span>
                                                    <div class="ps-progress" data-value="0"><span
                                                            style="width: 0%;"></span>
                                                    </div><span>0</span>
                                                </div>
                                                <div class="ps-block__star"><span>3 Star</span>
                                                    <div class="ps-progress" data-value="0"><span
                                                            style="width: 0%;"></span>
                                                    </div><span>0</span>
                                                </div>
                                                <div class="ps-block__star"><span>2 Star</span>
                                                    <div class="ps-progress" data-value="0"><span
                                                            style="width: 0%;"></span>
                                                    </div><span>0</span>
                                                </div>
                                                <div class="ps-block__star"><span>1 Star</span>
                                                    <div class="ps-progress" data-value="0"><span
                                                            style="width: 0%;"></span></div><span>0</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xl-7 col-lg-7 col-md-12 col-sm-12 col-12 ">
                                            <form class="ps-form--review" action="index.html" method="get">
                                                <h4>Submit Your Review</h4>
                                                <p>Your email address will not be published. Required fields are
                                                    marked<sup>*</sup></p>
                                                <div class="form-group form-group__rating">
                                                    <label>Your rating of this product</label>
                                                    <div class="br-wrapper br-theme-fontawesome-stars"><select
                                                            class="ps-rating" data-read-only="false"
                                                            style="display: none;">
                                                            <option value="0">0</option>
                                                            <option value="1">1</option>
                                                            <option value="2">2</option>
                                                            <option value="3">3</option>
                                                            <option value="4">4</option>
                                                            <option value="5">5</option>
                                                        </select>
                                                        <div class="br-widget"><a href="#" data-rating-value="1"
                                                                data-rating-text="1"></a><a href="#"
                                                                data-rating-value="2" data-rating-text="2"></a><a
                                                                href="#" data-rating-value="3"
                                                                data-rating-text="3"></a><a href="#"
                                                                data-rating-value="4" data-rating-text="4"></a><a
                                                                href="#" data-rating-value="5"
                                                                data-rating-text="5"></a>
                                                            <div class="br-current-rating"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <textarea class="form-control" rows="6" placeholder="Write your review here"></textarea>
                                                </div>
                                                <div class="row">
                                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12  ">
                                                        <div class="form-group">
                                                            <input class="form-control" type="text"
                                                                placeholder="Your Name">
                                                        </div>
                                                    </div>
                                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12  ">
                                                        <div class="form-group">
                                                            <input class="form-control" type="email"
                                                                placeholder="Your Email">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group submit">
                                                    <button class="ps-btn">Submit Review</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="ps-page__right">
                    <aside class="widget widget_product widget_features">
                        <p><i class="icon-network"></i> Dikirim se kota Malang</p>
                        <p><i class="icon-receipt"></i> Penjual memberikan nota belanja</p>
                        <p><i class="icon-credit-card"></i> Bayar online atau COD ketika membeli</p>
                    </aside>
                    <aside class="widget widget_same-brand">
                        <h3>Brand Sejenis</h3>
                        <div class="widget__content">
                            @foreach ($sameBrands as $product)
                                <div class="ps-product">
                                    <div class="ps-product__thumbnail"><a href="{{ url('produk/' . $product->prefix) }}">
                                            <img src="{{ asset('img/produk/' . $product->gambar_produk) }}"
                                                alt="">
                                        </a>
                                    </div>
                                    <div class="ps-product__container"><a class="ps-product__vendor"
                                            href="{{ url('toko/' . $product->prefix_toko) }}">{{ $product->nama_toko }}</a>
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
                                                <span>{{ $product->countReviews < 100 ? $product->countReviews : '99+' }}</span>
                                            </div>
                                            <p class="ps-product__price sale">@currency($product->harga_produk)</p>
                                        </div>
                                        <div class="ps-product__content hover"><a class="ps-product__title"
                                                href="{{ url('produk/' . $product->prefix) }}">{{ $product->nama_produk }}</a>
                                            <p class="ps-product__price sale">@currency($product->harga_produk)</p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </aside>
                </div>
            </div>
            <div class="ps-section--default">
                <div class="ps-section__header">
                    <h3>Produk Serupa</h3>
                </div>
                <div class="ps-section__content">
                    <div class="ps-carousel--nav owl-slider owl-carousel owl-loaded owl-drag" data-owl-auto="true"
                        data-owl-loop="true" data-owl-speed="10000" data-owl-gap="30" data-owl-nav="true"
                        data-owl-dots="true" data-owl-item="6" data-owl-item-xs="2" data-owl-item-sm="2"
                        data-owl-item-md="3" data-owl-item-lg="4" data-owl-item-xl="5" data-owl-duration="1000"
                        data-owl-mousedrag="on">
                        <div class="owl-stage-outer">
                            <div class="owl-stage"
                                style="transform: translate3d(0px, 0px, 0px); transition: all 0s ease 0s; width: 1920px;">
                                @foreach ($relates as $product)
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
    </div>

@endsection

@push('customScript')
    <script>
        $(document).ready(function() {

            $('.spinner-state').hide()
            $('.text-state').show()

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

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

            $('#addtocart').on('click', function(e) {

                e.preventDefault();

                var button = $(this);
                var cart = $('#cart');
                var cartTotal = cart.attr('data-totalitems');
                var newCartTotal = parseInt(cartTotal) + 1;
                var display = $(".ps-cart__items");

                $.ajax({
                    type: "POST",
                    url: "/api/keranjang",
                    data: {
                        id_produk: $('#id_produk').val(),
                        id_user: $('#id_user').val()
                    },
                    beforeSend: function() {
                        // setting a timeout
                        // button.addClass('sendtocart')
                        $('.spinner-state').show();
                        $('.text-state').hide()
                    },
                    success: function(data) {
                        console.log(data)
                        display.empty()
                        if (data.success) {
                            button.addClass('sendtocart');
                            setTimeout(function() {
                                button.removeClass('sendtocart');
                                cart.addClass('shake').attr('data-totalitems',
                                    newCartTotal);
                                setTimeout(function() {
                                    cart.removeClass('shake');
                                }, 500)
                            }, 1000)
                            var total_price = data.data.cartNum.reduce(function(a, b) {
                                return a + b.total;
                            }, 0);
                            $('#sub-total_count').text(formatRupiah(total_price));
                            console.log(total_price)
                            for (var i = 0; i < data.data.cart.length; i++) {
                                display.append(
                                    "<div class='ps-product--cart-mobile'>" +
                                    "<div class='ps-product__thumbnail'>" +
                                    "<a href='#'>" +
                                    "<img src='" + data.data.cart[i].url_gambar +
                                    "' alt='' />" +
                                    "</a>" +
                                    "</div>" +
                                    "<div class='ps-product__content'>" +
                                    "<a class='ps-product__remove' href='#'>" +
                                    "<i class='icon-cross' id='remove-cart_items'></i>" +
                                    "</a>" +
                                    "<a href='" + data.data.cart[i].url_produk + "'>" +
                                    data.data.cart[i].nama_produk +
                                    "</a>" +
                                    "<p><strong>Oleh: </strong>" +
                                    data.data.cart[i].nama_toko +
                                    "</p>" +
                                    "<small>" + data.data.cart[i].qty + " x " +
                                    formatRupiah(data.data.cart[i].harga_produk) +
                                    "</small>" +
                                    "</div>" +
                                    "</div>"
                                );
                            }

                        } else {
                            if (data.msg == 'Unauthorized') {
                                window.location.href = "{{ route('login') }}";
                            }
                        }

                        $('.spinner-state').hide()
                        $('.text-state').show()
                    }
                })
            })

            $('#remove-cart_items').on('click', function(e) {
                e.preventDefault()
                console.log('dipencet hapus')
            })
        })
    </script>
@endpush
