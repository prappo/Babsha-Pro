<?php $__env->startSection('title','User Lists'); ?>
<?php $__env->startSection('content'); ?>
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">User list</div>

                    <div class="panel-body">

                        <table id="mytable" class="table table-striped table-bordered" cellspacing="0" width="100%">
                            <thead>
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Type</th>
                                <th>Action</th>

                            </tr>
                            </thead>
                            <tbody>

                            <?php foreach($data as $d): ?>
                                <tr>
                                    <td><?php echo e($d->name); ?></td>
                                    <td><?php echo e($d->email); ?></td>
                                    <td><?php echo e($d->type); ?></td>
                                    <td>
                                        <div class="btn-group-xs" role="group">
                                            <button data-id="<?php echo e($d->id); ?>" class="btn btn-xs btn-danger"><i
                                                        class="fa fa-trash"></i> Delete
                                            </button>
                                            <a href="<?php echo e(url('/user')); ?>/<?php echo e($d->id); ?>" class="btn btn-xs btn-primary"><i
                                                        class="fa fa-edit"></i> Edit</a>
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
        $('.btn-danger').click(function () {
            var id = $(this).attr('data-id');
            swal({
                title: "Are you sure?",
                text: "Do you want to delete this user ?",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Yes, delete user!",
                closeOnConfirm: false,
                showLoaderOnConfirm: true,
            }, function () {
                $.ajax({
                    type: 'POST',
                    url: '<?php echo e(url('/user/del')); ?>',
                    data: {
                        'id': id
                    },
                    success: function (data) {
                        if (data == 'success') {
                            swal('Success', 'Deleted!', 'success');
                            location.reload();
                        }
                        else {
                            swal('Error', data, 'error');
                        }
                    },
                    error: function (data) {
                        swal('Error', data, 'error');
                    }
                });
            });

        })
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>