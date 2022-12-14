@extends('toko.layouts.main')

@section('title', 'Tambah Rekening')

@section('content_toko')
    <form class="ps-form--account-setting" action="{{ url('seller/tambah-rekening') }}" method="POST" autocomplete="off">
        @csrf
        <div class="ps-form__header">
            @if (session()->has('success'))
                <div class="alert alert-warning py-3">
                    {{ session('success') }}
                </div>
            @endif
            <h3 class="mb-5"> Tambah Rekening</h3>
        </div>
        <div class="ps-form__content">
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label>Bank</label>
                        <select class="form-control" name="id_bank">
                            <option value="">--Pilih Bank--</option>
                            @foreach ($banks as $bank)
                                <option value="{{ $bank->id }}">
                                    {{ $bank->nama }}</option>
                            @endforeach
                        </select>
                        @error('id_bank')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="col-sm-12">
                    <div class="form-group">
                        <label>Nomor Rekening</label>
                        <input class="form-control" type="text" placeholder="Masukkan Nomor Rekening" name="no_rekening"
                            value="{{ old('no_rekening') }}">
                        @error('no_rekening')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="col-sm-12">
                    <div class="form-group">
                        <label>Atas Nama</label>
                        <input class="form-control" type="text" placeholder="Masukkan Atas Nama" name="atas_nama"
                            value="{{ old('atas_nama') }}">
                        @error('atas_nama')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="col-sm-12">
                    <div class="form-group">
                        <label>Cabang</label>
                        <input class="form-control" type="text" placeholder="Masukkan Cabang" name="cabang"
                            value="{{ old('cabang') }}">
                        @error('cabang')
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
