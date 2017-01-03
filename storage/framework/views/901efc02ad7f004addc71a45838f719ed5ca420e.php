<?php $__env->startSection('title','404'); ?>
<?php $__env->startSection('content'); ?>
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">Sorry</div>

                    <div align="center" class="panel-body">
                        <img height="150" width="150" src="<?php echo e(url('/images')); ?>/404.gif">
                        <h1>404</h1>
                        <h3>The page your are looking for is not found</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.appblank', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>