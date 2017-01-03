<?php $__env->startSection('title','Order'); ?>
<?php $__env->startSection('content'); ?>



                <div class="panel panel-default">
                    <div class="panel-heading"><h3>Order #<?php echo e($orderNumber); ?> details</h3><h4>Payment method
                            : <?php echo e(\App\Orders::where('orderId',$orderNumber)->value('method')); ?><br>
                            ( <?php if(\App\Orders::where('orderId',$orderNumber)->value('payment')== "Paid"): ?> <b
                                    style="color: green;">Paid</b> <?php else: ?> <b style="color: red">Not Paid</b><?php endif; ?> )</h4>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h4>General Details</h4>
                                <p><b>Order Date</b></p>
                                <p><?php echo e(\App\Orders::where('orderId',$orderNumber)->value('created_at')); ?></p>
                                <p><b>Order Status</b></p>
                                <p><?php echo e($status = \App\Orders::where('orderId',$orderNumber)->value('status')); ?></p>
                                <p><b>Customer</b></p>
                                <div class="media">
                                    <div class="media-left"><a href="#"> <img alt="User Image" class="media-object"
                                                                              data-src="holder.js/64x64"
                                                                              src="<?php echo e(\App\Customers::where('fbId',\App\Orders::where('orderId',$orderNumber)->value('sender'))->value('image')); ?>"
                                                                              data-holder-rendered="true"
                                                                              style="width: 64px; height: 64px;"> </a>
                                    </div>
                                    <div class="media-body"><h4
                                                class="media-heading"><?php echo e(\App\Customers::where('fbId',\App\Orders::where('orderId',$orderNumber)->value('sender'))->value('name')); ?> </h4>


                                    </div>
                                </div>


                            </div>
                            <div class="col-md-6">
                                <h4>Shipping Detials</h4>
                                <p>
                                    <?php if(\App\Customers::where('fbId',\App\Orders::where('orderId',$orderNumber)->value('sender'))->value('address') != null): ?>
                                        <?php echo e(\App\Customers::where('fbId',\App\Orders::where('orderId',$orderNumber)->value('sender'))->value('address')); ?>

                                    <?php else: ?>
                                        <?php echo e(\App\Customers::where('fbId',\App\Orders::where('orderId',$orderNumber)->value('sender'))->value('street')); ?>

                                        <br>
                                        <?php echo e(\App\Customers::where('fbId',\App\Orders::where('orderId',$orderNumber)->value('sender'))->value('city')); ?>

                                        <br>
                                        <?php echo e(\App\Customers::where('fbId',\App\Orders::where('orderId',$orderNumber)->value('sender'))->value('state')); ?>

                                        <br>
                                        <?php echo e(\App\Customers::where('fbId',\App\Orders::where('orderId',$orderNumber)->value('sender'))->value('postal_code')); ?>

                                        <br>
                                        <?php echo e(\App\Customers::where('fbId',\App\Orders::where('orderId',$orderNumber)->value('sender'))->value('country')); ?>

                                        <br>
                                    <?php endif; ?>
                                </p>
                                <p>
                                    <a target="_blank" class="btn btn-default"
                                       href="https://www.google.com.bd/maps/place/<?php echo e(\App\Customers::where('fbId',\App\Orders::where('orderId',$orderNumber)->value('sender'))->value('coordinates')); ?>"><i
                                                class="fa fa-map"></i> View address on map</a>
                                </p>
                                <p><b>Mobile</b></p>
                                <p>
                                    <?php echo e(\App\Customers::where('fbId',\App\Orders::where('orderId',$orderNumber)->value('sender'))->value('mobile')); ?>

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
                                        <?php foreach(\App\Orders::distinct()->where('orderId',$orderNumber)->get(['productid','sender','orderId','type']) as $order): ?>
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


                                                                <?php if($order->type == "woo"): ?>
                                                                    <img alt="64x64"
                                                                         class="media-object"
                                                                         src="<?php echo e($woo->image); ?>"
                                                                         data-holder-rendered="true"
                                                                         style="width: 64px; height: 64px;">
                                                                <?php else: ?>
                                                                    <img alt="64x64"
                                                                         class="media-object"
                                                                         src="<?php echo e(url('/uploads/')); ?>/<?php echo e(\App\Products::where('id',$order->productid)->value('image')); ?>"
                                                                         data-holder-rendered="true"
                                                                         style="width: 64px; height: 64px;">
                                                                <?php endif; ?>


                                                                <?php if($order->type == "woo"): ?>
                                                                    <a href="<?php echo e($woo->url); ?>"> <?php echo e(($woo->name )); ?> <span
                                                                                class="badge">Woo</span></a>
                                                                <?php else: ?>
                                                                    <a href="<?php echo e(url('/product')); ?>/<?php echo e($order->productid); ?>"> <?php echo e(\App\Products::where('id',$order->productid)->value('title')); ?> </a>
                                                                    &nbsp;
                                                                <?php endif; ?>
                                                                &nbsp;







                                                </td>
                                                <?php if($order->type == "woo"): ?>
                                                    <td><?php echo e(\App\Http\Controllers\Data::getUnit()); ?><?php echo e($woo->price); ?></td>
                                                    <td><?php echo e((\App\Orders::where('productid',$order->productid)->where('orderId',$orderNumber)->count())); ?></td>
                                                    <td><?php echo e(\App\Http\Controllers\Data::getUnit()); ?><?php echo e($woo->price * (\App\Orders::where('productid',$order->productid)->where('orderId',$orderNumber)->count())); ?></td>
                                                <?php else: ?>
                                                    <td><?php echo e(\App\Http\Controllers\Data::getUnit()); ?><?php echo e(\App\Products::where('id',$order->productid)->value('price')); ?></td>
                                                    <td><?php echo e((\App\Orders::where('productid',$order->productid)->where('orderId',$orderNumber)->count())); ?></td>
                                                    <td><?php echo e(\App\Http\Controllers\Data::getUnit()); ?><?php echo e((\App\Products::where('id',$order->productid)->value('price')) * (\App\Orders::where('productid',$order->productid)->where('orderId',$orderNumber)->count())); ?></td>
                                                <?php endif; ?>
                                            </tr>
                                        <?php endforeach; ?>


                                        <tr>
                                            <td></td>
                                            <td>Total quantity :</td>
                                            <td><?php echo e($totalItem); ?></td>
                                            <td><b><?php echo e(\App\Http\Controllers\Data::getUnit()); ?><?php echo e($subTotal); ?></b></td>
                                        </tr>

                                        <tr>
                                            <td></td>
                                            <td></td>
                                            <td>Tax</td>
                                            <td><b><?php echo e(\App\Http\Controllers\Data::getUnit()); ?><?php echo e($tax = \App\Http\Controllers\Data::getTax()); ?></b></td>
                                        </tr>
                                        <tr>
                                            <td></td>
                                            <td></td>
                                            <td>Shipping Cost</td>
                                            <td> <b><?php echo e(\App\Http\Controllers\Data::getUnit()); ?><?php echo e(\App\Http\Controllers\Data::getShippingCost()); ?></b></td>
                                        </tr>
                                        <tr>
                                            <td></td>
                                            <td></td>
                                            <td>Total Cost : </td>
                                            <td> <b><?php echo e(\App\Http\Controllers\Data::getUnit()); ?><?php echo e(\App\Http\Controllers\Data::getShippingCost() + \App\Http\Controllers\Data::getTax() + $subTotal); ?></b></td>
                                        </tr>

                                        </tbody>
                                    </table>

                                </div>


                            </div>

                        </div>
                    </div>




<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.appblank', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>