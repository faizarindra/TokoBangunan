@extends('profile.layouts.main')

@section('title', 'Notifikasi')

@section('content_user')
    <div class="ps-shopping-product">
        <div class="ps-form--account-setting">
            <div class="ps-form__header">
                @if (session()->has('success'))
                    <div class="alert alert-warning py-3">
                        {{ session('success') }}
                    </div>
                @endif
                <div class="d-flex flex-row align-items-center mb-4">
                    <h3 class="mb-0"> Notifikasi</h3>
                </div>
            </div>
        </div>
        <div class="row">
            @forelse ($notifikasis as $notifikasi)
                <div class="col-md-12">
                    <div class="alert alert-{{ $notifikasi->bg_color }}" role="alert">
                        <i class="{{ $notifikasi->icon }} mr-2"></i> {{ $notifikasi->pesan }}
                    </div>
                </div>
            @empty
                <div class="col-md-12">
                    <div class="alert alert-danger text-center" role="alert">
                        <i class="fa fa-exclamation-triangle mr-2"></i> Tidak ada notifikasi
                    </div>
                </div>
            @endforelse
        </div>
    </div>
    <div class="ps-pagination">
        {{ $notifikasis->links() }}
    </div>
@endsection
