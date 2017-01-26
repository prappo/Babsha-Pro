<?php $__env->startSection('title','Add new WooCommerce Category'); ?>
<?php $__env->startSection('content'); ?>
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Add new WooCommerce Category</div>
                    <div class="panel-body">
                        <div class="form-horizontal">
                            <div class="form-group">
                                <label for="title" class="col-md-4 control-label">Category Name</label>
                                <div class="col-md-6">
                                    <input type="text" class="form-control" id="name">

                                </div>
                            </div>


                            <div class="form-group">
                                <div class="col-md-6 col-md-offset-4">
                                    <button id="addcategory" class="btn btn-primary">
                                        <i class="fa fa-btn fa-plus"></i> Add Category
                                    </button>
                                </div>
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
        $('#addcategory').click(function () {
            var name = $('#name').val();
            $.ajax({
                type: 'POST',
                url: '<?php echo e(url('/woo/addcategory')); ?>',
                data: {
                    'name': name
                },
                success: function (data) {
                    if (data == 'success') {
                        swal('Success', 'Category added', 'success');
                    }
                    else {
                        swal('Error', data, 'error');
                    }
                }
            });
        });
    </script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>