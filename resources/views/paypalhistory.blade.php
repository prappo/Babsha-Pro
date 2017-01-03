@extends('layouts.app')
@section('title','PayPal Payment History')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">Paypal payment History</div>

                    <div class="panel-body">

                        <table id="mytable" class="table table-striped table-bordered" cellspacing="0" width="100%">
                            <thead>
                            <tr>
                                <th>Customer ID</th>
                                <th>Order ID</th>
                                <th>Payment ID</th>
                                <th>Token</th>
                                <th>Payer ID</th>
                                <th>Amount</th>

                            </tr>
                            </thead>
                            <tbody>

                            @foreach($data as $d)
                                <tr>
                                    <td>{{$d->sender}}</td>
                                    <td>{{$d->orderId}}</td>
                                    <td>{{$d->paymentId}}</td>
                                    <td>{{$d->token}}</td>
                                    <td>{{$d->payerId}}</td>
                                    <td>{{$d->amount}}</td>

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

