<?php

namespace App\Http\Controllers;

use App\Orders;
use Illuminate\Http\Request;

use App\Http\Requests;

class Checkout extends Controller
{
    public function index($user){
        $orders = Orders::where('sender',$user)
            ->where('status','pending')->get();
        foreach($orders as $order){

        }
    }
}
