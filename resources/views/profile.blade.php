@extends('layouts.app')
@section('title','Profile')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Update Profile</div>
                    <div class="panel-body">
                        <div class="form-horizontal">
                            <div class="form-group">
                                <label for="name" class="col-md-4 control-label">Name</label>

                                <div class="col-md-6">
                                    <input type="text" value="{{$name}}" class="form-control" id="name">

                                </div>
                            </div>
                            <div class="form-group">
                                <label for="email" class="col-md-4 control-label">Email</label>

                                <div class="col-md-6">
                                    <input type="email" class="form-control" value="{{$email}}" id="email">

                                </div>
                            </div>

                            <div class="form-group">
                                <label for="oldPass" class="col-md-4 control-label">Old Password</label>

                                <div class="col-md-6">
                                    <input type="password" class="form-control" id="oldPass">

                                </div>
                            </div>

                            <div class="form-group">
                                <label for="newPass" class="col-md-4 control-label">New Password</label>

                                <div class="col-md-6">
                                    <input type="password" class="form-control" id="newPass">

                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-md-6 col-md-offset-4">
                                    <button id="update" class="btn btn-primary">
                                        <i class="fa fa-btn fa-save"></i> Update Profile
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
                url: '{{url('/profile')}}',
                data: {
                    'name': $('#name').val(),
                    'email': $('#email').val(),
                    'oldPass': $('#oldPass').val(),
                    'newPass': $('#newPass').val()
                },
                success: function (data) {
                    if (data == 'success') {
                        swal('Success', 'Updated', 'success');
                    }
                    else {
                        swal('Error', data, 'error');
                    }
                }
            });
        });
    </script>
@endsection
