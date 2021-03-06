<?php $__env->startSection('title','Update Product'); ?>
<?php $__env->startSection('content'); ?>
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Update Product</div>
                    <div class="panel-body">
                        <div class="form-horizontal">
                            <div class="form-group">
                                <label for="title" class="col-md-4 control-label">Product Title</label>

                                <div class="col-md-6">
                                    <input type="text" value="<?php echo e($title); ?>" class="form-control" id="title">

                                </div>
                            </div>
                            <input type="hidden" id="proId" value="<?php echo e($id); ?>">
                            <div class="form-group">
                                <label for="image" class="col-md-4 control-label">Image Upload</label>

                                <div class="col-md-6">
                                    <form id="uploadimage" method="post" enctype="multipart/form-data">
                                        <label>Select Your Image</label><br/>
                                        <input type="file" name="file"
                                               id="file"/><br>
                                        <input class="btn btn-xs btn-success" type="submit" value="Upload"
                                               id="imgUploadBtn"/>
                                        <input type="hidden" value="<?php echo e($image); ?>" id="image">
                                        <div id="imgMsg"></div>
                                    </form>

                                </div>
                            </div>

                            <div class="form-group">
                                <label for="shortDescription" class="col-md-4 control-label">Short Description</label>

                                <div class="col-md-6">
                                    <input type="text" value="<?php echo e($shortDescription); ?>" class="form-control" id="shortDescription">

                                </div>
                            </div>

                            <div class="form-group">
                                <label for="longDescription" class="col-md-4 control-label">Long Description</label>

                                <div class="col-md-6">
                                    <textarea id="longDescription" class="form-control" rows="3"><?php echo e($longDescription); ?></textarea>

                                </div>
                            </div>

                            <div class="form-group">
                                <label for="price" class="col-md-4 control-label">Price</label>

                                <div class="col-md-6">
                                    <input type="text" value="<?php echo e($price); ?>" class="form-control" id="price">

                                </div>
                            </div>

                            <div class="form-group">
                                <label for="category" class="col-md-4 control-label">Category</label>

                                <div class="col-md-6">
                                    <select class="form-control" id="category">
                                        <?php foreach($categories as $cat): ?>
                                            <option <?php if($cat->name == $category): ?> selected <?php endif; ?> ><?php echo e($cat->name); ?></option>
                                        <?php endforeach; ?>
                                    </select>

                                </div>
                            </div>

                            <div class="form-group">
                                <label for="featured" class="col-md-4 control-label">Featured</label>

                                <div class="col-md-6">
                                    <select class="form-control" id="featured">

                                            <option <?php if($featured == "no"): ?> selected <?php endif; ?> >no</option>
                                            <option <?php if($featured == "yes"): ?> selected <?php endif; ?> >yes</option>

                                    </select>

                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-md-6 col-md-offset-4">
                                    <button id="updateproduct" class="btn btn-primary">
                                        <i class="fa fa-btn fa-refresh"></i> Update
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

        $('#updateproduct').click(function () {
            $('#updateproduct').html("<i class='fa fa-btn fa-refresh'></i> Please wait ....");
            $.ajax({
                type:'POST',
                url:'<?php echo e(url('/update/product')); ?>',
                data:{
                    'id':$('#proId').val(),
                    'title':$('#title').val(),
                    'shortDescription':$('#shortDescription').val(),
                    'longDescription':$('#longDescription').val(),
                    'image':$('#image').val(),
                    'price':$('#price').val(),
                    'category':$('#category').val(),
                    'featured':$('#featured').val()

                },
                success:function (data) {
                    if(data == 'success'){
                        swal('Success','Successfully updated');
                        $('#updateproduct').html("<i class='fa fa-btn fa-refresh'></i> Update");

                    }
                    else{
                        swal('Error',data,'error');
                    }
                }
            });
        });

        $("#uploadimage").on('submit', (function (e) {
            e.preventDefault();
            $('#imgMsg').html("Please wait ...");
            $.ajax({
                url: "<?php echo e(url('/iup')); ?>",
                type: "POST",
                data: new FormData(this),
                contentType: false,
                cache: false,
                processData: false,
                success: function (data) {
                    if (data['status'] == 'success') {
                        $('#image').val(data['fileName']);
                        $('#imgMsg').html("Your file uploaded and it's name : " + data['fileName']);
                        swal('Success!', 'Image File succefully uploaded', 'success');
                        $('#imgPreview').attr('src', 'uploads/' + data['fileName']);

                    }
                    else {
                        swal('Error!', data, 'error');
                        $('#imgMsg').html("Something went wrong can't upload image");

                    }
                }
            });
        }));
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>