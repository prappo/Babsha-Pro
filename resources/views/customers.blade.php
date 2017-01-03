@extends('layouts.app')
@section('title','Customers')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">Customers</div>

                    <div class="panel-body">
                        @foreach($data as $d)


                            <div class="row">
                                <div class="panel panel-default">
                                    <!-- Default panel contents -->
                                    <div class="panel-heading">{{$d->name}}</div>
                                    <div class="panel-body">
                                        <div class="row">
                                            <div class="col-md-4"><img class="img-thumbnail" src="{{$d->image}}"
                                                                       alt="{{$d->name}}"></div>
                                            <div class="col-md-8">
                                                <div class="btn-group-xs" role="group">
                                                    <button data-id="{{$d->id}}" class="btn btn-danger"><i
                                                                class="fa fa-trash"></i> Delete customer
                                                    </button>
                                                    <button data-id="{{$d->id}}"
                                                            class="btn bot @if($d->bot == 'no') btn-success  @else btn-warning @endif"
                                                            data-id="{{$d->id}}"><i
                                                                class="fa fa-rocket"></i> @if($d->bot == 'no')
                                                            Enable @else Disable @endif Bot
                                                    </button>
                                                </div>
                                                <br>
                                                <div class="row">
                                                    <div class="col-md-8">
                                                        <div class="form-group">
                                                            <textarea id="msg_{{$d->fbId}}" class="form-control"
                                                                      placeholder="Type your message here"
                                                                      role="4"></textarea>

                                                        </div>
                                                    </div>
                                                    <div class="col-md-8">
                                                        <div class="form-group">
                                                            <div class="btn-group" role="group" aria-label="...">
                                                                <button type="button" data-id="{{$d->fbId}}"
                                                                        class="bimg i{{$d->fbId}} btn btn-default"><i
                                                                            class="fa fa-image"></i></button>
                                                                <button type="button" data-id="{{$d->fbId}}"
                                                                        class="bfile f{{$d->fbId}} btn btn-default"><i
                                                                            class="fa fa-file"></i></button>
                                                                <button type="button"  data-id="{{$d->fbId}}"
                                                                        class="baudio a{{$d->fbId}} btn btn-default"><i
                                                                            class="fa fa-music"></i></button>
                                                                <button type="button"  data-id="{{$d->fbId}}"
                                                                        class="bvideo v{{$d->fbId}} btn btn-default"><i
                                                                            class="fa fa-video-camera"></i></button>
                                                            </div>
                                                            <input type="hidden" id="img_{{$d->fbId}}">
                                                            <input type="hidden" id="file_{{$d->fbId}}">
                                                            <input type="hidden" id="audio_{{$d->fbId}}">
                                                            <input type="hidden" id="video_{{$d->fbId}}">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-8">
                                                        <div class="form-group">
                                                            <button data-id="{{$d->fbId}}" class="btn sendbtn btn-success"><i class="fa fa-send"></i>
                                                                Send message
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>

                                    <!-- Table -->
                                    <table class="table">
                                        <thead>
                                        <tr>
                                            <td><b>Street</b></td>
                                            <td><b>Postal Code</b></td>
                                            <td><b>City</b></td>
                                            <td><b>Mobile</b></td>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr>
                                            <td>{{$d->street}}</td>
                                            <td>{{$d->postal_code}}</td>
                                            <td>{{$d->city}}</td>
                                            <td>{{$d->mobile}}</td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>



                        @endforeach
                    </div>
                    {!! $data->render() !!}
                </div>
            </div>
        </div>
    </div>
    </div>
@endsection

@section('js')
    <script>
        $('.btn-danger').click(function () {
            var id = $(this).attr('data-id');


            swal({
                title: "Are you sure?",
                text: "You will not be able to recover this data!",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Yes, delete it!",
                closeOnConfirm: false,
                showLoaderOnConfirm: true
            }, function () {
                $.ajax({
                    type: 'POST',
                    url: '{{url('/customer/delete')}}',
                    data: {
                        'id': id
                    },
                    success: function (data) {
                        if (data == 'success') {
                            swal('Success', 'Customer deleted', 'success');
                            location.reload();
                        }
                        else {
                            swal('Error', data, 'error');
                        }
                    }
                });
            });
        });

        $('.bot').click(function () {
            var id = $(this).attr('data-id');


            swal({
                title: "Are you sure?",
                text: "Do you want to perform this actionn for this customer ?",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Yes",
                closeOnConfirm: false,
                showLoaderOnConfirm: true
            }, function () {
                $.ajax({
                    type: 'POST',
                    url: '{{url('/customer/bot')}}',
                    data: {
                        'id': id
                    },
                    success: function (data) {
                        if (data == 'success') {
                            swal('Success', 'Done', 'success');
                            location.reload();
                        }
                        else {
                            swal('Error', data, 'error');
                        }
                    }
                });
            });
        });

        $('.bimg').click(function () {
            var id = $(this).attr('data-id');
            swal({
                title: "Image!",
                text: "Enter image link",
                type: "input",
                inputValue:$('#img_'+id).val(),
                showCancelButton: true,
                closeOnConfirm: false,
                animation: "slide-from-top",
                inputPlaceholder: "Image link here"
            }, function (inputValue) {
                if (inputValue === false) return false;

                $('#img_'+id).val(inputValue);
                swal('Success','Done','success');
                if($('#img_'+id).val() == ""){
                    $('.i'+id).removeClass('btn-success');
                }
                else{
                    $('.i'+id).addClass('btn-success');
                }

            });
        });

        $('.bfile').click(function () {
            var id = $(this).attr('data-id');
            swal({
                title: "File!",
                text: "Enter File link",
                type: "input",
                inputValue:$('#file_'+id).val(),
                showCancelButton: true,
                closeOnConfirm: false,
                animation: "slide-from-top",
                inputPlaceholder: "File link here"
            }, function (inputValue) {
                if (inputValue === false) return false;

                $('#file_'+id).val(inputValue);
                swal('Success','Done','success');
                if($('#file_'+id).val() == ""){
                    $('.f'+id).removeClass('btn-success');
                }
                else{
                    $('.f'+id).addClass('btn-success');
                }

            });
        });

        $('.baudio').click(function () {
            var id = $(this).attr('data-id');
            swal({
                title: "Audio!",
                text: "Enter audio link",
                type: "input",
                inputValue:$('#audio_'+id).val(),
                showCancelButton: true,
                closeOnConfirm: false,
                animation: "slide-from-top",
                inputPlaceholder: "Audio link here"
            }, function (inputValue) {
                if (inputValue === false) return false;

                $('#audio_'+id).val(inputValue);
                swal('Success','Done','success');
                if($('#audio_'+id).val() == ""){
                    $('.a'+id).removeClass('btn-success');
                }
                else{
                    $('.a'+id).addClass('btn-success');
                }

            });
        });

        $('.bvideo').click(function () {
            var id = $(this).attr('data-id');
            swal({
                title: "Video!",
                text: "Enter video link",
                type: "input",
                inputValue:$('#video_'+id).val(),
                showCancelButton: true,
                closeOnConfirm: false,
                animation: "slide-from-top",
                inputPlaceholder: "Video link here"
            }, function (inputValue) {
                if (inputValue === false) return false;

                $('#video_'+id).val(inputValue);
                swal('Success','Done','success');
                if($('#video_'+id).val() == ""){
                    $('.v'+id).removeClass('btn-success');
                }
                else{
                    $('.v'+id).addClass('btn-success');
                }

            });
        });

        $('.sendbtn').click(function () {
            var sender = $(this).attr('data-id');
            $.ajax({
               type:'POST',
                url:'{{url('/send/to/user')}}',
                data:{
                    'sender':sender,
                    'message':$('#msg_'+sender).val(),
                    'audio':$('#audio_'+sender).val(),
                    'file':$('#file_'+sender).val(),
                    'image':$('#img_'+sender).val(),
                    'video':$('#video_'+sender).val()
                },
                success:function (data) {
                        swal('Success','Done !','success');
                },
                error:function (data) {
                    swal('Error',data,'error');
                }
            });
        })
    </script>

@endsection
