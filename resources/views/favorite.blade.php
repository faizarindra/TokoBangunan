@extends('layouts.app')

@section('title', 'Favorite')

@section('content')
    <div class="ps-page--simple">
        <div class="ps-breadcrumb">
            <div class="container">
                <ul class="breadcrumb">
                    <li><a href="index.html">Home</a></li>
                    <li><a href="shop-default.html">Shop</a></li>
                    <li>Whishlist</li>
                </ul>
            </div>
        </div>
        <div class="ps-section--shopping ps-shopping-cart">
            <div class="container">
                <div class="ps-section__header">
                    <h1>Favorite</h1>
                </div>
                <div class="ps-section__content">
                    <div class="table-responsive">
                        <table class="table ps-table--shopping-cart ps-table--responsive">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($favorites as $favorite)
                                    <tr>
                                        <td data-label="Product">
                                            <div class="ps-product--cart">
                                                <div class="ps-product__thumbnail">
                                                    <a href="{{ url('produk/' . $favorite->prefix) }}">
                                                        <img src="{{ asset('img/produk/' . $favorite->gambar_produk) }}"
                                                            alt="">
                                                    </a>
                                                </div>
                                                <div class="ps-product__content">
                                                    <a href="{{ url('produk/' . $favorite->prefix) }}">
                                                        {{ $favorite->nama_produk }}</a>
                                                    <p class="text-left">Dijual oleh:<strong>
                                                            {{ $favorite->nama_toko }}</strong>
                                                    </p>
                                                </div>
                                            </div>
                                        </td>
                                        <td data-label="Actions">
                                            <form action="{{ url('favorite') }}" method="POST" id="favorite-delete-form">
                                                @csrf
                                                @method('DELETE')
                                                <input type="hidden" name="id_favorite"
                                                    value="{{ $favorite->id_favorite }}">
                                            </form>
                                            <a style="cursor: pointer"
                                                onclick="event.preventDefault(); document.getElementById('favorite-delete-form').submit();">
                                                <i class="icon-cross"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="2">
                                            <h3 class="text-center">Tidak ada produk yang disimpan</h3>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
