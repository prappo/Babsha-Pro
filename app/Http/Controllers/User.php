<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Auth;

class User extends Controller
{
    public function userList(){
        if(Auth::user()->type != "admin"){
            return view('home');
        }
        $data = \App\User::all();
        return view('userlist',compact('data'));
    }

    public function userAdd(Request $request){
        if(Auth::user()->type != "admin"){
            return view('home');
        }

        if(\App\User::where('email',$request->email)->exists()){
            return "Email already exists";
        }

        try{
            $user = new \App\User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = bcrypt($request->pass);
            $user->type = "user";
            $user->save();
            return "success";
        }
        catch (\Exception $e){
            return $e->getMessage();
        }
    }

    public function userEdit($id){
        if(Auth::user()->type != "admin"){
            return view('home');
        }
        if($id=='add'){
            return view('adduser');
        }
        $name = \App\User::where('id',$id)->value('name');
        $email = \App\User::where('id',$id)->value('email');

        return view('edituser',compact('name','email','id'));
    }

    public function edit(Request $request){
        if($request->password == ""){
            try{
                \App\User::where('id',$request->id)->update([
                    'name'=>$request->name,
                    'email'=>$request->email
                ]);
                return "success";
            }
            catch (\Exception $e){
                return $e->getMessage();
            }
        }
        else{
            try{
                \App\User::where('id',$request->id)->update([
                    'name'=>$request->name,
                    'email'=>$request->email,
                    'password'=> bcrypt($request->password)
                ]);
                return "success";
            }
            catch (\Exception $e){
                return $e->getMessage();
            }
        }

    }

    public function del(Request $request){
        $getType = \App\User::where('id',$request->id)->value('type');
        if($getType == 'admin'){
            return "You can't delete admin";
        }
        try{
            \App\User::where('id',$request->id)->delete();
            return "success";
        }
        catch (\Exception $e){
            return $e->getMessage();
        }
    }
}
