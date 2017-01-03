<?php $__env->startSection('title','PayPal Payment History'); ?>
<?php $__env->startSection('content'); ?>
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">Paypal payment History</div>

                    <div class="panel-body">

                        <table id="mytable" class="table table-striped table-bordered" cellspacing="0" width="100%">
                            <thead>
                            <tr>
                                <th>Customer ID</th>
                                <th>Order ID</th>
                                <th>Payment ID</th>
                                <th>Token</th>
                                <th>Payer ID</th>
                                <th>Amount</th>

                            </tr>
                            </thead>
                            <tbody>

                            <?php foreach($data as $d): ?>
                                <tr>
                                    <td><?php echo e($d->sender); ?></td>
                                    <td><?php echo e($d->orderId); ?></td>
                                    <td><?php echo e($d->paymentId); ?></td>
                                    <td><?php echo e($d->token); ?></td>
                                    <td><?php echo e($d->payerId); ?></td>
                                    <td><?php echo e($d->amount); ?></td>

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


<?php echo $__env->make('layouts.app', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>