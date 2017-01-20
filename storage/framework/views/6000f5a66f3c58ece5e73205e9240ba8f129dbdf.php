<?php $__env->startSection('title','Add new Product'); ?>
<?php $__env->startSection('content'); ?>
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Add new Product</div>
                    <div class="panel-body">
                        <div class="form-horizontal">

                            <div class="form-group">
                                <label for="title" class="col-md-4 control-label">For</label>

                                <div class="col-md-6">
                                    <select class="form-control" id="pageId">
                                        <?php foreach(\App\FacebookPages::where('userId',Auth::user()->id)->get() as $fbPage): ?>
                                            <option value="<?php echo e($fbPage->pageId); ?>"><?php echo e($fbPage->pageName); ?></option>
                                        <?php endforeach; ?>
                                    </select>

                                </div>
                            </div>
                            <div class="form-group">
                                <label for="title" class="col-md-4 control-label">Product Title</label>

                                <div class="col-md-6">
                                    <input type="text" class="form-control" id="title">

                                </div>
                            </div>

                            <div class="form-group">
                                <label for="image" class="col-md-4 control-label">Image Upload</label>

                                <div class="col-md-6">
                                    <form id="uploadimage" method="post" enctype="multipart/form-data">
                                        <label>Select Your Image</label><br/>
                                        <input type="file" name="file"
                                               id="file"/><br>
                                        <input class="btn btn-xs btn-success" type="submit" value="Upload"
                                               id="imgUploadBtn"/>
                                        <input type="hidden" id="image">
                                        <div id="imgMsg"></div>
                                    </form>

                                </div>
                            </div>

                            <div class="form-group">
                                <label for="shortDescription" class="col-md-4 control-label">Short Description</label>

                                <div class="col-md-6">
                                    <input type="text" class="form-control" id="shortDescription">

                                </div>
                            </div>

                            <div class="form-group">
                                <label for="longDescription" class="col-md-4 control-label">Long Description</label>

                                <div class="col-md-6">
                                    <textarea id="longDescription" class="form-control" rows="3"></textarea>

                                </div>
                            </div>

                            <div class="form-group">
                                <label for="price" class="col-md-4 control-label">Price</label>

                                <div class="col-md-6">
                                    <input type="text" class="form-control" id="price">

                                </div>
                            </div>

                            <div class="form-group">
                                <label for="category" class="col-md-4 control-label">Category</label>

                                <div class="col-md-6">
                                    <select class="form-control" id="category">
                                        <?php foreach($categories as $category): ?>
                                            <option value="<?php echo e($category->name); ?>"><?php echo e($category->name); ?></option>
                                        <?php endforeach; ?>
                                        <?php if($wooCategories != "none"): ?>
                                            <?php foreach($wooCategories as $wc): ?>
                                                <option value="<?php echo e($wc['id']); ?>"><?php echo e($wc['name']); ?> ( WooCommerce )</option>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </select>

                                </div>
                            </div>

                            <div class="form-group">
                                <label for="featured" class="col-md-4 control-label">Featured</label>

                                <div class="col-md-6">
                                    <select class="form-control" id="featured">

                                        <option>no</option>
                                        <option>yes</option>

                                    </select>

                                </div>
                            </div>

                            <div class="form-group">
                                <label for="postFb" class="col-md-4 control-label">Post On Facebook</label>

                                <div class="col-md-6">
                                    <div class="checkbox">
                                        <label>
                                            <input id="postFb" type="checkbox"> Yes
                                        </label>
                                    </div>

                                </div>
                            </div>

                            <div class="form-group">
                                <label for="postWp" class="col-md-4 control-label">Create WooCommerce Product</label>

                                <div class="col-md-6">
                                    <div class="checkbox">
                                        <label>
                                            <input id="postWp" type="checkbox"> Yes
                                        </label>
                                    </div>

                                </div>
                            </div>


                            <div class="form-group">
                                <div class="col-md-6 col-md-offset-4">
                                    <button id="addproduct" class="btn btn-primary">
                                        <i class="fa fa-btn fa-plus"></i> Add Product
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

        $("#uploadimage").on('submit', (function (e) {
            e.preventDefault();
            $('#imgMsg').html("Please wait ...");
            $.ajax({
                type: "POST",
                url: "<?php echo e(url('/iup')); ?>",
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


        $('#addproduct').click(function () {
            var postFb = "no";
            var postWp = "no";
            if ($('#postFb').is(':checked')) {
                postFb = "yes";
            }
            if ($('#postWp').is(':checked')) {
                postWp = "yes";
            }

            $.ajax({
                type: 'POST',
                url: '<?php echo e(url('/addproduct')); ?>',
                data: {
                    'title': $('#title').val(),
                    'shortDescription': $('#shortDescription').val(),
                    'longDescription': $('#longDescription').val(),
                    'image': $('#image').val(),
                    'price': $('#price').val(),
                    'category': $('#category').val(),
                    'featured': $('#featured').val(),
                    'postFb': postFb,
                    'postWp': postWp
                },
                success: function (data) {
                    console.log(data);
                    swal('Success', 'Done', 'success');

                },
                error: function (data) {
                    swal('Error', data, 'error');
                }
            });
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>