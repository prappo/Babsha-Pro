@extends('layouts.app')
@section('title','Home')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">Dashboard</div>

                    <div class="panel-body">
                        <div class="bs-glyphicons">
                            <ul class="bs-glyphicons-list">
                                <li><h3><span class="fa fa-users" aria-hidden="true"></span></h3> <span
                                            class="glyphicon-class"><h4>{{\App\User::all()->count()}}</h4>Users</span>
                                </li>
                                <li><h3><span class="fa fa-facebook-official" aria-hidden="true"></span></h3> <span
                                            class="glyphicon-class"><h4>{{\App\FacebookPages::all()->count()}}</h4>Pages</span>
                                </li>

                                <li><h3><span class="fa fa-file" aria-hidden="true"></span></h3> <span
                                            class="glyphicon-class"><h4>{{\App\Products::all()->count()}}</h4>Products</span>
                                </li>

                                <li><h3><span class="fa fa-star" aria-hidden="true"></span></h3> <span
                                            class="glyphicon-class"><h4>{{\App\Catagories::all()->count()}}</h4>Categories</span>
                                </li>

                            </ul>
                        </div>

                        {{--<div class="chart-container">--}}
                            {{--<canvas id="myChart" width="200" height="100"></canvas>--}}
                        {{--</div>--}}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('js')

    {{--<script>--}}
        {{--var labels = [--}}
            {{--@foreach(\App\Income::take(20)->get() as $income)"{{$income->created_at}}", @endforeach,--}}
        {{--]--}}
        {{--var money = [@foreach(\App\Income::take(20)->distinct()->get() as $money){{$money->money}}, @endforeach]--}}
        {{--var data = {--}}
            {{--labels: labels,--}}
            {{--datasets: [--}}
                {{--{--}}
                    {{--label: "Earning ",--}}
                    {{--fill: false,--}}
                    {{--lineTension: 0.1,--}}
                    {{--backgroundColor: "rgba(75,192,192,0.4)",--}}
                    {{--borderColor: "rgba(75,192,192,1)",--}}
                    {{--borderCapStyle: 'butt',--}}
                    {{--borderDash: [],--}}
                    {{--borderDashOffset: 0.0,--}}
                    {{--borderJoinStyle: 'miter',--}}
                    {{--pointBorderColor: "rgba(75,192,192,1)",--}}
                    {{--pointBackgroundColor: "#fff",--}}
                    {{--pointBorderWidth: 1,--}}
                    {{--pointHoverRadius: 5,--}}
                    {{--pointHoverBackgroundColor: "rgba(75,192,192,1)",--}}
                    {{--pointHoverBorderColor: "rgba(220,220,220,1)",--}}
                    {{--pointHoverBorderWidth: 2,--}}
                    {{--pointRadius: 1,--}}
                    {{--pointHitRadius: 10,--}}
                    {{--data: money,--}}
                    {{--spanGaps: false,--}}
                {{--}--}}
            {{--]--}}
        {{--};--}}
        {{--var ctx = document.getElementById("myChart");--}}
        {{--var myChart = new Chart(ctx, {--}}
            {{--type: 'line',--}}
            {{--data: data,--}}
            {{--options: {--}}
                {{--scales: {--}}
                    {{--yAxes: [{--}}
                        {{--ticks: {--}}
                            {{--beginAtZero: true--}}
                        {{--}--}}
                    {{--}]--}}
                {{--}--}}
            {{--}--}}
        {{--});--}}


    {{--</script>--}}

@endsection