<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

class Policy extends Controller
{
    //
    /**
     * Bot Policy sample text to help you to approve your app on Facebook
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function bot(){
        return view('botpolicy');
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function botLegal(){
        return view('botlegal');
    }
}
