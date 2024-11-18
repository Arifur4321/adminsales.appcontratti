@extends('layouts.master')
@section('title')
@lang('translation.Sales-Performence')
@endsection

@section('content')
    @component('components.breadcrumb')
        @slot('li_1')
            Projects
        @endslot
        @slot('title' )
        @lang('translation.Sales-Performence')

        @endslot
    @endcomponent

<!-- -----------------------------  --> 
<meta name="csrf-token" content="{{ csrf_token() }}">
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script> 
    <link rel="stylesheet" href="//cdn.datatables.net/2.0.2/css/dataTables.dataTables.min.css">
    <script src="//cdn.datatables.net/2.0.2/js/dataTables.min.js"></script>

    <!-- SweetAlert2 -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
 
    <!-- Include Chart.js library -->
    <style>
        .chart-container {
            position: relative;
            height: 450px; /* Adjust this value as needed */
        }
    </style>
 
    <div class="container">
        <div class="chart-container">
            <canvas id="salesPerformanceChart"></canvas>
        </div>
    </div>


    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var ctx = document.getElementById('salesPerformanceChart').getContext('2d');
            var salesData = @json($salesData);

            var labels = salesData.map(data => data.sales_name);
            var totalData = salesData.map(data => data.total);
            var signedData = salesData.map(data => data.signed);
            var pendingData = salesData.map(data => data.pending);
            var emptyData = salesData.map(data => data.empty);

            var chart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [
                        {
                            label: 'Total Contracts',
                            data: totalData,
                            backgroundColor: 'rgba(54, 162, 235, 0.2)',
                            borderColor: 'rgba(54, 162, 235, 1)',
                            borderWidth: 1
                        },
                        {
                            label: 'Signed Contracts',
                            data: signedData,
                           // backgroundColor: 'rgba(75, 192, 192, 0.2)',
                            backgroundColor: 'rgba(102, 255, 178, 0.2)',
                            borderColor: 'rgba(75, 192, 192, 1)',
                            borderWidth: 1
                        },
                        {
                            label: 'Pending Contracts',
                            data: pendingData,
                            backgroundColor: 'rgba(255, 206, 86, 0.2)',
                            borderColor: 'rgba(255, 206, 86, 1)',

                            borderWidth: 1
                        }
                        // ,
                        // {
                        //     label: 'Empty Contracts',
                        //     data: emptyData,
                        //     backgroundColor: 'rgba(255, 99, 132, 0.2)',
                        //     borderColor: 'rgba(255, 99, 132, 1)',
                        //     borderWidth: 1
                        // }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 5
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: true,
                            position: 'top'
                        },
                        tooltip: {
                            mode: 'index',
                            intersect: false
                        }
                    }
                }
            });
        });
    </script>
 



<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<style>
    #spinner-overlay {
        display: none;
        position: fixed;
        width: 100%;
        height: 100%;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: rgba(0, 0, 0, 0.5);
        z-index: 9999;
    }

    #spinner {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        display: flex;
        align-items: center;
        justify-content: center;
        width: 120px;
        height: 120px;
    }

    .ring {
        border: 8px solid transparent;
        border-radius: 50%;
        position: absolute;
        animation: spin 1.5s linear infinite;
    }

    .ring:nth-child(1) {
        width: 120px;
        height: 120px;
        border-top: 8px solid #3498db;
        animation-delay: -0.45s;
    }

    .ring:nth-child(2) {
        width: 100px;
        height: 100px;
        border-right: 8px solid #f39c12;
        animation-delay: -0.3s;
    }

    .ring:nth-child(3) {
        width: 80px;
        height: 80px;
        border-bottom: 8px solid #e74c3c;
        animation-delay: -0.15s;
    }

    .ring:nth-child(4) {
        width: 60px;
        height: 60px;
        border-left: 8px solid #9b59b6;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
</style>

<!-- Spinner Overlay -->
<div id="spinner-overlay">
    <div id="spinner">
        <div class="ring"></div>
        <div class="ring"></div>
        <div class="ring"></div>
        <div class="ring"></div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const spinnerOverlay = document.getElementById("spinner-overlay");

        // Show the spinner when the page is loading
        spinnerOverlay.style.display = "block";

        window.addEventListener("load", function() {
            // Hide the spinner when the page has fully loaded
            spinnerOverlay.style.display = "none";
        });

        document.addEventListener("ajaxStart", function() {
            // Show the spinner when an AJAX request starts
            spinnerOverlay.style.display = "block";
        });

        document.addEventListener("ajaxStop", function() {
            // Hide the spinner when the AJAX request completes
            spinnerOverlay.style.display = "none";
        });
    });
</script>

@endsection
