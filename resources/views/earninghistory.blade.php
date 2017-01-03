@extends('layouts.app')
@section('title','Earning History')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">Earning History</div>

                    <div class="panel-body">

                        <table id="mytable" class="table table-striped table-bordered" cellspacing="0" width="100%">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>Order ID</th>
                                <th>Amount</th>
                                <th>Date</th>

                            </tr>
                            </thead>
                            <tbody>

                            @foreach($data as $d)
                                <tr>


                                    <td><a>{{$d->id}}</a></td>
                                    <td>{{$d->orderid}}</td>
                                    <td>{{\App\Http\Controllers\Data::getUnit().$d->money}}</td>

                                    <td>{{\App\Http\Controllers\Data::date($d->created_at)}}</td>


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
