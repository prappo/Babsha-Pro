@extends('layouts.app')
@section('title','Order History')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">Order History</div>

                    <div class="panel-body">

                        <table id="mytable" class="table table-striped table-bordered" cellspacing="0" width="100%">
                            <thead>
                            <tr>
                                <th>Order</th>
                                <th>Purchased</th>
                                <th>Status</th>
                                <th>Payment</th>
                                <th>Action</th>

                            </tr>
                            </thead>
                            <tbody>

                            @foreach($orders as $order)
                                <tr>
                                    <td><a href="{{url('/order')}}/{{$order->orderId}}">#{{$order->orderId}}</a></td>
                                    <td>{{\App\Orders::where('orderId',$order->orderId)->count()}} @if(\App\Orders::where('productid',$order->productId)->count() > 1)
                                            Item @else Items @endif </td>
                                    <td>
                                        {{\App\Orders::where('orderId',$order->orderId)->value('status')}}

                                    </td>
                                    <td>
                                        @if(\App\Orders::where('orderId',$order->orderId)->value('payment') == "Paid")
                                            <b style="color: green">Paid</b>
                                        @else
                                            <b style="color: red">Not Paid</b>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group" aria-label="...">
                                            <a href="{{url('/order')}}/{{$order->orderId}}"
                                               class="btn btn-primary btn-xs"><i class="fa fa-eye"></i></a>

                                        </div>
                                    </td>

                                </tr>
                            @endforeach

                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script>

    </script>
@endsection
