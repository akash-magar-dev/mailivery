@extends('layout')
@section('content')
@php
$months = array('January' => 0,'February ' => 0,'March' => 0,'April' => 0,'May' => 0,'June' => 0,'July' => 0,'August' =>
0,'September' => 0,'October' => 0,'November' => 0,'December ' => 0);
$month_count = array_map("count", $monthly_mail);
$year_count = array_sum($month_count);
$months_data= array_merge($months,$month_count);

$chart_month = array_keys($months);
$chart_month_count = implode(",",array_values($months_data));
@endphp

<div class="dashboard-finance">
    <div class="container-fluid dashboard-content">
        <!-- ============================================================== -->
        <!-- pageheader  -->
        <!-- ============================================================== -->
        <div class="row">
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                <div class="page-header">
                    <h3 class="mb-2 font-weight-bold mailivery">Mail Dashboard </h3>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 col-12">
                <div class="card">
                    <h5 class="card-header">Yearly count</h5>
                    <div class="card-body text-center">
                        <div class="metric-value d-inline-block ">
                            <h1 class="mb-1 year mailivery">{{$year_count}} </h1>
                            <span>mails sent</span>
                        </div>
                        {{-- <div class="metric-label d-inline-block float-right text-success font-weight-bold">
                            <span class="icon-circle-small icon-box-xs text-success bg-success-light"><i
                                    class="fa fa-fw fa-arrow-up"></i></span><span class="ml-1">25%</span>
                        </div> --}}
                    </div>
                    <div class="card-body bg-light">
                        {{-- <canvas id="myChart" style="height: 500px"></canvas> --}}
                    </div>

                </div>
            </div>
            <div class="col">
                <div class="card">
                    <h5 class="card-header">Monthly count</h5>
                    <div class="card-body bg-light">
                        <canvas id="monthChart"></canvas>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    $(function(){
        month = {!!json_encode($chart_month_count)!!};
        const ctx = document.getElementById('monthChart');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: {!!json_encode($chart_month)!!},
                datasets: [{
                    data: month.split(','),
                    borderWidth: 1,
                    label:'Monthly mail sent',
                    fill: false,
                    borderColor: 'rgb(75, 192, 192)',
                    tension: 0.3
                }], 
            },
        });
    });
</script>
@endsection