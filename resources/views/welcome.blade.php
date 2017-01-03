@extends('layouts.app')
@section('title','Welcome')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">Welcome</div>

                    <div align="center" class="panel-body">
                        <img height="80" width="80" src="@if(\App\Http\Controllers\Data::getLogo() == ""){{url('/images/logo.png')}} @else {{\App\Http\Controllers\Data::getLogo()}} @endif">
                        <h1 style="color:#8FBCFF">{{\App\Http\Controllers\Data::getShopTitle()}}</h1>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
