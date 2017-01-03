@extends('layouts.app')
@section('title','Bot replies')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">Bot auto replies</div>
                    <br>
                    <div class="form-horizontal">

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

                            @foreach($data as $d)
                                <tr>
                                    <td>{{$d->message}}</td>
                                    <td>{{$d->reply}}</td>
                                    <td>
                                        <button data-id="{{$d->id}}" class="btn btn-xs btn-danger"><i class="fa fa-trash"></i>
                                            Delete
                                        </button>
                                    </td>
                                </tr>
                            @endforeach

                            </tbody>
                        </table>

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
            $.ajax({
                type: 'POST',
                url: '{{url('/bot/del')}}',
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
                url: '{{url('/bot/add')}}',
                data: {
                    'message':$('#message').val(),
                    'reply':$('#reply').val()
                },
                success:function (data) {
                    if(data=='success'){
                        swal('Success','Added','success');
                        location.reload();
                    }
                    else{
                        swal('Error',data,'error');
                    }
                }
            });
        })
    </script>
@endsection
