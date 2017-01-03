@extends('layouts.app')
@section('title','WooCommerce Products')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">WooCommerce Products</div>

                    <div class="panel-body">
                        <div class="container">
                            <?php
                            $count = 0;
                            ?>
                            @foreach($data as $d)
                                <?php $count++;?>
                                <div class="row">

                                    <div class="col-md-3">
                                        <a href="#" class="thumbnail">
                                            <img data-lightbox="image" data-title="{{$d['name']}}" src="{{$d['images'][0]['src']}}" alt="">
                                        </a>
                                    </div>
                                    <div class="col-md-7">
                                        <h1>{{$d['name']}}</h1>
                                        <h3>${{$d['price']}} </h3>
                                        <p>{!! $d['short_description'] !!}</p>
                                        <p>{!! $d['description'] !!}</p>
                                        <h4>Category : <span class="badge">@if(isset($d['categories'][0]['name'])){{$d['categories'][0]['name']}}@endif</span></h4>
                                        <h4>Status : <span class="badge">{{$d['status']}}</span></h4>
                                        <br>

                                    </div>
                                    <div class="col-md-2">
                                        <div class="btn-group-vertical" role="group" aria-label="...">
                                            <a class="btn btn-primary btn-xs"
                                               href="{{url('/woo/update/product')}}/{{$d['id']}}"><i
                                                        class="fa fa-edit">Quick Edit</i> </a>
                                            <a data-id="{{$d['id']}}" class="btn btn-danger btn-xs"><i
                                                        class="fa fa-times">
                                                    Delete Product</i> </a>
                                        </div>
                                    </div>

                                </div>

                            @endforeach

                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
@endsection

@section('js')
    <script>


        $('.btn-success').click(function () {
            $.ajax({
                type: 'POST',
                url: '{{url('/product/status')}}',
                data: {
                    'id': $(this).attr('data-id'),
                    'status': $(this).attr('data-value')
                },
                success: function (data) {
                    if (data == 'success') {
                        swal('Success', 'Product status updated', 'success');
                        location.reload();
                    }
                    else {
                        swal('Error', data, 'error');
                    }
                }

            });
        });

        $('.btn-danger').click(function () {
            var id = $(this).attr('data-id');


            swal({
                title: "Are you sure?",
                text: "You will not be able to recover this product!",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Yes, delete it!",
                closeOnConfirm: false,
                showLoaderOnConfirm: true
            }, function () {
                $.ajax({
                    type: 'POST',
                    url: '{{url('/woo/delete/product')}}',
                    data: {
                        'id': id
                    },
                    success: function (data) {
                        if (data == 'success') {
                            swal('Success', 'Product deleted', 'success');
                            location.reload();
                        }
                        else {
                            swal('Error', data, 'error');
                        }
                    }
                });
            });

        })
    </script>
@endsection