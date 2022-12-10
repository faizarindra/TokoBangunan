@extends('profile.layouts.main')

@section('content_user')
    <form class="ps-form--account-setting" action="{{ url('/profile/user') }}" method="POST">
        @csrf
        @method('patch')
        <div class="ps-form__header">
            @if (session()->has('success'))
                <div class="alert alert-warning py-3">
                    {{ session('success') }}
                </div>
            @endif
            <h3> Informasi Akun</h3>
            @if ($percentage['null'] > 0)
                <h5>Lengkapi Profile</h5>
                <div class="progress" style="height: 20px;">
                    <div class="progress-bar progress-bar-striped bg-warning progress-bar-animated" role="progressbar"
                        aria-valuenow="{{ $percentage['null'] }}" aria-valuemin="0"
                        aria-valuemax="{{ $percentage['base_columns'] }}"
                        style="width: {{ $percentage['percent'] }}%;font-size: 20px">
                        {{ $percentage['percent'] }}%</div>
                </div>
            @endif
        </div>
        <div class="ps-form__content">
            <div class="form-group">
                <label>Nama</label>
                <input class="form-control" type="text" placeholder="Masukkan Nama..." value="{{ $auth->name }}"
                    name="name">
            </div>
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label>Password (Kosongkan jika tidak ingin update password)</label>
                        <input class="form-control" type="text" placeholder="Masukkan Password baru..." name="password">
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label>Nomor Telpon</label>
                        <input class="form-control" type="text" placeholder="Masukkan No Telp..." name="no_telp"
                            value="{{ $auth->no_telp }}">
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label>Email</label>
                        <input class="form-control" type="text" placeholder="Masukkan Email..."
                            value="{{ $auth->email }}" name="email">
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label>Tanggal Lahir</label>
                        <input class="form-control" type="date" placeholder="Masukkan Tanggal Lahir..." name="dob"
                            value="{{ $auth->dob }}">
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label>Jenis Kelamin</label>
                        <select class="form-control" name="jenis_kelamin">
                            <option value="">--Pilih Jenis Kelamin--</option>
                            <option value="0" @if ($auth->jenis_kelamin == '0') selected @endif>
                                Laki-Laki</option>
                            <option value="1" @if ($auth->jenis_kelamin == '1') selected @endif>
                                Perempuan</option>
                        </select>
                    </div>
                </div>
                <div class="col-sm-6 d-flex align-items-center">
                    <div class="ps-list--link">
                        <li>
                            <a style="cursor:pointer;" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
                                Ingin ganti foto
                                profile?Klik
                                disini</a>
                        </li>
                    </div>

                </div>
            </div>
        </div>
        <div class="form-group submit">
            <button class="ps-btn">Update</button>
        </div>
    </form>
@endsection
