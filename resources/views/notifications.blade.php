@extends('layouts.app')
@section('title','Notifications')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">Notifications
                        <button id="delAll" class="btn btn-danger btn-xs"><i class="fa fa-trahs"></i> Delete All
                            notifications
                        </button>
                    </div>

                    <div class="panel-body">


                        <table id="mytable" class="table table-striped table-bordered" cellspacing="0" width="100%">
                            <thead>
                            <tr>
                                <th>Content</th>
                                <th>Time</th>
                            </tr>
                            </thead>
                            <tbody>

                            @foreach($data as $d)
                                <tr>
                                    <td>{{$d->content}}</td>
                                    <td>{{$d->created_at}}</td>
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
        $('#delAll').click(function () {
            swal({
                title: "Are you sure?",
                text: "You will not be able to recover this data!",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Yes, delete all!",
                closeOnConfirm: false,
                showLoaderOnConfirm: true,
            }, function () {
                $.ajax({
                   type:'POST',
                    url:'{{url('/notifications/delete')}}',
                    data:{},
                    success:function (data) {
                        if(data=='success'){
                            swal('Success','Deleted !','success');
                            location.reload();
                        }
                        else{
                            swal('Error',data,'error');
                        }
                    },
                    error:function (data) {
                        console.log(data);
                    }

                });
            });
        });
    </script>
@endsection

