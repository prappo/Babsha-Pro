@extends('layouts.appblank')
@section('title','Update Information')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">

                <div class="form-horizontal">
                    <div class="form-group">
                        <label for="name" class="col-md-4 control-label">Name</label>

                        <div class="col-md-8">
                            <input id="name" type="text" class="form-control" value="{{ $name }}">

                        </div>
                    </div>

                    <div class="form-group">
                        <label for="mobile" class="col-md-4 control-label">Mobile</label>

                        <div class="col-md-8">
                            <input type="text" class="form-control" id="mobile" value="{{ $mobile }}">

                        </div>
                    </div>

                    <div class="form-group">
                        <label for="street" class="col-md-4 control-label">Street</label>

                        <div class="col-md-8">
                            <input id="street" type="text" class="form-control" value="{{ $street }}">

                        </div>
                    </div>

                    <div class="form-group">
                        <label for="postal_code" class="col-md-4 control-label">Postal Code</label>

                        <div class="col-md-8">
                            <input id="postal_code" type="text" class="form-control" value="{{ $postal_code }}">

                        </div>
                    </div>

                    <div class="form-group">
                        <label for="city" class="col-md-4 control-label">City</label>

                        <div class="col-md-8">
                            <input id="city" type="text" class="form-control" value="{{ $city }}">

                        </div>
                    </div>

                    <div class="form-group">
                        <label for="state" class="col-md-4 control-label">State</label>

                        <div class="col-md-8">
                            <input id="state" type="text" class="form-control" value="{{ $state }}">

                        </div>
                    </div>

                    <div class="form-group">
                        <label for="country" class="col-md-4 control-label">Country</label>

                        <div class="col-md-8">
                            <input id="country" type="text" class="form-control" value="{{ $country }}">

                        </div>
                    </div>
                    <input id="fbId" type="hidden" value="{{$fbId}}">
{{--                    <input type="hidden" id="account_token" value="{{$accountToken}}">--}}

                    <div class="form-group">
                        <div class="col-md-8 col-md-offset-4">
                            <button id="update" class="btn btn-primary">
                                <i class="fa fa-btn fa-save"></i> Update
                            </button>
                            <label id="msg"></label>
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
            var account_token = $("#account_token").val();
            $(this).html('<i class="fa fa-btn fa-refresh"></i> Please wait..');
            $.ajax({
                type: 'POST',
                url: '{{secure_url('/info/update')}}',
                data: {
                    'name': $('#name').val(),
                    'mobile': $('#mobile').val(),
                    'city': $('#city').val(),
                    'street': $('#street').val(),
                    'state': $('#state').val(),
                    'country': $('#country').val(),
                    'postal_code': $('#postal_code').val(),
                    'id': $('#fbId').val()
                },
                success: function (data) {
                    if (data == 'success') {
                        swal("Success", 'Thanks for updating your shipping information', 'success');
                        {{--$('#update').html('<i class="fa fa-btn fa-save"></i> Update');--}}
                        {{--window.location.replace("{{$redirect_uri}}&authorization_code=1337");--}}
                        $('#update').html('<i class="fa fa-btn fa-save"></i> Update');
                        window.close();
                    }
                    else {
                        swal('Error', 'Something went wrong . Please try again later', 'error');
                        $('#update').html('<i class="fa fa-btn fa-save"></i> Update');
                    }
                },
                error:function (data) {
                    swal(data);
                }
            });

        });
    </script>
@endsection