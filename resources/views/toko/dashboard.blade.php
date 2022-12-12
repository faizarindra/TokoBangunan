@extends('toko.layouts.main')

@section('title', 'Dashboard')

@section('content_toko')
    <div class="ps-section__header">
        <h3>Dashboard</h3>
    </div>
    <div class="ps-section__content">
        <div class="row">
            <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6 col-12 ">
                <div class="ps-block--dashboard">
                    <div class="ps-block__left"><i class="icon-bag2"></i></div>
                    <div class="ps-block__right">
                        <h3>{{ $total_product }}</h3>
                        <p>Produk</p>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6 col-12 ">
                <div class="ps-block--dashboard">
                    <div class="ps-block__left"><i class="icon-chart-bars"></i></div>
                    <div class="ps-block__right">
                        <h3>{{ $total_pesanan }}</h3>
                        <p>Pesanan</p>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6 col-12 ">
                <div class="ps-block--dashboard">
                    <div class="ps-block__left"><i class="icon-rocket"></i></div>
                    <div class="ps-block__right">
                        <h3>{{ $total_review }}</h3>
                        <p>Ulasan</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
