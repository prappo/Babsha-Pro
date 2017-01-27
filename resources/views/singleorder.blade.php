@extends('layouts.app')
@section('title','Order')
@section('content')
    <div class="container">
        <div class="row">
            <div id="orderSection" class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading"><h3>Order #{{$orderNumber}} details</h3><h4>Payment method
                            : {{\App\Orders::where('orderId',$orderNumber)->value('method')}}
                            ( @if(\App\Orders::where('orderId',$orderNumber)->value('payment')== "Paid") <b
                                    style="color: green;">Paid</b> @else <b style="color: red">Not Paid</b>@endif )</h4>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h4>General Details</h4>
                                <p><b>Order Date</b></p>
                                <p>{{\App\Orders::where('orderId',$orderNumber)->value('created_at')}}</p>
                                <p><b>Order Status</b></p>
                                <p>{{$status = \App\Orders::where('orderId',$orderNumber)->value('status')}}</p>
                                <p><b>Customer</b></p>
                                <div class="media">
                                    <div class="media-left"><a href="#"> <img alt="User Image" class="media-object"
                                                                              data-src="holder.js/64x64"
                                                                              src="{{\App\Customers::where('fbId',\App\Orders::where('orderId',$orderNumber)->value('sender'))->value('image')}}"
                                                                              data-holder-rendered="true"
                                                                              style="width: 64px; height: 64px;"> </a>
                                    </div>
                                    <div class="media-body"><h4
                                                class="media-heading">{{\App\Customers::where('fbId',\App\Orders::where('orderId',$orderNumber)->value('sender'))->value('name')}} </h4>


                                    </div>
                                </div>


                            </div>
                            <div class="col-md-6">
                                <h4>Shipping Detials</h4>
                                <p>

                                        Street :{{\App\Customers::where('fbId',\App\Orders::where('orderId',$orderNumber)->value('sender'))->value('street')}}
                                        <br>
                                        City : {{\App\Customers::where('fbId',\App\Orders::where('orderId',$orderNumber)->value('sender'))->value('city')}}
                                        <br>
                                        State :{{\App\Customers::where('fbId',\App\Orders::where('orderId',$orderNumber)->value('sender'))->value('state')}}
                                        <br>
                                        Postal Code : {{\App\Customers::where('fbId',\App\Orders::where('orderId',$orderNumber)->value('sender'))->value('postal_code')}}
                                        <br>
                                        Country : {{\App\Customers::where('fbId',\App\Orders::where('orderId',$orderNumber)->value('sender'))->value('country')}}
                                        <br>

                                </p>
                                <p>

                                </p>
                                <p><b>Mobile</b></p>
                                <p>
                                    {{\App\Customers::where('fbId',\App\Orders::where('orderId',$orderNumber)->value('sender'))->value('mobile')}}
                                </p>
                            </div>

                        </div>
                        <div class="row" style="padding:20px">
                            <div class="panel panel-default">
                                <div class="panel-heading">Items</div>
                                <div class="panel-body">

                                    <div class="row">
                                        <div class="col-md-6"><b>Product Name</b></div>
                                        <div class="col-md-2"><b>Cost</b></div>
                                        <div class="col-md-2"><b>Qty</b></div>
                                        <div class="col-md-2"><b>Total</b></div>
                                    </div>
                                    <br>
                                    <?php
                                    $totalItem = 0;
                                    $subTotal = 0;
                                    $total = 0;

                                    ?>
                                    @foreach(\App\Orders::distinct()->where('orderId',$orderNumber)->get(['productid','sender','orderId','type']) as $order)
                                        <?php

                                        $totalItem = $totalItem + (\App\Orders::where('productid', $order->productid)->where('orderId', $orderNumber)->count());

                                        if ($order->type == "woo") {
                                            $woo = new \App\Http\Controllers\Woo($order->productid);
                                            $subTotal = $subTotal + ($woo->price * (\App\Orders::where('productid', $order->productid)->where('orderId', $orderNumber)->count()));
                                        } else {
                                            $subTotal = $subTotal + (\App\Products::where('id', $order->productid)->value('price')) * (\App\Orders::where('productid', $order->productid)->where('orderId', $orderNumber)->count());
                                        }
                                        ?>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="media">
                                                    <div class="media-left"><a href="#">
                                                            @if($order->type == "woo")
                                                                <img alt="64x64"
                                                                     class="media-object"
                                                                     src="{{$woo->image}}"
                                                                     data-holder-rendered="true"
                                                                     style="width: 64px; height: 64px;">
                                                            @else
                                                                <img alt="64x64"
                                                                     class="media-object"
                                                                     src="{{url('/uploads/')}}/{{\App\Products::where('id',$order->productid)->value('image')}}"
                                                                     data-holder-rendered="true"
                                                                     style="width: 64px; height: 64px;">
                                                            @endif
                                                        </a>
                                                    </div>
                                                    <div class="media-body">
                                                        <h4 class="media-heading">
                                                            @if($order->type == "woo")
                                                                <a href="{{$woo->url}}"> {{($woo->name )}} <span
                                                                            class="badge">Woo</span></a>
                                                            @else
                                                                <a href="{{url('/product')}}/{{$order->productid}}"> {{\App\Products::where('id',$order->productid)->value('title')}} </a>
                                                                &nbsp;
                                                            @endif
                                                            &nbsp;
                                                            <button data-order="{{$order->orderId}}"
                                                                    data-id="{{$order->productid}}"
                                                                    data-sender="{{$order->sender}}"
                                                                    class="btn rmProduct btn-xs btn-danger"><i
                                                                        class="fa fa-times"></i> Delete
                                                            </button>
                                                        </h4>


                                                    </div>
                                                </div>

                                            </div>
                                            @if($order->type == "woo")
                                                <div class="col-md-2">{{\App\Settings::where('userId',Auth::user()->id)->value('currency')}}{{$woo->price}}</div>
                                                <div class="col-md-2">{{ (\App\Orders::where('productid',$order->productid)->where('orderId',$orderNumber)->count())}}</div>
                                                <div class="col-md-2">{{\App\Settings::where('userId',Auth::user()->id)->value('currency')}}{{$woo->price * (\App\Orders::where('productid',$order->productid)->where('orderId',$orderNumber)->count())}}</div>
                                            @else
                                                <div class="col-md-2">{{\App\Settings::where('userId',Auth::user()->id)->value('currency')}}{{\App\Products::where('id',$order->productid)->value('price')}}</div>
                                                <div class="col-md-2">{{ (\App\Orders::where('productid',$order->productid)->where('orderId',$orderNumber)->count())}}</div>
                                                <div class="col-md-2">{{\App\Settings::where('userId',Auth::user()->id)->value('currency')}}{{(\App\Products::where('id',$order->productid)->value('price')) * (\App\Orders::where('productid',$order->productid)->where('orderId',$orderNumber)->count())}}</div>
                                            @endif
                                        </div>
                                    @endforeach
                                    <br>
                                    <div style="border-top: 1px solid #8c8b8b;">
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6"></div>
                                        <div class="col-md-2">Total quantity :</div>
                                        <div class="col-md-2"><b>{{$totalItem}}</b></div>
                                        <div class="col-md-2">
                                            <b>{{\App\Settings::where('userId',Auth::user()->id)->value('currency')}}{{$subTotal}}</b></div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6"></div>
                                        <div class="col-md-2"></div>
                                        <div class="col-md-2"><b>Tax</b></div>
                                        <div class="col-md-2">
                                            <b>{{\App\Settings::where('userId',Auth::user()->id)->value('currency')}}{{$tax = \App\Settings::where('userId',Auth::user()->id)->value('tax')}}</b>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6"></div>
                                        <div class="col-md-2"></div>
                                        <div class="col-md-2"><b>Shipping Cost</b></div>
                                        <div class="col-md-2">
                                            <b>{{\App\Settings::where('userId',Auth::user()->id)->value('currency')}}{{\App\Settings::where('userId',Auth::user()->id)->value('shipping')}}</b>
                                        </div>
                                    </div>
                                    <div style="border-top: 1px solid #8c8b8b;">
                                        <div class="row">
                                            <div class="col-md-6"></div>
                                            <div class="col-md-2"></div>
                                            <div class="col-md-2"><b>Total Cost : </b></div>
                                            <div class="col-md-2">
                                                <b>{{\App\Settings::where('userId',Auth::user()->id)->value('currency')}}{{\App\Settings::where('userId',Auth::user()->id)->value('shipping') + \App\Settings::where('userId',Auth::user()->id)->value('tax') + $subTotal}}</b>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>


                        </div>

                    </div>
                </div>

            </div>
            @if($status != "done" && $status != "canceled")
                <div class="row">
                    <div class="col-md-5"></div>
                    <div class="col-md-7">
                        <div class="btn-group" role="group" aria-label="...">
                            <button id="confirm" data-id="{{$orderNumber}}" class="btn btn-success"><i
                                        class="fa fa-check"></i> Confirm Order
                            </button>
                            <button data-order="{{$orderNumber}}"
                                    data-sender="{{\App\Orders::where('orderId',$orderNumber)->value('sender')}}"
                                    id="cancel" data-id="{{$orderNumber}}" class="btn btn-warning"><i
                                        class="fa fa-times"></i> Cancel Order
                            </button>
                            <button data-order="{{$orderNumber}}" id="delete" data-id="{{$orderNumber}}"
                                    class="btn btn-danger"><i
                                        class="fa fa-trash"></i> Delete Order
                            </button>
                            <button id="printNow" class="btn btn-default"><i class="fa fa-print"></i> Print Order
                            </button>

                        </div>
                    </div>
                </div>
            @endif
            <br><br><br>
        </div>
    </div>
@endsection
@section('js')
    <script src="{{url('/js/print.js')}}"></script>
    <script>

        $('#confirm').click(function () {
            swal({
                title: "Are you sure?",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#5cb85c",
                confirmButtonText: "Yes",
                closeOnConfirm: false,
                showLoaderOnConfirm: true
            }, function () {
                $.ajax({
                    type: 'POST',
                    url: '{{url('/send/confirmation')}}',
                    data: {
                        'orderId': '{{$orderNumber}}'
                    },
                    success: function (data) {
                        if (data.search('message_id') != -1) {
                            swal("Success", "Order confirmed !", 'success');
                            window.location.href = "{{url('/orders')}}";
                        }
                        else {
                            swal('Error', data, 'error');
                            console.log(data);
                        }
                    },
                    error: function (data) {
                        console.log(data.responseText);
                        swal('Error', data, 'error');
                    }

                });
            });
        });

        $('.rmProduct').click(function () {
            var productId = $(this).attr('data-id');
            var UserId = $(this).attr('data-sender');
            var orderId = $(this).attr('data-order');
            swal({
                title: "Are you sure?",
                text: "Do you want to remove this product form order list ?",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Yes, remove it!",
                closeOnConfirm: false,
                showLoaderOnConfirm: true
            }, function () {
                $.ajax({
                    type: 'POST',
                    url: '{{url('/remove/product')}}',
                    data: {
                        'productid': productId,
                        'userId': UserId,
                        'orderId': orderId
                    },
                    success: function (data) {
                        if (data == 'success') {
                            swal("Success", "Deleted !", 'success');
                            location.reload();
                        }
                        else {
                            swal('Error', data, 'error');
                        }
                    },
                    error: function (data) {
                        console.log(data.responseText);
                        swal('Error', data.responseText, 'error');
                    }

                });
            });

        });

        $('#printNow').click(function () {
            $('#orderSection').print({
                globalStyles: true,
                mediaPrint: true,
                stylesheet: true,
                noPrintSelector: ".no-print",
                iframe: true,
                append: null,
                prepend: null,
                manuallyCopyFormValues: true,
                deferred: $.Deferred(),
                timeout: 750,
                title: null,
                doctype: '<!doctype html>'
            });
        });

        $('#cancel').click(function () {
            var orderId = $(this).attr('data-order');
            var sender = $(this).attr('data-sender');
            swal({
                title: "Are you sure?",
                text: "Do you want to cancel this order ?",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Yes, cancel it!",
                closeOnConfirm: false

            }, function () {

                swal({
                    title: "Why ?",
                    text: "Write the reason why you want to cancel this order and send this message to customer",
                    type: "input",
                    showCancelButton: true,
                    closeOnConfirm: false,
                    animation: "slide-from-top",
                    inputPlaceholder: "Write something",
                    showLoaderOnConfirm: true
                }, function (inputValue) {
                    if (inputValue === false) return false;
                    if (inputValue === "") {
                        swal.showInputError("You need to write something!");
                        return false
                    }
                    $.ajax({
                        type: 'POST',
                        url: '{{url('/cancel/order')}}',
                        data: {
                            'orderId': orderId
                        },
                        success: function (data) {
                            if (data == 'success') {
                                swal("Success", "Order Canceled !", 'success');
                                window.location.href = "{{url('/orders')}}";
                            }
                            else {
                                swal('Error', data, 'error');
                            }
                        },
                        error: function (data) {
                            console.log(data.responseText);
                            swal('Error', "Something went wrong please check console message", 'error');
                        }

                    });

                    $.ajax({
                       type:'POST',
                        url:'{{url('/send/message')}}',
                        data:{
                            'userId':sender,
                            'msg':inputValue
                        },
                        success:function (data) {
                            if(data=='error'){
                                swal('Error',"Something went wrong .Can't send message to Customer");
                            }

                        },
                        error:function (data) {
                            swal('Error',data,'error');
                        }
                    });
                });


            });
        });

        $('#delete').click(function () {
            var orderId = $(this).attr('data-order');
            swal({
                title: "Are you sure?",
                text: "Do you want to delete this order ?",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Yes, delete it!",
                closeOnConfirm: false,
                showLoaderOnConfirm: true
            }, function () {
                $.ajax({
                    type: 'POST',
                    url: '{{url('/delete/order')}}',
                    data: {
                        'orderId': orderId
                    },
                    success: function (data) {
                        if (data == 'success') {
                            swal("Success", "Deleted !", 'success');
                            window.location.href = "{{url('/orders')}}";
                        }
                        else {
                            swal('Error', data, 'error');
                        }
                    },
                    error: function (data) {
                        console.log(data.responseText);
                        swal('Error', data.responseText, 'error');
                    }

                });
            });
        });
    </script>
@endsection