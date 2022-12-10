@extends('toko.layouts.main')

@section('title', 'Edit Toko')

@section('content_toko')
    <form class="ps-form--account-setting" action="{{ url('seller/edit-toko') }}" method="POST" autocomplete="off"
        enctype="multipart/form-data">
        @csrf
        @method('PATCH')
        <div class="ps-form__header">
            @if (session()->has('success'))
                <div class="alert alert-warning py-3">
                    {{ session('success') }}
                </div>
            @endif
            <h3> Edit Toko</h3>
        </div>
        <div class="ps-form__content">
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label>Nama Toko</label>
                        <input type="hidden" name="id" value="{{ $toko->id }}">
                        <input class="form-control" type="text" placeholder="Masukkan Nama Toko" name="nama_toko"
                            value="{{ $toko->nama_toko }}">
                        @error('nama_toko')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label>Alamat</label>
                        <input class="form-control" type="text" placeholder="Masukkan Alamat" name="alamat_toko"
                            value="{{ $toko->alamat_toko }}">
                        @error('alamat_toko')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="col-sm-12">
                    <div class="form-group">
                        <label>Deskripsi</label>
                        <textarea class="form-control" rows="6" placeholder="Masukkan Deskripsi" name="deskripsi_toko">{{ $toko->deskripsi_toko }}</textarea>
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
                                <option value="{{ $provinsi->province_id . '#' . $provinsi->province }}"
                                    @if (explode('#', $toko->id_provinsi)[0] == $provinsi->province_id) selected @endif>
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
                            @foreach ($kotas as $kota)
                                <option value="{{ $kota->city_id . '#' . $kota->type . ' ' . $kota->city_name }}"
                                    @if (explode('#', $toko->id_kota)[0] == $kota->city_id) selected @endif>
                                    {{ $kota->type }} {{ $kota->city_name }}</option>
                            @endforeach
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
                            @foreach ($kecamatans as $kecamatan)
                                <option value="{{ $kecamatan->subdistrict_id . '#' . $kecamatan->subdistrict_name }}"
                                    @if (explode('#', $toko->id_kecamatan)[0] == $kecamatan->subdistrict_id) selected @endif>
                                    {{ $kecamatan->subdistrict_name }}</option>
                            @endforeach
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
                        <span class="text-danger">* Kosongkan jika tidak ingin mengubah gambar</span>
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
