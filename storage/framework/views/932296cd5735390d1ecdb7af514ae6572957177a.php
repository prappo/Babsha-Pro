<?php $__env->startSection('title','All categories'); ?>
<?php $__env->startSection('content'); ?>
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">All categories</div>

                    <div class="panel-body">
                        <div class="col-lg-5">
                            <ul class="list-group">
                                <?php foreach($data as $d): ?>
                                    <li class="list-group-item"><?php echo e($d->name); ?> <span class="badge"><button
                                                    data-id="<?php echo e($d->id); ?>"
                                                    class="btn btn-danger btn-xs"><i
                                                        class="fa fa-times"></i> Delete</button><button
                                                    data-id="<?php echo e($d->id); ?>" data-name="<?php echo e($d->name); ?>"
                                                    class="btn btn-xs btn-primary"><i
                                                        class="fa fa-edit"></i> Edit</button> </span></li>
                                <?php endforeach; ?>
                            </ul>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('js'); ?>
    <script>
        $('.btn-danger').click(function () {
            var id = $(this).attr('data-id');
            swal({
                title: "Are you sure?",
                text: "You will not be able to recover this data!",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Yes, delete it!",
                closeOnConfirm: false,
                showLoaderOnConfirm: true
            }, function () {
                $.ajax({
                    type: 'POST',
                    url: '<?php echo e(url('/category/delete')); ?>',
                    data: {
                        id: id
                    },
                    success: function (data) {
                        if (data == 'success') {
                            swal('Success', 'Deleted', 'success');
                            location.reload();
                        }
                        else {
                            swal('Error', data, 'error');
                        }
                    }
                });
            });

        });

        $('.btn-primary').click(function () {
            var id = $(this).attr('data-id');
            var name = $(this).attr('data-name');
            swal({
                title: "Change category name",
                text: "Write something interesting:",
                type: "input",
                value: name,
                showCancelButton: true,
                closeOnConfirm: false,
                animation: "slide-from-top",
                inputPlaceholder: "Write something",
                showLoaderOnConfirm: true
            }, function (inputValue) {

                if (inputValue === false) return false;
                if (inputValue === "") {
                    swal.showInputError("You need to write something!");
                    return false
                } else {

                    $.ajax({
                        type: 'POST',
                        url: '<?php echo e(url('/category/edit')); ?>',
                        data: {
                            'id': id,
                            'name': inputValue
                        },
                        success: function (data) {
                            if (data == 'success') {
                                swal('Success', 'Updated', 'success');
                                location.reload();
                            }
                            else {
                                swal('Error', data, 'error');
                            }
                        }
                    });
                }

            });

        })
    </script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>