@extends('layouts.app')
@section('title','Update user')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Update User</div>
                    <div class="panel-body">
                        <div class="form-horizontal">
                            <div class="form-group">
                                <label for="name" class="col-md-4 control-label">Name</label>
                                <input id="userId" type="hidden" value="{{$id}}">
                                <div class="col-md-6">
                                    <input type="text" class="form-control" value="{{$name}}" id="name">

                                </div>
                            </div>
                            <div class="form-group">
                                <label for="email" class="col-md-4 control-label">Email</label>

                                <div class="col-md-6">
                                    <input type="email" class="form-control" value="{{$email}}"  id="email">

                                </div>
                            </div>



                            <div class="form-group">
                                <label for="newPass" class="col-md-4 control-label"> Password</label>

                                <div class="col-md-6">
                                    <input type="password" value="" class="form-control" id="pass">

                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-md-6 col-md-offset-4">
                                    <button id="update" class="btn btn-primary">
                                        <i class="fa fa-btn fa-save"></i> Update User
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

        $('#update').click(function () {
            $.ajax({
                type: 'POST',
                url: '{{url('/user/edit')}}',
                data: {
                    'id':$('#userId').val(),
                    'name': $('#name').val(),
                    'email': $('#email').val(),
                    'password': $('#pass').val()
                },
                success: function (data) {
                    if (data == 'success') {
                        swal('Success', 'User information updated', 'success');
                    }
                    else {
                        swal('Error', data, 'error');
                    }
                }
            });
        });
    </script>
@endsection
