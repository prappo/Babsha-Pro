<?php $__env->startSection('title','Products'); ?>
<?php $__env->startSection('content'); ?>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">Products</div>

                    <div class="panel-body">
                        <div class="container">
                            <?php foreach($data as $d): ?>

                                <div class="row">

                                    <div class="col-md-3">
                                        <a href="#" class="thumbnail">
                                            <img src="<?php echo e(url('/uploads')."/".$d->image); ?>" alt="">
                                        </a>
                                    </div>
                                    <div class="col-md-7">
                                        <h1><?php echo e($d->title); ?> <?php if($d->featured == "yes"): ?> <span
                                                    class="badge">Featured</span> <?php endif; ?></h1>
                                        <h3>$<?php echo e($d->price); ?> </h3>
                                        <p><?php echo e($d->short_description); ?></p>
                                        <p><?php echo e($d->long_description); ?></p>
                                        <h4>Category : <span class="badge"><?php echo e($d->category); ?></span></h4>
                                        <h4>Status : <span class="badge"><?php echo e($d->status); ?></span></h4>
                                        <br>

                                    </div>
                                    <div class="col-md-2">
                                        <div class="btn-group-vertical" role="group" aria-label="...">
                                            <a class="btn btn-primary btn-xs"
                                               href="<?php echo e(url('/update/product')); ?>/<?php echo e($d->id); ?>"><i
                                                        class="fa fa-edit"> Edit Product</i> </a>
                                            <a data-value="<?php echo e($d->status); ?>" data-id="<?php echo e($d->id); ?>"
                                               class="btn <?php if($d->status=='published'): ?>btn-warning <?php else: ?> btn-success <?php endif; ?> btn-xs"> <?php if($d->status=='published'): ?>
                                                    Unpublish <?php else: ?> Publish <?php endif; ?> Product </a>
                                            <a data-id="<?php echo e($d->id); ?>" class="btn btn-danger btn-xs"><i
                                                        class="fa fa-times">
                                                    Delete Product</i> </a>
                                        </div>
                                    </div>

                                </div>

                            <?php endforeach; ?>
                            <?php echo $data->render(); ?>

                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('js'); ?>
    <script>
        $('.btn-warning').click(function () {
            $.ajax({
                type: 'POST',
                url: '<?php echo e(url('/product/status')); ?>',
                data: {
                    'id': $(this).attr('data-id'),
                    'status': $(this).attr('data-value')
                },
                success: function (data) {
                    if (data == 'success') {
                        swal('Success', 'Product status updated', 'success');
                        location.reload();
                    }
                    else {
                        swal('Error', data, 'error');
                    }
                }

            });
        });

        $('.btn-success').click(function () {
            $.ajax({
                type: 'POST',
                url: '<?php echo e(url('/product/status')); ?>',
                data: {
                    'id': $(this).attr('data-id'),
                    'status': $(this).attr('data-value')
                },
                success: function (data) {
                    if (data == 'success') {
                        swal('Success', 'Product status updated', 'success');
                        location.reload();
                    }
                    else {
                        swal('Error', data, 'error');
                    }
                }

            });
        });

        $('.btn-danger').click(function () {
            var id = $(this).attr('data-id');


            swal({
                title: "Are you sure?",
                text: "You will not be able to recover this product!",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Yes, delete it!",
                closeOnConfirm: false,
                showLoaderOnConfirm: true
            }, function () {
                $.ajax({
                    type: 'POST',
                    url: '<?php echo e(url('/delete/product')); ?>',
                    data: {
                        'id': id
                    },
                    success: function (data) {
                        if (data == 'success') {
                            swal('Success', 'Product deleted', 'success');
                            location.reload();
                        }
                        else {
                            swal('Error', data, 'error');
                        }
                    }
                });
            });

        })
    </script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>