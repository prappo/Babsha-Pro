@extends('layouts.appblank')
@section('title','Order')
@section('content')



                <div class="panel panel-default">
                    <div class="panel-heading"><h3>Order #{{$orderNumber}} details</h3><h4>Payment method
                            : {{\App\Orders::where('orderId',$orderNumber)->value('method')}}<br>
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
                                    @if(\App\Customers::where('fbId',\App\Orders::where('orderId',$orderNumber)->value('sender'))->value('address') != null)
                                        {{\App\Customers::where('fbId',\App\Orders::where('orderId',$orderNumber)->value('sender'))->value('address')}}
                                    @else
                                        {{\App\Customers::where('fbId',\App\Orders::where('orderId',$orderNumber)->value('sender'))->value('street')}}
                                        <br>
                                        {{\App\Customers::where('fbId',\App\Orders::where('orderId',$orderNumber)->value('sender'))->value('city')}}
                                        <br>
                                        {{\App\Customers::where('fbId',\App\Orders::where('orderId',$orderNumber)->value('sender'))->value('state')}}
                                        <br>
                                        {{\App\Customers::where('fbId',\App\Orders::where('orderId',$orderNumber)->value('sender'))->value('postal_code')}}
                                        <br>
                                        {{\App\Customers::where('fbId',\App\Orders::where('orderId',$orderNumber)->value('sender'))->value('country')}}
                                        <br>
                                    @endif
                                </p>
                                <p>
                                    <a target="_blank" class="btn btn-default"
                                       href="https://www.google.com.bd/maps/place/{{\App\Customers::where('fbId',\App\Orders::where('orderId',$orderNumber)->value('sender'))->value('coordinates')}}"><i
                                                class="fa fa-map"></i> View address on map</a>
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
                                <td class="panel-body">

                                    <table class="table">
                                        <thead>
                                        <tr>
                                            <th>Product Name</th>
                                            <th>Cost</th>
                                            <th>Qty</th>
                                            <th>Total</th>
                                        </tr>
                                        </thead>
                                        <tbody>
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
                                                $woo = new \App\Http\Controllers\WooProduct($order->productid);
                                                $subTotal = $subTotal + ($woo->price * (\App\Orders::where('productid', $order->productid)->where('orderId', $orderNumber)->count()));
                                            } else {
                                                $subTotal = $subTotal + (\App\Products::where('id', $order->productid)->value('price')) * (\App\Orders::where('productid', $order->productid)->where('orderId', $orderNumber)->count());
                                            }
                                            ?>
                                            <tr>
                                                <td>


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


                                                                @if($order->type == "woo")
                                                                    <a href="{{$woo->url}}"> {{($woo->name )}} <span
                                                                                class="badge">Woo</span></a>
                                                                @else
                                                                    <a href="{{url('/product')}}/{{$order->productid}}"> {{\App\Products::where('id',$order->productid)->value('title')}} </a>
                                                                    &nbsp;
                                                                @endif
                                                                &nbsp;







                                                </td>
                                                @if($order->type == "woo")
                                                    <td>{{\App\Http\Controllers\Data::getUnit()}}{{$woo->price}}</td>
                                                    <td>{{ (\App\Orders::where('productid',$order->productid)->where('orderId',$orderNumber)->count())}}</td>
                                                    <td>{{\App\Http\Controllers\Data::getUnit()}}{{$woo->price * (\App\Orders::where('productid',$order->productid)->where('orderId',$orderNumber)->count())}}</td>
                                                @else
                                                    <td>{{\App\Http\Controllers\Data::getUnit()}}{{\App\Products::where('id',$order->productid)->value('price')}}</td>
                                                    <td>{{ (\App\Orders::where('productid',$order->productid)->where('orderId',$orderNumber)->count())}}</td>
                                                    <td>{{\App\Http\Controllers\Data::getUnit()}}{{(\App\Products::where('id',$order->productid)->value('price')) * (\App\Orders::where('productid',$order->productid)->where('orderId',$orderNumber)->count())}}</td>
                                                @endif
                                            </tr>
                                        @endforeach


                                        <tr>
                                            <td></td>
                                            <td>Total quantity :</td>
                                            <td>{{$totalItem}}</td>
                                            <td><b>{{\App\Http\Controllers\Data::getUnit()}}{{$subTotal}}</b></td>
                                        </tr>

                                        <tr>
                                            <td></td>
                                            <td></td>
                                            <td>Tax</td>
                                            <td><b>{{\App\Http\Controllers\Data::getUnit()}}{{$tax = \App\Http\Controllers\Data::getTax()}}</b></td>
                                        </tr>
                                        <tr>
                                            <td></td>
                                            <td></td>
                                            <td>Shipping Cost</td>
                                            <td> <b>{{\App\Http\Controllers\Data::getUnit()}}{{\App\Http\Controllers\Data::getShippingCost()}}</b></td>
                                        </tr>
                                        <tr>
                                            <td></td>
                                            <td></td>
                                            <td>Total Cost : </td>
                                            <td> <b>{{\App\Http\Controllers\Data::getUnit()}}{{\App\Http\Controllers\Data::getShippingCost() + \App\Http\Controllers\Data::getTax() + $subTotal}}</b></td>
                                        </tr>

                                        </tbody>
                                    </table>

                                </div>


                            </div>

                        </div>
                    </div>




@endsection
