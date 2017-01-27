@extends('layouts.app')
@section('title','Order Lists')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">Order list</div>

                    <div class="panel-body">

                        <table id="mytable" class="table table-striped table-bordered" cellspacing="0" width="100%">
                            <thead>
                            <tr>
                                <th>Order</th>
                                <th>Purchased</th>
                                <th>Ship to</th>
                                <th>Date</th>
                                <th>Total</th>
                                <th>Payment</th>
                                <th>Action</th>

                            </tr>
                            </thead>
                            <tbody>

                            @foreach($orders as $order)
                                <tr>
                                    <td><a href="{{url('/order')}}/{{$order->orderId}}">#{{$order->orderId}}</a></td>
                                    <td>{{\App\Orders::where('orderId',$order->orderId)->where('userId',Auth::user()->id)->count()}} @if(\App\Orders::where('productid',$order->productId)->count() > 1)
                                            Item @else Items @endif </td>
                                    <td>@if(\App\Http\Controllers\Customer::getAdddress($order->sender) == "none"){{\App\Http\Controllers\Customer::getName($order->sender)}}
                                        ,{{\App\Http\Controllers\Customer::getStreet($order->sender)}}
                                        ,{{\App\Http\Controllers\Customer::getCity($order->sender)}}
                                        ,{{\App\Http\Controllers\Customer::getPostalCode($order->sender)}}  @endif</td>
                                    <td>{{$order->created_at}}</td>
                                    <td>

                                        {{\App\Settings::where('userId',Auth::user()->id)->value('currency')}}{{\App\Orders::where('orderId',$order->orderId)->sum('price')}}

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
                                               class="btn btn-primary btn-xs"><i class="fa fa-eye"></i> View Order</a>

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
