<?php

namespace App\Http\Controllers;

use App\Notifications;
use Illuminate\Http\Request;

use App\Http\Requests;

class Notification extends Controller
{
    public function index(){
        $data = Notifications::all();
        return view('notifications',compact('data'));
    }

    public function delete(){
        try{
            Notifications::truncate();
            return "success";
        }
        catch (\Exception $e){
            return $e->getMessage();
        }
    }
}
