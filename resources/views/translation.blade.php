@extends('layouts.app')
@section('title','Translation')
@section('content')
    <div class="container">
        <form action="{{url('/update/translate')}}" method="post">
            <table class="table">
                <caption>Translation</caption>
                <thead>
                <tr>

                    <th>English</th>
                    <th>Your Language</th>

                </tr>
                </thead>
                <tbody>
                <tr>
                    <td>Hi</td>
                    <td><input name="1" class="form-control" value="{{\App\Http\Controllers\Settings::getLang(1)}}"  type="text"></td>
                </tr>
                <tr>
                    <td>Welcome back</td>
                    <td><input name="2" class="form-control" value="{{\App\Http\Controllers\Settings::getLang(2)}}"  type="text"></td>
                </tr>
                <tr>
                    <td>Check out our menu . If you need more help please type help and send us</td>
                    <td><input name="3" class="form-control" value="{{\App\Http\Controllers\Settings::getLang(3)}}" type="text"></td>
                </tr>

                <tr>
                    <td>View Our Products</td>
                    <td><input name="4" class="form-control" value="{{\App\Http\Controllers\Settings::getLang(4)}}" type="text"></td>
                </tr>

                <tr>
                    <td>More Options</td>
                    <td><input name="5" class="form-control" value="{{\App\Http\Controllers\Settings::getLang(5)}}" type="text"></td>
                </tr>


                <tr>
                    <td>Contact us</td>
                    <td><input name="6" class="form-control" value="{{\App\Http\Controllers\Settings::getLang(6)}}" type="text"></td>
                </tr>
                <tr>
                    <td>Select a cagetory</td>
                    <td><input name="7" class="form-control" value="{{\App\Http\Controllers\Settings::getLang(7)}}" type="text"></td>
                </tr>
                <tr>
                    <td>Categories</td>
                    <td><input name="8" class="form-control" value="{{\App\Http\Controllers\Settings::getLang(8)}}" type="text"></td>
                </tr>
                <tr>
                    <td>Order Now</td>
                    <td><input name="9" class="form-control" value="{{\App\Http\Controllers\Settings::getLang(9)}}" type="text"></td>
                </tr>
                <tr>
                    <td>Product Details</td>
                    <td><input name="10" class="form-control" value="{{\App\Http\Controllers\Settings::getLang(10)}}" type="text"></td>
                </tr>
                <tr>
                    <td>Back</td>
                    <td><input name="11" class="form-control" value="{{\App\Http\Controllers\Settings::getLang(11)}}" type="text"></td>
                </tr>
                <tr>
                    <td>My Account</td>
                    <td><input name="12" class="form-control" value="{{\App\Http\Controllers\Settings::getLang(12)}}" type="text"></td>
                </tr>
                <tr>
                    <td>Featured Items</td>
                    <td><input name="13" class="form-control" value="{{\App\Http\Controllers\Settings::getLang(13)}}" type="text"></td>
                </tr>
                <tr>
                    <td>Contact us</td>
                    <td><input name="14" class="form-control" value="{{\App\Http\Controllers\Settings::getLang(14)}}" type="text"></td>
                </tr>
                <tr>
                    <td>Email</td>
                    <td><input name="15" class="form-control" value="{{\App\Http\Controllers\Settings::getLang(15)}}" type="text"></td>
                </tr>
                <tr>
                    <td>Call us</td>
                    <td><input name="16" class="form-control" value="{{\App\Http\Controllers\Settings::getLang(16)}}" type="text"></td>
                </tr>
                <tr>
                    <td>Thanks for your order.We will contact your soon</td>
                    <td><input name="17" class="form-control" value="{{\App\Http\Controllers\Settings::getLang(17)}}" type="text"></td>
                </tr>
                <tr>
                    <td>Update Your shipping address</td>
                    <td><input name="18" class="form-control" value="{{\App\Http\Controllers\Settings::getLang(18)}}" type="text"></td>
                </tr>
                <tr>
                    <td>Do you want to place your order now ?</td>
                    <td><input name="19" class="form-control" value="{{\App\Http\Controllers\Settings::getLang(19)}}" type="text"></td>
                </tr>
                <tr>
                    <td>Yes</td>
                    <td><input name="20" class="form-control" value="{{\App\Http\Controllers\Settings::getLang(20)}}" type="text"></td>
                </tr>
                <tr>
                    <td>No Thanks</td>
                    <td><input name="21" class="form-control" value="{{\App\Http\Controllers\Settings::getLang(21)}}" type="text"></td>
                </tr>

                </tbody>
            </table>
            <div class="row">
                <div class="col-md-11"></div>
                <div class="col-md-1">
                    <button class="btn btn-success" type="submit"><i class="fa fa-save"></i> Save</button>
                </div>


            </div>
            <br><br>
        </form>
    </div>
@endsection