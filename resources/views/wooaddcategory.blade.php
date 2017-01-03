@extends('layouts.app')
@section('title','Add new WooCommerce Category')
@section('content')
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
@endsection
@section('js')
    <script>
        $('#addcategory').click(function () {
            var name = $('#name').val();
            $.ajax({
                type: 'POST',
                url: '{{url('/woo/addcategory')}}',
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
@endsection