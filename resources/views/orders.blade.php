@extends('layouts.app')
@section('title','Orders')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">Total {{$orderCount}} orders</div>

                    <div class="panel-body">

                        <div class="list-group">
                            @foreach($orders as $order)
                                <a class="list-group-item">

                                    {{--<h4 class="list-group-item-heading">{{\App\Products::where('id',$order->productid)->value('title')}}</h4>--}}
                                    <p class="list-group-item-text">
                                        <div class="row">
                                            <div id="productDetails" class="col-md-4">
                                                {{--product details start--}}
                                                <div class="panel panel-default">
                                                    <div class="panel-heading">
                                                        <img class="thumbnail" width="250" height="150"
                                                             src="{{url('/uploads')."/".\App\Products::where('id',$order->productid)->value('image')}}">
                                                        {{\App\Products::where('id',$order->productid)->value('title')}}
                                                    </div>
                                                    <div class="panel-body">
                                    <p>

                                        {{\App\Products::where('id',$order->productid)->value('short_description')}}</p>
                        </div>
                        <table class="table">

                            <tbody>
                            <tr>
                                <th>Price</th>
                                <td>{{\App\Http\Controllers\Data::getUnit()}}{{\App\Products::where('id',$order->productid)->value('price')}}</td>

                            </tr>

                            <tr>
                                <th>Category</th>
                                <td>{{\App\Products::where('id',$order->productid)->value('category')}}</td>

                            </tr>
                            </tbody>
                        </table>
                    </div>
                    {{--product details end--}}

                </div>
                <div id="userDetails" class="col-md-5">
                    {{--customer details start--}}
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <img class="thumbnail" height="64" width="64"
                                 src="{{\App\Customers::where('fbId',$order->sender)->value('image')}}">
                            {{\App\Customers::where('fbId',$order->sender)->value('name')}}

                        </div>

                        <table class="table">

                            <tbody>
                            <tr>
                                <th>Mobile</th>
                                <td>{{\App\Customers::where('fbId',$order->sender)->value('mobile')}}</td>

                            </tr>
                            <tr>
                                <th>Street</th>
                                <td>{{\App\Customers::where('fbId',$order->sender)->value('street')}}</td>

                            </tr>

                            <tr>
                                <th>City</th>
                                <td>{{\App\Customers::where('fbId',$order->sender)->value('city')}}</td>

                            </tr>

                            <tr>
                                <th>State</th>
                                <td>{{\App\Customers::where('fbId',$order->sender)->value('state')}}</td>

                            </tr>
                            <tr>
                                <th>Country</th>
                                <td>{{\App\Customers::where('fbId',$order->sender)->value('country')}}</td>

                            </tr>
                            @if(\App\Customers::where('fbId',$order->sender)->value('coordinates') != null)
                            <tr>
                                <th>Map</th>
                                <td><a  target="_blank" class="btn btn-default"
                                        href="https://www.google.com.bd/maps/place/{{\App\Customers::where('fbId',$order->sender)->value('coordinates')}}">
                                        <i class="fa fa-map"></i> View Address on map</a></td>
                            </tr>
                            @endif
                            </tbody>
                        </table>
                    </div>

                    {{--customer details end--}}
                    <?php
                    $unitPrice = \App\Products::where('id', $order->productid)->value('price');
                    $quantity = \App\Orders::where('productid', $order->productid)->where('sender', $order->sender)->where('status', 'pending')->count();
                    $totalCost = $quantity * $unitPrice;
                    $subTotal = $totalCost + \App\Http\Controllers\Data::getTax() + \App\Http\Controllers\Data::getShippingCost();
                    $shortDescription = \App\Products::where('id', $order->productid)->value('short_description');
                    $title = \App\Products::where('id', $order->productid)->value('title');
                    $image = \App\Products::where('id', $order->productid)->value('image');
                    ?>
                </div>
                <div id="action" class="col-md-3">
                    <div class="btn-group-vertical" role="group" aria-label="...">
                        <button data-title="{{$title}}" data-shortDescription="{{$shortDescription}}"
                                data-image="{{$image}}" data-sender="{{$order->sender}}" data-quantity="{{$quantity}}"
                                data-id="{{$order->productid}}"
                                data-price="{{$unitPrice}}" data-totalCost="{{$totalCost}}"
                                data-subTotal="{{$subTotal}}" type="button" class="btn btn-success"><i
                                    class="fa fa-check"></i> Confirm
                        </button>
                        <button data-id="{{$order->sender}}" type="button" class="btn btn-primary">
                            Request for update address
                        </button>
                        <button data-id="{{$order->sender}}" type="button" class="btn btn-warning"><i
                                    class="fa fa-envelope"></i>
                            Send Message
                        </button>
                        <button type="button" data-user="{{$order->sender}}" data-id="{{$order->productid}}"
                                class="btn btn-danger"><i
                                    class="fa fa-times"></i>
                            Delete
                        </button>

                    </div>

                    <table class="table">

                        <tbody>
                        <tr>
                            <th>Quantity</th>
                            <td>{{$quantity}}</td>
                        </tr>
                        <tr>
                            <th>Unit price</th>
                            <td>{{\App\Http\Controllers\Data::getUnit()}}{{$unitPrice}}</td>
                        </tr>
                        <tr>
                            <th>Total Cost</th>
                            <td>{{\App\Http\Controllers\Data::getUnit()}}{{$totalCost}}</td>
                        </tr>
                        <tr>
                            <th>Tax</th>
                            <td>{{\App\Http\Controllers\Data::getUnit()}}{{\App\Http\Controllers\Data::getTax()}}</td>
                        </tr>
                        <tr>
                            <th>Shipping Cost</th>
                            <td>{{\App\Http\Controllers\Data::getUnit()}}{{\App\Http\Controllers\Data::getShippingCost()}}</td>
                        </tr>
                        <tr>
                            <th>Sub Total</th>
                            <td>{{\App\Http\Controllers\Data::getUnit()}}{{$subTotal}}</td>
                        </tr>
                        </tbody>
                    </table>
                </div>

            </div>
            </p>
            </a>

            @endforeach

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
            var sender = $(this).attr('data-id');

            swal({
                title: "Message",
                text: "Write Message to your customer",
                type: "input",
                showCancelButton: true,
                closeOnConfirm: false,
                animation: "slide-from-top",
                inputPlaceholder: "Write something",
                showLoaderOnConfirm: true,
            }, function (inputValue) {
                if (inputValue === false) return false;
                if (inputValue === "") {
                    swal.showInputError("You need to write something!");
                    return false
                }

                $.ajax({
                    type: 'POST',
                    url: '{{url('/send/message')}}',
                    data: {
                        'userId': sender,
                        'msg': inputValue
                    },
                    success: function (data) {
                        if (data.search('success') != -1) {
                            swal('Success', 'Message sent to customer', 'success');

                        }
                        else {
                            swal('Error', data, 'error');
                        }
                    },
                    error: function (data) {
                        console.log(data);
                    }
                });
            });
        });
        $('.btn-danger').click(function () {
            var id = $(this).attr('data-id');
            var userId = $(this).attr('data-user');
            swal({
                title: "Do you want to cancel this order ?",
                text: "If yes click ok button",
                type: "info",
                showCancelButton: true,
                closeOnConfirm: false,
                showLoaderOnConfirm: true,
            }, function () {
                $.ajax({
                    type: 'POST',
                    url: '{{url('/delete/order')}}',
                    data: {
                        'productid': id,
                        'userId': userId
                    },
                    success: function (data) {
                        if (data == 'success') {
                            swal('Success', 'Order cancelded and delted', 'success');
                            location.reload();
                        }
                        else {
                            swal('Error', data, 'error');
                        }
                    },
                    error: function (data) {
                        console.log(data);
                    }
                });
            });
        });
        $('.btn-primary').click(function () {
            var id = $(this).attr('data-id');
            swal({
                title: "Do you want to send a request to customer to update his/her shipping address ?",
                text: "If yes click ok button",
                type: "info",
                showCancelButton: true,
                closeOnConfirm: false,
                showLoaderOnConfirm: true,
            }, function () {
                $.ajax({
                    type: 'POST',
                    url: '{{url('/send/request')}}',
                    data: {
                        'userId': id
                    },
                    success: function (data) {
                        if (data.search('success') != -1) {
                            swal('Success', 'Request sent to customer', 'success');
                        }
                        else {
                            swal('Error', data, 'error');
                        }
                    },
                    error: function (data) {
                        console.log(data);
                    }
                });
            });
        });

        $('.btn-success').click(function () {
            var quantity = $(this).attr('data-quantity');
            var id = $(this).attr('data-id');
            var price = $(this).attr('data-price');
            var totalCost = $(this).attr('data-totalCost');
            var subTotal = $(this).attr('data-subTotal');
            var userId = $(this).attr('data-sender');
            var image = $(this).attr('data-image');
            var shortDescription = $(this).attr('data-shortDescription');
            var title = $(this).attr('data-title');

            swal({
                title: "Did you get paid for this order ? and want to send receipt to customer",
                text: "If yes click ok button",
                type: "info",
                showCancelButton: true,
                closeOnConfirm: false,
                showLoaderOnConfirm: true,
            }, function () {
                $.ajax({
                    type: 'POST',
                    url: '{{url('/send/confirmation')}}',
                    data: {
                        'id': id,
                        'quantity': quantity,
                        'price': price,
                        'totalCost': totalCost,
                        'subTotal': subTotal,
                        'userId': userId,
                        'title': title,
                        'image': image,
                        'shortDescription': shortDescription
                    },
                    success: function (data) {
                        if (data.search('message_id') != -1) {
                            swal('Success', 'Receipt sent to customer', 'success');
                        }
                        else {
                            swal('Error', data, 'error');
                        }
                    },
                    error: function (data) {
                        console.log(data.responseText);
                    }
                });
            });

        });
        //        /send/confirmation
    </script>
@endsection
