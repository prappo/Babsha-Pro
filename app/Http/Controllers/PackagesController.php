<?php

namespace App\Http\Controllers;

use App\UserPackages;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Auth;

class PackagesController extends Controller
{
    public static function isMyPackage($packageName)
    {
        if (UserPackages::where('userId', Auth::user()->id)->value($packageName) == "yes") {
            return true;
        } else {
            return false;
        }
    }
}
