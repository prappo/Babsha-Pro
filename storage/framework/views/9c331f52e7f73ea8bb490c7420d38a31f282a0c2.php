<?php $__env->startSection('title','Welcome'); ?>
<?php $__env->startSection('content'); ?>
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">Welcome</div>

                    <div align="center" class="panel-body">
                        <img height="80" width="80" src="<?php if(\App\Http\Controllers\Data::getLogo() == ""): ?><?php echo e(url('/images/logo.png')); ?> <?php else: ?> <?php echo e(\App\Http\Controllers\Data::getLogo()); ?> <?php endif; ?>">
                        <h1 style="color:#8FBCFF"><?php echo e(\App\Http\Controllers\Data::getShopTitle()); ?></h1>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>