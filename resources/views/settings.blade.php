@extends('layouts.app')
@section('title','Settings')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Update Shop Settings Settings</div>
                    <div class="panel-body">
                        <div class="form-horizontal">

                            <div class="row">
                                <div class="col-md-4"></div>
                                <div class="col-md-6">
                                    <a class="btn btn-primary" href="{{$loginUrl}}">Connect with facebook</a>
                                </div>
                            </div>
                            <br>
                            <div class="form-group">
                                <label for="title" class="col-md-4 control-label">Facebook Pages</label>

                                <div class="col-md-6">
                                    <select class="form-control">
                                        @foreach(\App\FacebookPages::where('userId',Auth::user()->id)->get() as $fbPage)
                                            <option>{{$fbPage->pageName}}</option>
                                        @endforeach
                                    </select>

                                </div>
                            </div>

                            <div class="form-group">
                                <label for="title" class="col-md-4 control-label">Shop Title</label>

                                <div class="col-md-6">
                                    <input type="text"
                                           value="{{\App\Settings::where('userId',Auth::user()->id)->value('title')}}"
                                           class="form-control" id="title">

                                </div>
                            </div>

                            <div class="form-group">
                                <label for="subTitle" class="col-md-4 control-label">Shop Sub Title</label>

                                <div class="col-md-6">
                                    <input type="text" value="{{\App\Settings::where('userId',Auth::user()->id)->value('subTitle')}}"
                                           class="form-control" id="subTitle">

                                </div>
                            </div>

                            <div class="form-group">
                                <label for="phone" class="col-md-4 control-label">Phone</label>

                                <div class="col-md-6">
                                    <input type="text" value="{{\App\Settings::where('userId',Auth::user()->id)->value('phone')}}"
                                           class="form-control" id="phone">

                                </div>
                            </div>

                            <div class="form-group">
                                <label for="email" class="col-md-4 control-label">Email</label>

                                <div class="col-md-6">
                                    <input type="email" value="{{\App\Settings::where('userId',Auth::user()->id)->value('email')}}"
                                           class="form-control" id="email">

                                </div>
                            </div>

                            <div class="form-group">
                                <label for="address" class="col-md-4 control-label">Address</label>

                                <div class="col-md-6">
                                    <input type="text" value="{{\App\Settings::where('userId',Auth::user()->id)->value('address')}}"
                                           class="form-control" id="address">

                                </div>
                            </div>

                            <div class="form-group">
                                <label for="afterOrderMsg" class="col-md-4 control-label">After order Message</label>

                                <div class="col-md-6">
                                    <input type="text" value="{{\App\Settings::where('userId',Auth::user()->id)->value('afterOrderMsg')}}"
                                           class="form-control" id="afterOrderMsg">

                                </div>
                            </div>

                            <div class="form-group">
                                <label for="map" class="col-md-4 control-label">Map Data</label>

                                <div class="col-md-6">
                                    <input type="text" value="{{\App\Settings::where('userId',Auth::user()->id)->value('map')}}"
                                           class="form-control" id="map">

                                </div>
                            </div>

                            <div class="form-group">

                                <label for="image" class="col-md-4 control-label">Logo</label>

                                <div class="col-md-6">
                                    <form id="uploadimage" method="post" enctype="multipart/form-data">
                                        <label>Select Your Image</label><br/>
                                        <input type="file" name="file"
                                               id="file"/><br>
                                        <input class="btn btn-xs btn-success" type="submit" value="Upload"
                                               id="imgUploadBtn"/>
                                        <input type="hidden" value="{{\App\Settings::where('userId',Auth::user()->id)->value('logo')}}"
                                               id="image">
                                        <div id="imgMsg"></div>
                                    </form>

                                </div>
                            </div>

                            <div class="form-group">
                                <label for="currency" class="col-md-4 control-label">Currency</label>

                                <div class="col-md-6">
                                    <select class="form-control" id="currency">
                                        <option <?php if (\App\Settings::where('userId',Auth::user()->id)->value('currency') == "USD") {
                                            echo "selected";
                                        } ?> >USD
                                        </option>
                                        <option <?php if (\App\Settings::where('userId',Auth::user()->id)->value('currency') == "EURO") {
                                            echo "selected";
                                        } ?> >EURO
                                        </option>
                                        <option <?php if (\App\Settings::where('userId',Auth::user()->id)->value('currency') == "BDT") {
                                            echo "selected";
                                        } ?> >BDT
                                        </option>

                                        <option <?php if (\App\Settings::where('userId',Auth::user()->id)->value('currency') == "GBP") {
                                            echo "selected";
                                        } ?> >GBP
                                        </option>
                                    </select>

                                </div>
                            </div>

                            <div class="form-group">
                                <label for="tax" class="col-md-4 control-label">Tax</label>

                                <div class="col-md-6">
                                    <input type="text" value="{{\App\Settings::where('userId',Auth::user()->id)->value('tax')}}"
                                           class="form-control" id="tax">

                                </div>
                            </div>


                            <div class="form-group">
                                <label for="shipping" class="col-md-4 control-label">Shipping Cost</label>

                                <div class="col-md-6">
                                    <input type="text" value="{{\App\Settings::where('userId',Auth::user()->id)->value('shipping')}}"
                                           class="form-control" id="shipping">

                                </div>
                            </div>

                            <div class="form-group">
                                <label for="paymentMethod" class="col-md-4 control-label">Payment Method</label>

                                <div class="col-md-6">
                                    <select class="form-control" id="paymentMethod">
                                        <option value="paypal">PayPal</option>
                                    </select>

                                </div>
                            </div>
                            <div class="form-group">
                                <label for="paypalClientId" class="col-md-4 control-label">PayPal Client ID</label>

                                <div class="col-md-6">
                                    <input type="text" value="{{\App\Http\Controllers\Settings::get('paypalClientId')}}"
                                           class="form-control" id="paypalClientId">

                                </div>
                            </div>

                            <div class="form-group">
                                <label for="paypalClientSecret" class="col-md-4 control-label">PayPal Client
                                    Secret</label>

                                <div class="col-md-6">
                                    <input type="text"
                                           value="{{\App\Http\Controllers\Settings::get('paypalClientSecret')}}"
                                           class="form-control" id="paypalClientSecret">

                                </div>
                            </div>
                            @if(\App\Http\Controllers\PackagesController::isMyPackage('woo'))
                                {{-- woo commerce settings start--}}
                                <div class="form-group">
                                    <label for="wpUrl" class="col-md-4 control-label">WordPress URL</label>

                                    <div class="col-md-6">
                                        <input type="text" value="{{\App\Http\Controllers\Settings::get('wpUrl')}}"
                                               class="form-control" id="wpUrl">

                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="wooConsumerKey" class="col-md-4 control-label">WooCommerce Consumer
                                        Key</label>

                                    <div class="col-md-6">
                                        <input type="text"
                                               value="{{\App\Http\Controllers\Settings::get('wooConsumerKey')}}"
                                               class="form-control" id="wooConsumerKey">

                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="wooConsumerSecret" class="col-md-4 control-label">WooCommerce
                                        Secret</label>

                                    <div class="col-md-6">
                                        <input type="text"
                                               value="{{\App\Http\Controllers\Settings::get('wooConsumerSecret')}}"
                                               class="form-control" id="wooConsumerSecret">

                                    </div>
                                </div>
                            @endif

                            {{-- woo commerce settings end--}}


                            <div class="form-group">
                                <div class="col-md-6 col-md-offset-4">
                                    <button id="update" class="btn btn-primary">
                                        <i class="fa fa-btn fa-save"></i> Update
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
        if ($('#lang').val() == 'no') {
            $('#gt').hide();
        }
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
                        $('#imagePreview').attr('src', 'uploads/' + data['fileName']);

                    }
                    else {
                        swal('Error!', data, 'error');
                        $('#imgMsg').html("Something went wrong can't upload image");

                    }
                }
            });
        }));


        $('#update').click(function () {
            $.ajax({
                type: 'POST',
                url: "{{url('/settings')}}",
                data: {
                    'title': $('#title').val(),
                    'subTitle': $('#subTitle').val(),
                    'logo': $('#image').val(),
                    'email': $('#email').val(),
                    'phone': $('#phone').val(),
                    'currency': $('#currency').val(),
                    'tax': $('#tax').val(),
                    'shipping': $('#shipping').val(),
                    'address': $('#address').val(),
                    'paymentMethod': $('#paymentMethod').val(),
                    'paypalClientId': $('#paypalClientId').val(),
                    'paypalClientSecret': $('#paypalClientSecret').val(),
                    'afterOrderMsg': $('#afterOrderMsg').val(),
                    'token': $('#token').val(),
                    'map': $('#map').val(),
                    'appId': $('#appId').val(),
                    'appSec': $('#appSec').val(),
                    'reg': 'off',
                    'lang': $('#lang').val(),
                    @if(\App\Http\Controllers\PackagesController::isMyPackage('woo'))
                    'wpUrl': $('#wpUrl').val(),
                    'wooConsumerKey': $('#wooConsumerKey').val(),
                    'wooConsumerSecret': $('#wooConsumerSecret').val(),
                    @endif
                    'fixedLang': $('#fixedLang').val(),
                    'fixedLangOp': $('#fixedLangOp').val()

                },
                success: function (data) {
                    if (data == 'success') {
                        swal('Success', 'Updated', 'success');
                        location.reload();
                    }
                    else {
                        swal("Error", data, 'error');
                    }

                }
            });
        });


    </script>
@endsection
