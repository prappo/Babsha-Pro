<?php $__env->startSection('title','Bot replies'); ?>
<?php $__env->startSection('content'); ?>
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">Bot auto replies</div>
                    <br>
                    <div class="form-horizontal">

                        <div class="form-group">
                            <label for="pageId" class="col-md-2 control-label">Select Page</label>

                            <div class="col-md-4">
                                <select id="pageId" class="form-control">
                                    <?php foreach(\App\FacebookPages::where('userId',Auth::user()->id)->get() as $page): ?>
                                        <option value="<?php echo e($page->pageId); ?>"><?php echo e($page->pageName); ?></option>
                                    <?php endforeach; ?>
                                </select>

                            </div>

                        </div>

                        <div class="form-group">
                            <label for="message" class="col-md-2 control-label">Message</label>

                            <div class="col-md-4">
                                <input value="" type="text"
                                       class="form-control" id="message">

                            </div>

                        </div>

                        <div class="form-group">
                            <label for="reply" class="col-md-2 control-label">Reply</label>

                            <div class="col-md-4">
                                <input value="" type="text"
                                       class="form-control" id="reply">

                            </div>

                        </div>

                        <div class="form-group">
                            <div class="col-md-2"></div>
                            <div class="col-md-4">
                                <button id="add" class="btn btn-success"><i class="fa fa-plus"></i> Add bot reply
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="panel-body">

                        <table id="mytable" class="table table-striped table-bordered" cellspacing="0" width="100%">
                            <thead>
                            <tr>
                                <th>Message</th>
                                <th>Reply</th>
                                <th>Action</th>

                            </tr>
                            </thead>
                            <tbody>

                            <?php foreach($data as $d): ?>
                                <tr>
                                    <td><?php echo e($d->message); ?></td>
                                    <td><?php echo e($d->reply); ?></td>
                                    <td>
                                        <button data-id="<?php echo e($d->id); ?>" class="btn btn-xs btn-danger"><i
                                                    class="fa fa-trash"></i>
                                            Delete
                                        </button>
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
            $.ajax({
                type: 'POST',
                url: '<?php echo e(url('/bot/del')); ?>',
                data: {
                    'id': id
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
        $('#add').click(function () {
            $.ajax({
                type: 'POST',
                url: '<?php echo e(url('/bot/add')); ?>',
                data: {
                    'message': $('#message').val(),
                    'reply': $('#reply').val(),
                    'pageId':$('#pageId').val()
                },
                success: function (data) {
                    if (data == 'success') {
                        swal('Success', 'Added', 'success');
                        location.reload();
                    }
                    else {
                        swal('Error', data, 'error');
                    }
                }
            });
        })
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>