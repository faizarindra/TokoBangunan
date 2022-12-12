@extends('layouts.app')

@section('title', 'Profile')

@section('content')
    <section class="ps-section--account">
        <div class="container">
            <div class="row">
                <div class="col-lg-4">
                    <div class="ps-section__left">
                        <aside class="ps-widget--account-dashboard">
                            <div class="ps-widget__header"><img
                                    src="{{ $auth->image ? asset('img/pp/' . $auth->image) : 'http://nouthemes.net/html/martfury/img/users/3.jpg' }}"
                                    alt=""
                                    style="height:60px;width:60px;object-fit:cover;flex-basis:71px;max-width:70px">
                                <figure>
                                    <figcaption>Halo, {{ $auth->name }}</figcaption>
                                    <p><a href="#">{{ $auth->email }}</a></p>
                                </figure>
                            </div>
                            <div class="ps-widget__content">
                                <ul>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                        style="display: none;">
                                        @csrf
                                    </form>
                                    <li class="{{ Request::is('profile') ? 'active' : '' }}"><a
                                            href="{{ url('profile') }}"><i class="icon-user"></i> Informasi
                                            Akun</a>
                                    </li>
                                    <li class="{{ Request::is('profile/notifikasi') ? 'active' : '' }}"><a
                                            href="{{ url('profile/notifikasi') }}"><i class="icon-alarm-ringing"></i>
                                            Notifikasi</a></li>
                                    <li class="{{ Request::is('profile/pesanan') ? 'active' : '' }}"><a
                                            href="{{ url('profile/pesanan') }}"><i class="icon-papers"></i> Pesanan</a></li>
                                    <li class="{{ Request::is('profile/pembayaran') ? 'active' : '' }}"><a
                                            href="{{ url('profile/pembayaran') }}"><i class="icon-cash-dollar"></i>
                                            Pembayaran</a></li>
                                    <li
                                        class="{{ Request::is('profile/alamat') || Request::is('profile/tambah-alamat') ? 'active' : '' }}">
                                        <a href="{{ url('profile/alamat') }}"><i class="icon-map-marker"></i> Alamat
                                            Saya</a>
                                    </li>
                                    <li class="{{ Request::is('profile/terakhir-dilihat') ? 'active' : '' }}"><a
                                            href="{{ url('profile/terakhir-dilihat') }}"><i class="icon-eye"></i> Terakhir
                                            Dilihat</a></li>
                                    <li>
                                        <a target="blank" href="{{ url('seller') }}"><i class="icon-store"></i>
                                            Toko
                                            Saya</a>
                                    </li>
                                    {{-- <li><a href="#"><i class="icon-heart"></i> Favorite</a></li> --}}
                                    <li><a style="cursor: pointer" aria-current="page"
                                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i
                                                class="icon-power-switch"></i>Logout</a></li>
                                </ul>
                            </div>
                        </aside>
                    </div>
                </div>
                <div class="col-lg-8">
                    <div class="ps-section__right">
                        @yield('content_user')
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal -->
        <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
            aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="staticBackdropLabel">Upload Foto</h5>
                    </div>
                    <div class="modal-body">
                        <form action="{{ url('profile/photo') }}" method="post" id="change-pp-form"
                            enctype="multipart/form-data">
                            @csrf
                            @method('patch')
                            <input type="file" name="image" id="image" />
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="ps-btn p-3" data-bs-dismiss="modal"
                            style="font-size: 14px;font-weight:normal">Batalkan</button>
                        <button type="button" class="ps-btn p-3 bg-primary" style="font-size: 14px;font-weight:normal"
                            onclick="event.preventDefault(); document.getElementById('change-pp-form').submit();">Simpan</button>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
