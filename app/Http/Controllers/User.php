<?php

namespace App\Http\Controllers;

use App\Settings;
use App\UserPackages;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Auth;

class User extends Controller
{
    public function userList()
    {
        if (Auth::user()->type != "admin") {
            return view('home');
        }
        $data = \App\User::all();
        return view('userlist', compact('data'));
    }

    public function userAdd(Request $request)
    {
        if (Auth::user()->type != "admin") {
            return view('home');
        }

        if (\App\User::where('email', $request->email)->exists()) {
            return "Email already exists";
        }

        try {
            $user = new \App\User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = bcrypt($request->pass);
            $user->type = "user";
            $user->save();

            //create settings

            //get user ID
            $userId = \App\User::where('email', $request->email)->value('id');
            // create new settings for user
            $settings = new Settings();
            $settings->userId = $userId;
            $settings->save();

            // create package for user
            $package = new UserPackages();
            $package->userId = $userId;
            $package->woo = $request->woo;
            $package->shopify = $request->shopify;
            $package->magento = $request->magento;
            $package->save();


            return "success";
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function userEdit($id)
    {
        if (Auth::user()->type != "admin") {
            return view('home');
        }
        if ($id == 'add') {
            return view('adduser');
        }
        $name = \App\User::where('id', $id)->value('name');
        $email = \App\User::where('id', $id)->value('email');

        return view('edituser', compact('name', 'email', 'id'));
    }

    public function edit(Request $request)
    {
        if ($request->password == "") {
            try {
                \App\User::where('id', $request->id)->update([
                    'name' => $request->name,
                    'email' => $request->email
                ]);

                UserPackages::where('userId', $request->id)->update([
                    'woo' => $request->woo,
                    'shopify' => $request->shopify,
                    'magento' => $request->magento
                ]);
                return "success";
            } catch (\Exception $e) {
                return $e->getMessage();
            }
        } else {
            try {
                \App\User::where('id', $request->id)->update([
                    'name' => $request->name,
                    'email' => $request->email,
                    'password' => bcrypt($request->password)
                ]);
                UserPackages::where('userId', $request->id)->update([
                    'woo' => $request->woo,
                    'shopify' => $request->shopify,
                    'magento' => $request->magento
                ]);
                return "success";
            } catch (\Exception $e) {
                return $e->getMessage();
            }
        }

    }

    public function del(Request $request)
    {
        $getType = \App\User::where('id', $request->id)->value('type');
        if ($getType == 'admin') {
            return "You can't delete admin";
        }
        try {
            \App\User::where('id', $request->id)->delete();
            return "success";
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
}
