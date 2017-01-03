@extends('layouts.app')
@section('title','Update Product')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Update WooCommerce Product</div>
                    <div class="panel-body">
                        <div class="form-horizontal">
                            <div class="form-group">
                                <label for="title" class="col-md-4 control-label">Product Title</label>

                                <div class="col-md-6">
                                    <input type="text" value="{{$name}}" class="form-control" id="title">

                                </div>
                            </div>
                            <input type="hidden" id="proId" value="{{$id}}">


                            <div class="form-group">
                                <label for="shortDescription" class="col-md-4 control-label">Short Description</label>

                                <div class="col-md-6">
                                    <input type="text" value="{{$short_description}}" class="form-control" id="shortDescription">

                                </div>
                            </div>

                            <div class="form-group">
                                <label for="longDescription" class="col-md-4 control-label">Long Description</label>

                                <div class="col-md-6">
                                    <textarea id="longDescription" class="form-control" rows="3">{{$description}}</textarea>

                                </div>
                            </div>

                            <div class="form-group">
                                <label for="price" class="col-md-4 control-label">Price</label>

                                <div class="col-md-6">
                                    <input type="text" value="{{$price}}" class="form-control" id="price">

                                </div>
                            </div>

                            <div class="form-group">
                                <label for="category" class="col-md-4 control-label">Category</label>

                                <div class="col-md-6">
                                    <select class="form-control" id="category">
                                        @foreach($categories as $cat)
                                            <option @if($cat['name'] == $category) selected @endif value="{{$cat['id']}}"  >{{$cat['name']}}</option>
                                        @endforeach
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
@endsection

@section('js')
    <script>

        $('#updateproduct').click(function () {
            $('#updateproduct').html("<i class='fa fa-btn fa-refresh'></i> Please wait ....");
            $.ajax({
                type:'POST',
                url:'{{url('/woo/update/product')}}',
                data:{
                    'id':$('#proId').val(),
                    'title':$('#title').val(),
                    'short_description':$('#shortDescription').val(),
                    'description':$('#longDescription').val(),
                    'image':$('#image').val(),
                    'price':$('#price').val(),
                    'category':$('#category').val(),
                    'featured':$('#featured').val()

                },
                success:function (data) {
                    if(data == 'success'){
                        swal('Success','Successfully updated');
                        $('#updateproduct').html("<i class='fa fa-btn fa-refresh'></i> Update");
                        location.reload();

                    }
                    else{
                        swal('Error',data,'error');
                        $('#updateproduct').html("<i class='fa fa-btn fa-refresh'></i> Update");
                    }
                }
            });
        });

        $("#uploadimage").on('submit', (function (e) {
            e.preventDefault();
            $('#imgMsg').html("Please wait ...");
            $.ajax({
                url: "{{url('/iup')}}",
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
@endsection
