@extends('toko.layouts.main')

@section('title', 'Buat Toko')

@section('content_toko')
    <form class="ps-form--account-setting" action="{{ url('seller/buat-toko') }}" method="POST" autocomplete="off"
        enctype="multipart/form-data">
        @csrf
        <div class="ps-form__header">
            @if (session()->has('success'))
                <div class="alert alert-warning py-3">
                    {{ session('success') }}
                </div>
            @endif
            <h3> Buat Toko</h3>
        </div>
        <div class="ps-form__content">
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label>Nama Toko</label>
                        <input class="form-control" type="text" placeholder="Masukkan Nama Toko" name="nama_toko"
                            value="{{ old('nama_toko') }}">
                        @error('nama_toko')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label>Alamat</label>
                        <input class="form-control" type="text" placeholder="Masukkan Alamat" name="alamat_toko"
                            value="{{ old('alamat_toko') }}">
                        @error('alamat_toko')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="col-sm-12">
                    <div class="form-group">
                        <label>Deskripsi</label>
                        <textarea class="form-control" rows="6" placeholder="Masukkan Deskripsi" name="deskripsi_toko">{{ old('deskripsi_toko') }}</textarea>
                        @error('deskripsi_toko')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label>Provinsi</label>
                        <select class="form-control" name="provinsi">
                            <option value="">--Pilih Provinsi--</option>
                            @foreach ($provinsis as $provinsi)
                                <option value="{{ $provinsi->province_id . '#' . $provinsi->province }}">
                                    {{ $provinsi->province }}</option>
                            @endforeach
                            @error('provinsi')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </select>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label>Kabupaten/Kota</label>
                        <select class="form-control" name="kota">
                            <option value="">--Pilih Kabupaten/Kota--</option>
                            @error('kota')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </select>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label>Kecamatan</label>
                        <select class="form-control" name="kecamatan">
                            <option value="">--Pilih Kecamatan--</option>
                            @error('kecamatan')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </select>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label>Gambar Toko</label>
                        <input class="form-control-file" type="file" name="gambar_toko">
                        @error('gambar_toko')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>
        </div>
        <div class="form-group submit">
            <button class="ps-btn">Simpan</button>
        </div>
    </form>
@endsection

@push('customScript')
    <script>
        $(document).ready(function() {
            $('select[name="provinsi"]').on('change', function() {
                var provinceId = $(this).val();
                if (provinceId) {
                    $.ajax({
                        url: '/api/kota/' + provinceId,
                        type: "GET",
                        dataType: "json",
                        success: function(data) {
                            $('select[name="kota"]').empty();
                            $.each(data.data, function(key, value) {
                                $('select[name="kota"]').append(
                                    '<option value="' + value.id + '#' + value
                                    .nama + '">' + value
                                    .nama +
                                    '</option>');
                            });
                        },
                    });
                } else {
                    $('select[name="kota"]').empty();
                }
            });
        });

        $(document).ready(function() {
            $('select[name="kota"]').on('change', function() {
                var cityId = $(this).val();
                if (cityId) {
                    $.ajax({
                        url: '/api/kecamatan/' + cityId,
                        type: "GET",
                        dataType: "json",
                        success: function(data) {
                            $('select[name="kecamatan"]').empty();
                            $.each(data.data, function(key, value) {
                                $('select[name="kecamatan"]').append(
                                    '<option value="' + value.id + '#' + value
                                    .nama + '">' + value
                                    .nama +
                                    '</option>');
                            });
                        },
                    });
                } else {
                    $('select[name="kecamatan"]').empty();
                }
            });
        });
    </script>
@endpush
