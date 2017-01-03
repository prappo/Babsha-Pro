<?php $__env->startSection('title','Error'); ?>
<?php $__env->startSection('content'); ?>
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">Sorry</div>

                    <div align="center" class="panel-body">
                        <img height="150" width="150" src="<?php echo e(url('/images')); ?>/503.gif">
                        <h1>Error</h1>
                        <h3><?php if(isset($msg)): ?> <?php echo e($msg); ?> <?php else: ?> Something went wrong :( <?php endif; ?></h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.appblank', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>