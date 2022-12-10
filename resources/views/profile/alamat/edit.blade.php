@extends('profile.layouts.main')

@section('content_user')
    <form class="ps-form--account-setting" action="{{ url('profile/alamat') }}" method="POST" autocomplete="off">
        @csrf
        @method('PATCH')
        <div class="ps-form__header">
            @if (session()->has('success'))
                <div class="alert alert-warning py-3">
                    {{ session('success') }}
                </div>
            @endif
            <h3> Edit Alamat</h3>
        </div>
        <div class="ps-form__content">
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label>Nama Penerima</label>
                        <input type="hidden" name="id" value="{{ $alamat->id }}">
                        <input class="form-control" type="text" placeholder="Masukkan Nama Penerima" name="nama_penerima"
                            value="{{ $alamat->nama_penerima }}">
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label>Nomor Penerima</label>
                        <input class="form-control" type="text" placeholder="Masukkan Nomor Penerima" name="no_hp"
                            value="{{ $alamat->no_hp }}">
                    </div>
                </div>
                <div class="col-sm-12">
                    <div class="form-group">
                        <label>Alamat</label>
                        <textarea class="form-control" name="alamat" id="alamat" cols="30" rows="5"
                            placeholder="Masukkan Alamat">{{ $alamat->alamat }}</textarea>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label>Provinsi</label>
                        <select class="form-control" name="provinsi">
                            <option value="">--Pilih Provinsi--</option>
                            @foreach ($provinsis as $provinsi)
                                <option value="{{ $provinsi->province_id . '#' . $provinsi->province }}"
                                    @if (explode('#', $alamat->id_provinsi)[0] == $provinsi->province_id) selected @endif>
                                    {{ $provinsi->province }}</option>
                            @endforeach
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
                                    @if (explode('#', $alamat->id_kota)[0] == $kota->city_id) selected @endif>
                                    {{ $kota->type }} {{ $kota->city_name }}</option>
                            @endforeach
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
                                    @if (explode('#', $alamat->id_kecamatan)[0] == $kecamatan->subdistrict_id) selected @endif>
                                    {{ $kecamatan->subdistrict_name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label>Kode Pos</label>
                        <input class="form-control" type="text" placeholder="Masukkan Kode Pos" name="kode_pos"
                            value="{{ $alamat->kode_pos }}">
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
