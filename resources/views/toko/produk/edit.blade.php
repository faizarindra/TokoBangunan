@extends('toko.layouts.main')

@section('title', 'Edit Produk')

@section('content_toko')
    <form class="ps-form--account-setting" action="{{ url('seller/produk') }}" method="POST" autocomplete="off"
        enctype="multipart/form-data">
        @csrf
        @method('PATCH')
        <div class="ps-form__header">
            @if (session()->has('success'))
                <div class="alert alert-warning py-3">
                    {{ session('success') }}
                </div>
            @endif
            <h3> Edit Produk</h3>
        </div>
        <div class="ps-form__content">
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label>Nama Produk</label>
                        <input type="hidden" name="id" value="{{ $product->id }}">
                        <input class="form-control" type="text" placeholder="Masukkan Nama Porduk" name="nama_produk"
                            value="{{ $product->nama_produk }}">
                        @error('nama_produk')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label>Harga</label>
                        <input class="form-control" type="text" placeholder="Masukkan Harga" name="harga_produk"
                            value="{{ $product->harga_produk }}">
                        @error('harga_produk')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label>Satuan</label>
                        <input class="form-control" type="text" placeholder="Masukkan Satuan (Kg, Liter, dll)"
                            name="satuan_produk" value="{{ $product->satuan_produk }}">
                        @error('satuan_produk')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label>Stok</label>
                        <input class="form-control" type="text" placeholder="Masukkan Stok" name="stok_raw"
                            value="{{ $product->stok_raw }}">
                        @error('stok_raw')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="col-sm-12">
                    <div class="form-group">
                        <label>Deskripsi Produk</label>
                        <textarea class="form-control" name="deskripsi" id="deskripsi" cols="30" rows="5"
                            placeholder="Masukkan Deskripsi Produk">{{ $product->deskripsi }}</textarea>
                        @error('deskripsi')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label>Kategori</label>
                        <select class="form-control" name="id_kategori">
                            <option value="">--Pilih Kategori--</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}" @if ($product->id_kategori == $category->id) selected @endif>
                                    {{ $category->nama_kategori }}</option>
                            @endforeach
                        </select>
                        @error('id_kategori')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label>Merk</label>
                        <select class="form-control" name="id_merk">
                            <option value="">--Pilih Merk--</option>
                            @foreach ($merks as $merk)
                                <option value="{{ $merk->id }}" @if ($product->id_merk == $merk->id) selected @endif>
                                    {{ $merk->nama_merk }}</option>
                            @endforeach
                        </select>
                        @error('id_merk')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="col-sm-12">
                    <div class="form-group">
                        <label>Gambar</label>
                        <input class="form-control-file" type="file" name="gambar_produk">
                        <span class="text-danger">* Kosongkan jika tidak ingin mengubah gambar</span>
                        @error('gambar_produk')
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
            $('select[name="id_kategori"]').on('change', function() {
                var kategoriId = $(this).val();
                if (kategoriId) {
                    $.ajax({
                        url: '/api/merk/' + kategoriId,
                        type: "GET",
                        dataType: "json",
                        success: function(data) {
                            $('select[name="id_merk"]').empty();
                            $.each(data.data, function(key, value) {
                                $('select[name="id_merk"]').append(
                                    '<option value="' + value.id + '">' + value
                                    .nama_merk +
                                    '</option>');
                            });
                        },
                    });
                } else {
                    $('select[name="id_merk"]').empty();
                }
            });
        });
    </script>
@endpush
