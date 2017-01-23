@extends('layouts.app')
@section('title','Bot Settings')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Bot Settings</div>
                    <div class="panel-body">
                        <div class="form-horizontal">
                            <div class="form-group">
                                <label for="pageId" class="col-md-4 control-label">Select page</label>
                                <div class="col-md-6">
                                    <select class="form-control" id="pageId">
                                        @foreach(\App\FacebookPages::where('userId',Auth::user()->id)->get() as $page)
                                            <option value="{{$page->pageId}}">{{$page->pageName}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="name" class="col-md-4 control-label">Greeting Message</label>

                                <div class="col-md-6">
                                    <input type="text" class="form-control" id="message">
                                </div>
                            </div>


                            <div class="form-group">
                                <div class="col-md-6 col-md-offset-4">
                                    <button id="update" class="btn btn-primary">
                                        <i class="fa fa-btn fa-save"></i> Update Message and setup menu
                                    </button>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Select a page to subscribe for Bot</div>
                    <div class="panel-body">
                        <div class="list-group">
                            @foreach(\App\FacebookPages::where('userId',Auth::user()->id)->get() as $fb)
                                <li class="list-group-item"><a target="_blank" href="{{url('/bot/subscribe/')}}/{{$fb->pageId}}">{{$fb->pageName}}</a></li>
                            @endforeach

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
                url: '{{url('/bot/settings')}}',
                data: {
                    'message': $('#message').val(),
                    'pageId': $('#pageId').val()
                },
                success: function (data) {
                    swal("Success", 'Done !', "success");
                },
                error: function (data) {
                    swal('error', data, 'error');
                }
            });
        });
    </script>
@endsection
