<?php $__env->startSection('title','Bot Settings'); ?>
<?php $__env->startSection('content'); ?>
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Bot Settings</div>
                    <div class="panel-body">
                        <div class="form-horizontal">
                            <div class="form-group">
                                <label for="name" class="col-md-4 control-label">Greeting Message</label>

                                <div class="col-md-6">
                                    <input type="text" class="form-control" id="message">
                                </div>
                            </div>


                            <div class="form-group">
                                <div class="col-md-6 col-md-offset-4">
                                    <button id="update" class="btn btn-primary">
                                        <i class="fa fa-btn fa-save"></i> Update
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

        $('#update').click(function () {
            $.ajax({
                type: 'POST',
                url: '<?php echo e(url('/bot/settings')); ?>',
                data: {
                    'message': $('#message').val()
                },
                success: function (data) {
                    swal("Success",'Done !',"success");
                },
                error:function (data) {
                    swal('error',data,'error');
                }
            });
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>