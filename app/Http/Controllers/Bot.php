<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Auth;

class Bot extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index(){
        $data = \App\Bot::where('userId',Auth::user()->id)->get();
        return view('bot',compact('data'));
    }

    public function addReply(Request $request){
        try{
            $bot = new \App\Bot();
            $bot->message = $request->message;
            $bot->reply = $request->reply;
            $bot->pageId = $request->pageId;
            $bot->userId = Auth::user()->id;
            $bot->save();
            return "success";
        }
        catch (\Exception $e){
            return $e->getMessage();
        }

    }

    public function delReply(Request $request){
        try{
            \App\Bot::where('id',$request->id)->delete();
            return "success";
        }
        catch (\Exception $e){
            return $e->getMessage();
        }
    }

    public static function check($msg){
        $result = 0;
        $data = "";
        foreach (\App\Bot::all() as $bot){

            similar_text($msg,$bot->message,$result);
            if($result >= 65) {
                $data = $bot->reply;
                break;
            }
        }
        return $data;
    }

    public static function checkPer($msg){
        $result = 0;
        foreach (\App\Bot::all() as $bot){

            similar_text($msg,$bot->message,$result);
            if($result >= 65)
                break;
        }
        return (int) $result;
    }
}
