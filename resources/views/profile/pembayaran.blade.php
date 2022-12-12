@extends('profile.layouts.main')

@section('title', 'Pembayaran')

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
                    <h3 class="mb-0"> Pesanan</h3>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <table class="table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Kode Pesanan</th>
                            <th>Tanggal Pesanan</th>
                            <th>Total Harga</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($pembayarans as $pembayaran)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td><a target="_blank" href="{{ url('pembayaran?data=' . $pembayaran->id_hash) }}">
                                        {{ $pembayaran->kode }}
                                    </a></td>
                                <td>@date($pembayaran->created_at)</td>
                                <td>@currency($pembayaran->total)</td>
                                <td>
                                    @if ($pembayaran->status == 0)
                                        <span class="badge badge-info">Menunggu Pembayaran</span>
                                    @elseif ($pembayaran->status == 1)
                                        <span class="badge badge-success">Dibayar</span>
                                    @elseif($pembayaran->status == 2)
                                        <span class="badge badge-danger">Dibatalkan</span>
                                    @elseif ($pembayaran->status == 3)
                                        <span class="badge badge-danger">Kadaluarsa</span>
                                    @elseif ($pembayaran->status == 4)
                                        <span class="badge badge-primary">Diproses</span>
                                    @endif
                                </td>
                                <td class="item">
                                    @if ($pembayaran->status == 0 || $pembayaran->status == 4)
                                        <button class="btn btn-danger" id="bayar"
                                            data-bayar="{{ $pembayaran->id_hash }}"
                                            data-token="{{ $pembayaran->snap_token ?? null }}">Bayar</button>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">Belum ada pesanan</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="ps-pagination">
            {{-- {{ $pembayarans->links() }} --}}
        </div>
    </div>
@endsection

@push('customScript')
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}">
    </script>
    <script>
        // const payButton = document.querySelector('#bayar');

        const showSnap = (token) => {
            snap.pay(token, {
                // Optional
                onSuccess: function(result) {
                    /* You may add your own js here, this is just example */
                    // document.getElementById('result-json').innerHTML += JSON.stringify(result, null, 2);
                    console.log(result)
                    location.reload();
                },
                // Optional
                onPending: function(result) {
                    /* You may add your own js here, this is just example */
                    // document.getElementById('result-json').innerHTML += JSON.stringify(result, null, 2);
                    console.log(result)
                    location.reload();
                },
                // Optional
                onError: function(result) {
                    /* You may add your own js here, this is just example */
                    // document.getElementById('result-json').innerHTML += JSON.stringify(result, null, 2);
                    console.log(result)
                    location.reload();
                },
                onClose: function(result) {
                    location.reload();
                }
            });
        }

        const callApi = (id) => {
            $.ajax({
                url: "{{ url('api/token') }}",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    id
                },
                beforeSend: function() {
                    $('#button-bayar').attr('disabled', true);
                },
                success: function(data) {
                    console.log(data)
                    showSnap(data.snap_token)
                    $('#button-bayar').attr('disabled', false);
                },
                error: function(data) {
                    console.log(data)
                }
            });
        }

        // payButton.addEventListener('click', function(e) {
        //     e.preventDefault();
        //     var value = $(this).data('bayar');
        //     var snap_token = $(this).data('token');
        //     console.log(value, snap_token)
        //     // if (snap_token != '') {
        //     //     showSnap(snap_token)
        //     // } else {
        //     //     callApi()
        //     // }
        // });

        $('.item').each(function() {
            var $button = $(this).find('button')
            var id_pembayaran = $button.data('bayar');
            var snap_token = $button.data('token');
            $button.click(function() {
                if (snap_token != '') {
                    showSnap(snap_token)
                } else {
                    callApi(id_pembayaran)
                }
            })
        })
    </script>
@endpush
