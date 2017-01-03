@extends('layouts.appblank')
@section('title','404')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">Sorry</div>

                    <div align="center" class="panel-body">
                        <img height="150" width="150" src="{{url('/images')}}/404.gif">
                        <h1>404</h1>
                        <h3>The page your are looking for is not found</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
