@extends('layouts.app')
@section('title', 'Beranda || Laporan Penjualan')
@section('content')
@section('script')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/apexcharts/5.3.5/apexcharts.min.css"
        integrity="sha512-IqtQ7LKr3He47p7HjxynmqZfN07VljNkdGyGDdDJ//f1r6bT0IEKQf2CCtSgun/pvbFlNnPDMRrMSQhmSxmSSg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/apexcharts/5.3.5/apexcharts.min.js"
        integrity="sha512-dC9VWzoPczd9ppMRE/FJohD2fB7ByZ0VVLVCMlOrM2LHqoFFuVGcWch1riUcwKJuhWx8OhPjhJsAHrp4CP4gtw=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
@endsection

<div class="row row-cols-1 row-cols-md-4 g-4" style="align-items: center; justify-content: space-between;">
    <div class="card text-center m-2" style="width: 350px; height: auto; padding: 2rem;">
        <h3>💰 Omset Hari ini</h3>
        <div style="font-size: 30px;">{{ 'Rp ' . number_format($omsetHariIni, 0, ',', '.') }}</div>
    </div>
    <div class="card text-center m-2" style="width: 350px; height: auto; padding: 2rem;">
        <h3>💵 Laba Hari Ini</h3>
        <div style="font-size: 30px;">{{ 'Rp ' . number_format($labaHariIni, 0, ',', '.') }}</div>
    </div>
    <div class="card text-center m-2" style="width: 350px; height: auto; padding: 2rem;">
        <h3>💰 Omset Bulan ini</h3>
        <div style="font-size: 30px;">{{ 'Rp ' . number_format($omsetBulanIni, 0, ',', '.') }}</div>
    </div>
    <div class="card text-center m-2" style="width: 350px; height: auto; padding: 2rem;">
        <h3>💵 Laba Bulan Ini</h3>
        <div style="font-size: 30px;">{{ 'Rp ' . number_format($labaBulanIni, 0, ',', '.') }}</div>
    </div>
</div>
<hr class="m-4">
@if ($total != null && $periode != null)
    <div class="p-4 mt-4 card d-flex" style="margin: auto;">
        <h1 class="text-center">Data Penjualan Minggu Ini</h1>
        <div id="chart1"></div>
    </div>
@endif
<hr class="m-4">
@if ($dataBulan != null && $totalBulan != null)
    <div class="p-4 mt-4 card d-flex" style="margin: auto;">
        <h1 class="text-center">Data Penjualan Tahun Ini</h1>
        <div id="chart"></div>
    </div>
@endif

<script>
    var options = {
        series: [{
            name: "Desktops",
            data: @json($total)
        }],
        chart: {
            height: 350,
            type: 'line',
            zoom: {
                enabled: false
            }
        },
        dataLabels: {
            enabled: false
        },
        stroke: {
            curve: 'straight'
        },
        grid: {
            row: {
                colors: ['#f3f3f3', 'transparent'], // takes an array which will be repeated on columns
                opacity: 0.5
            },
        },
        xaxis: {
            categories: @json($periode),
            labels: {
                fontSize: '8px',
                fontFamily: 'Nata Sans',
            }
        },
        yaxis: {
            labels: {
                formatter: function(value) {
                    return value.toLocaleString('id-ID', {
                        style: "currency",
                        currency: "IDR"
                    });
                },
                style: {
                    fontFamily: 'Nata Sans'
                }
            },
        },
    };

    var chart = new ApexCharts(document.querySelector("#chart1"), options);
    chart.render();
</script>

<script>
    var options = {
        series: [{
            data: @json($totalBulan)
        }],
        chart: {
            height: 350,
            type: 'bar',
        },
        colors: ['#008FFB', '#00E396', '#FEB019', '#FF4560',
            '#775DD0', '#546E7A', '#26A69A', '#D10CE8'
        ],
        plotOptions: {
            bar: {
                columnWidth: '45%',
                distributed: true,
            }
        },
        dataLabels: {
            enabled: false
        },
        legend: {
            show: false
        },
        xaxis: {
            categories: @json($dataBulan),
            labels: {
                style: {
                    colors: ['#008FFB', '#00E396', '#FEB019', '#FF4560',
                        '#775DD0', '#546E7A', '#26A69A', '#D10CE8'
                    ],
                    fontSize: '12px',
                    fontFamily: 'Nata Sans'
                }
            }
        },
        yaxis: {
            labels: {
                formatter: function(value) {
                    return value.toLocaleString('id-ID', {
                        style: "currency",
                        currency: "IDR"
                    });
                },
                style: {
                    fontFamily: 'Nata Sans'
                }
            },
        },
    };

    var chart = new ApexCharts(document.querySelector("#chart"), options);
    chart.render();
</script>

@if ($message = Session::get('error'))
    <script>
        Swal.fire({
            title: "{{ $message }}",
            icon: "error",
            draggable: false
        });
    </script>
@endif
@endsection
