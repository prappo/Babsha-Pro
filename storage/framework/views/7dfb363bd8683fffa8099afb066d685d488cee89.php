<?php $__env->startSection('title','Order Lists'); ?>
<?php $__env->startSection('content'); ?>
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">Order list</div>

                    <div class="panel-body">

                        <table id="mytable" class="table table-striped table-bordered" cellspacing="0" width="100%">
                            <thead>
                            <tr>
                                <th>Order</th>
                                <th>Purchased</th>
                                <th>Ship to</th>
                                <th>Date</th>
                                <th>Total</th>
                                <th>Payment</th>
                                <th>Action</th>

                            </tr>
                            </thead>
                            <tbody>

                            <?php foreach($orders as $order): ?>
                                <tr>
                                    <td><a href="<?php echo e(url('/order')); ?>/<?php echo e($order->orderId); ?>">#<?php echo e($order->orderId); ?></a></td>
                                    <td><?php echo e(\App\Orders::where('orderId',$order->orderId)->count()); ?> <?php if(\App\Orders::where('productid',$order->productId)->count() > 1): ?>
                                            Item <?php else: ?> Items <?php endif; ?> </td>
                                    <td><?php if(\App\Http\Controllers\Customer::getAdddress($order->sender) == "none"): ?><?php echo e(\App\Http\Controllers\Customer::getName($order->sender)); ?>

                                        ,<?php echo e(\App\Http\Controllers\Customer::getStreet($order->sender)); ?>

                                        ,<?php echo e(\App\Http\Controllers\Customer::getCity($order->sender)); ?>

                                        ,<?php echo e(\App\Http\Controllers\Customer::getPostalCode($order->sender)); ?> <?php else: ?> <?php echo e(\App\Http\Controllers\Customer::getAdddress($order->sender)); ?> <?php endif; ?></td>
                                    <td><?php echo e($order->created_at); ?></td>
                                    <td>
                                        <?php
                                        $total = 0;
                                        ?>

                                        <?php foreach(\App\Orders::distinct()->where('orderId',$order->orderId)->where('status','pending')->get(['productid']) as $o): ?>

                                            <?php $total = $total + (\App\Products::where('id',$o->productid)->value('price')) * (\App\Orders::where('orderId',$order->orderId)->where('status','pending')->where('productid',$o->productid)->count());?>
                                        <?php endforeach; ?>
                                         <?php echo e(\App\Http\Controllers\Data::getUnit() . $total); ?>


                                    </td>
                                    <td>
                                        <?php if(\App\Orders::where('orderId',$order->orderId)->value('payment') == "Paid"): ?>
                                            <b style="color: green">Paid</b>
                                        <?php else: ?>
                                            <b style="color: red">Not Paid</b>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group" aria-label="...">
                                            <a href="<?php echo e(url('/order')); ?>/<?php echo e($order->orderId); ?>"
                                               class="btn btn-primary btn-xs"><i class="fa fa-eye"></i> View Order</a>

                                        </div>
                                    </td>

                                </tr>
                            <?php endforeach; ?>

                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('js'); ?>
    <script>

    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>