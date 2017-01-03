@extends('layouts.appblank')
@section('title','Error')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">Sorry</div>

                    <div align="center" class="panel-body">
                        <img height="150" width="150" src="{{url('/images')}}/503.gif">
                        <h1>Error</h1>
                        <h3>@if(isset($msg)) {{$msg}} @else Something went wrong :( @endif</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
