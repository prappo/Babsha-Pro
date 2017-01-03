@extends('layouts.app')
@section('title','Products')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">Products</div>

                    <div class="panel-body">
                        <div class="container">
                            @foreach($data as $d)

                                <div class="row">

                                    <div class="col-md-3">
                                        <a href="#" class="thumbnail">
                                            <img src="{{url('/uploads')."/".$d->image}}" alt="">
                                        </a>
                                    </div>
                                    <div class="col-md-7">
                                        <h1>{{$d->title}} @if($d->featured == "yes") <span
                                                    class="badge">Featured</span> @endif</h1>
                                        <h3>${{$d->price}} </h3>
                                        <p>{{$d->short_description}}</p>
                                        <p>{{$d->long_description}}</p>
                                        <h4>Category : <span class="badge">{{$d->category}}</span></h4>
                                        <h4>Status : <span class="badge">{{$d->status}}</span></h4>
                                        <br>

                                    </div>
                                    <div class="col-md-2">
                                        <div class="btn-group-vertical" role="group" aria-label="...">
                                            <a class="btn btn-primary btn-xs"
                                               href="{{url('/update/product')}}/{{$d->id}}"><i
                                                        class="fa fa-edit"> Edit Product</i> </a>
                                            <a data-value="{{$d->status}}" data-id="{{$d->id}}"
                                               class="btn @if($d->status=='published')btn-warning @else btn-success @endif btn-xs"> @if($d->status=='published')
                                                    Unpublish @else Publish @endif Product </a>
                                            <a data-id="{{$d->id}}" class="btn btn-danger btn-xs"><i
                                                        class="fa fa-times">
                                                    Delete Product</i> </a>
                                        </div>
                                    </div>

                                </div>

                            @endforeach
                            {!! $data->render() !!}
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
        $('.btn-warning').click(function () {
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
                    url: '{{url('/delete/product')}}',
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