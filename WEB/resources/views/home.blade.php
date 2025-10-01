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

@if ($total != null && $periode != null)
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
    <div class="p-4 mt-4" style="margin: auto;">
        <div id="chart"></div>
    </div>
@endif

<script>
    var options = {
        series: [{
            data: @json($total)
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
