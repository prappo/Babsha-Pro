<?php $__env->startSection('title','Earning History'); ?>
<?php $__env->startSection('content'); ?>
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">Earning History</div>

                    <div class="panel-body">

                        <table id="mytable" class="table table-striped table-bordered" cellspacing="0" width="100%">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>Order ID</th>
                                <th>Amount</th>
                                <th>Date</th>

                            </tr>
                            </thead>
                            <tbody>

                            <?php foreach($data as $d): ?>
                                <tr>


                                    <td><a><?php echo e($d->id); ?></a></td>
                                    <td><?php echo e($d->orderid); ?></td>
                                    <td><?php echo e(\App\Http\Controllers\Data::getUnit().$d->money); ?></td>

                                    <td><?php echo e(\App\Http\Controllers\Data::date($d->created_at)); ?></td>


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