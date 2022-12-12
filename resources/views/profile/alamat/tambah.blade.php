@extends('profile.layouts.main')

@section('title', 'Tambah Alamat')

@section('content_user')
    <form class="ps-form--account-setting" action="{{ url('/profile/tambah-alamat') }}" method="POST" autocomplete="off">
        @csrf
        <div class="ps-form__header">
            @if (session()->has('success'))
                <div class="alert alert-warning py-3">
                    {{ session('success') }}
                </div>
            @endif
            <h3> Tambah Alamat</h3>
        </div>
        <div class="ps-form__content">
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label>Nama Penerima</label>
                        <input class="form-control" type="text" placeholder="Masukkan Nama Penerima" name="nama_penerima"
                            value="{{ old('nama_penerima') }}">
                        @error('nama_penerima')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label>Nomor Penerima</label>
                        <input class="form-control" type="text" placeholder="Masukkan Nomor Penerima" name="no_hp"
                            value="{{ old('no_hp') }}">
                        @error('no_hp')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-sm-12">
                    <div class="form-group">
                        <label>Alamat</label>
                        <textarea class="form-control" name="alamat" id="alamat" cols="30" rows="5"
                            placeholder="Masukkan Alamat">{{ old('alamat') }}</textarea>
                        @error('alamat')
                            <div class="text-danger">{{ $message }}</div>
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
                        </select>
                        @error('provinsi')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label>Kabupaten/Kota</label>
                        <select class="form-control" name="kota">
                            <option value="">--Pilih Kabupaten/Kota--</option>
                        </select>
                        @error('kota')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label>Kecamatan</label>
                        <select class="form-control" name="kecamatan">
                            <option value="">--Pilih Kecamatan--</option>
                        </select>
                        @error('kecamatan')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label>Kode Pos</label>
                        <input class="form-control" type="text" placeholder="Masukkan Kode Pos" name="kode_pos"
                            value="{{ old('kode_pos') }}">
                        @error('kode_pos')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-sm-12">
                    <div class="form-group">
                        <div class="custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input" id="customSwitch1" name="is_main">
                            <label class="custom-control-label" for="customSwitch1">Jadikan Alamat Utama?</label>
                        </div>
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
