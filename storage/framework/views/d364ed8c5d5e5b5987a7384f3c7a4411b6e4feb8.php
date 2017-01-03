<?php $__env->startSection('title','Order History'); ?>
<?php $__env->startSection('content'); ?>
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">Order History</div>

                    <div class="panel-body">

                        <table id="mytable" class="table table-striped table-bordered" cellspacing="0" width="100%">
                            <thead>
                            <tr>
                                <th>Order</th>
                                <th>Purchased</th>
                                <th>Status</th>
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
                                    <td>
                                        <?php echo e(\App\Orders::where('orderId',$order->orderId)->value('status')); ?>


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
                                               class="btn btn-primary btn-xs"><i class="fa fa-eye"></i></a>
                                            <a class="btn btn-success btn-xs"><i class="fa fa-check"></i></a>
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