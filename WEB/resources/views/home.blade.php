@extends('layouts.app')
@section('title', 'Home')
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
        <div style="font-size: 30px;">20</div>
    </div>
    <div class="card text-center m-2" style="width: 350px; height: auto; padding: 2rem;">
        <h3>💵 Laba Bulan Ini</h3>
        <div style="font-size: 30px;">20</div>
    </div>
</div>
<hr class="m-4">
@if ($total != null && $periode != null)
    <h1 class="text-center mt-4">Data Penjualan</h1>
    <div class="d-flex" style="margin: -0.5rem; margin-top: 1rem; margin-bottom: 1rem;">
        <form class="d-flex m-2 ms-auto" action="/home/filter" method="get">
            <input class="form-control me-2" type="date" name="first"
                @isset($first) value="{{ $first }}" @endisset />
            <label for="second" class="form-label m-2">=></label>
            <input class="form-control me-2" type="date" name="second"
                @isset($second) value="{{ $second }}" @endisset />
            <button class="btn btn-outline-primary" type="submit">Filter</button>
        </form>
    </div>
    <div class="p-4 mt-4 card d-flex" style="margin: auto;">
        <div id="chart1"></div>
        <div id="chart" style="position: absolute; left: 50%;"></div>
    </div>
@endif

<script>
    var options = {
        series: [{
            name: "Desktops",
            data: @json($total)
        }],
        chart: {
            width: 750,
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
        title: {
            text: 'Data Penjualan',
            align: 'left',
            style: {
                fontFamily: 'Nata Sans'
            }
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
            data: @json($total)
        }],
        chart: {
            width: 750,
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
        title: {
            text: 'Data Penjualan',
            align: 'center',
            style: {
                fontFamily: 'Nata Sans'
            }
        },
        xaxis: {
            categories: @json($periode),
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
