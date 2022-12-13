@extends('toko.layouts.main')

@section('title', 'Dashboard')

@section('content_toko')
    <div class="ps-section__header">
        <h3>Dashboard</h3>
    </div>
    <div class="ps-section__content">
        <div class="row">
            <div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-12 ">
                <div class="ps-block--dashboard alert-danger d-flex justify-content-between align-items-center px-5 py-4">
                    <div class="ps-block__left"><i class="icon-bag2" style="font-size: 4rem"></i></div>
                    <div class="ps-block__right">
                        <h3 class="text-right">{{ $total_product }}</h3>
                        <p class="mb-0">Produk</p>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-12 ">
                <div class="ps-block--dashboard alert-primary d-flex justify-content-between align-items-center px-5 py-4">
                    <div class="ps-block__left"><i class="icon-chart-bars" style="font-size: 4rem"></i></div>
                    <div class="ps-block__right">
                        <h3 class="text-right">{{ $total_pesanan }}</h3>
                        <p class="mb-0">Pesanan</p>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-12 ">
                <div class="ps-block--dashboard alert-warning d-flex justify-content-between align-items-center px-5 py-4">
                    <div class="ps-block__left"><i class="icon-star" style="font-size: 4rem"></i></div>
                    <div class="ps-block__right">
                        <h3 class="text-right">{{ $total_review }}</h3>
                        <p class="mb-0">Ulasan</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mt-5">
            <div class="col-8">
                <canvas id="myChart" height="200px"></canvas>
            </div>
            <div class="col-4">
                <div class="row">
                    <div class="col-12">
                        <div
                            class="ps-block--dashboard alert-success d-flex justify-content-between align-items-center px-5 py-4 mb-3">
                            <div class="ps-block__left"><i class="icon-bag-dollar" style="font-size: 4rem"></i></div>
                            <div class="ps-block__right">
                                <h3 class="text-right">@currency($total_penjualan_bulan)</h3>
                                <p class="mb-0">Penjualan Bulan Ini</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <canvas id="chartJK" height="100px"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('customScript')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script type="text/javascript">
        var labels = {{ Js::from($labels) }};
        var users = {{ Js::from($data) }};

        const data = {
            labels: labels,
            datasets: [{
                label: 'Penjualan',
                backgroundColor: [
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(255, 159, 64, 0.2)',
                    'rgba(255, 205, 86, 0.2)',
                    'rgba(75, 192, 192, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(153, 102, 255, 0.2)',
                    'rgba(201, 203, 207, 0.2)'
                ],
                borderColor: [
                    'rgb(255, 99, 132)',
                    'rgb(255, 159, 64)',
                    'rgb(255, 205, 86)',
                    'rgb(75, 192, 192)',
                    'rgb(54, 162, 235)',
                    'rgb(153, 102, 255)',
                    'rgb(201, 203, 207)'
                ],
                borderWidth: 1,
                data: users,
            }]
        };

        const config = {
            type: 'bar',
            data: data,
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            },
        };

        const myChart = new Chart(
            document.getElementById('myChart'),
            config
        );
    </script>

    <script type="text/javascript">
        var jk = {{ Js::from($grafik_jk) }};

        const dataJK = {
            labels: [
                'Laki-laki',
                'Perempuan',
                'Tidak Diketahui'
            ],
            datasets: [{
                label: 'Penjualan',
                backgroundColor: [
                    'rgb(255, 99, 132)',
                    'rgb(54, 162, 235)',
                    'rgb(255, 205, 86)'
                ],
                hoverOffset: 4,
                data: jk,
            }]
        };

        const configJK = {
            type: 'doughnut',
            data: dataJK,
        };

        const chartJK = new Chart(
            document.getElementById('chartJK'),
            configJK
        );
    </script>
@endpush
