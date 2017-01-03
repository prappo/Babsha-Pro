<?php

namespace App\Http\Controllers;

use App\Translate;
use Facebook\Exceptions\FacebookResponseException;
use Facebook\Exceptions\FacebookSDKException;
use Facebook\Facebook;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Settings extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {

        if (Settings::get('appId') == "" && Settings::get('appSec') == "") {
            $loginUrl = "";
        } else {
            $fb = new Facebook([
                'app_id' => Settings::get('appId'),
                'app_secret' => Settings::get('appSec'),
                'default_graph_version' => 'v2.6',
            ]);

            try {
                $permissions = ['pages_messaging', 'publish_actions', 'manage_pages', 'publish_pages'];
                $helper = $fb->getRedirectLoginHelper();
                $loginUrl = $helper->getLoginUrl(url('') . '/fbconnect', $permissions);
            } catch (\Exception $e) {
                $loginUrl = url('/');
            }
        }

        return view('settings', compact('loginUrl'));
    }

    public function translation(){
        if(Auth::user()->type != 'admin'){
            return view('home');
        }
        return view('translation');
    }

    public function updateTranslation(Request $request){
        $inputs = $request->input();
        $count =1;
        $result = "";
       try{
           foreach ($inputs as $input){
               Translate::where('langId',$count)->update(['lang'=>$inputs[$count]]);
               $count++;
           }
           $result = "<div class=\"alert alert-success\" role=\"alert\">Updated successfully</div>";

           return view('result',compact('result'));
       }
       catch (\Exception $e){
           $result ="<div class=\"alert alert-danger\" role=\"alert\">". $e->getMessage()."</div>";
           return view('result',compact($result));
       }


    }

    public function fbConnect()
    {
        session_start();

        $fb = new Facebook([
            'app_id' => Settings::get('appId'),
            'app_secret' => Settings::get('appSec'),
            'default_graph_version' => 'v2.6',
        ]);

        $helper = $fb->getRedirectLoginHelper();
        $_SESSION['FBRLH_state'] = $_GET['state'];

        try {
            $accessToken = $helper->getAccessToken();
            $_SESSION['token'] = $accessToken;

        } catch (FacebookResponseException $e) {
            // When Graph returns an error
            return '[a] Graph returned an error: ' . $e->getMessage();

        } catch (FacebookSDKException $e) {
            // When validation fails or other local issues
            return '[a] Facebook SDK returned an error: ' . $e->getMessage();

        }

        try {
            $response = $fb->get('/me', $_SESSION['token']);
        } catch (FacebookResponseException $e) {
            return '[b] Graph returned an error: ' . $e->getMessage();
            exit;
        } catch (FacebookSDKException $e) {
            return '[b] Facebook SDK returned an error: ' . $e->getMessage();
            exit;
        }


        return redirect('settings');


    }

    /**
     * @param $key
     * @return mixed
     */
    public static function get($key)
    {
        return \App\Settings::where('key', $key)->value('value');
    }

    public static function getLang($key)
    {
        return Translate::where('langId', $key)->value('lang');
    }

    /**
     * @param Request $re
     * @return string
     */
    public function update(Request $re)
    {
        try {
            DB::table('settings')->where('key', 'token')->update(['value' => $re->token]);
            DB::table('settings')->where('key', 'email')->update(['value' => $re->email]);
            DB::table('settings')->where('key', 'currency')->update(['value' => $re->currency]);
            DB::table('settings')->where('key', 'paymentMethod')->update(['value' => $re->paymentMethod]);
            DB::table('settings')->where('key', 'shipping')->update(['value' => $re->shipping]);
            DB::table('settings')->where('key', 'afterOrderMsg')->update(['value' => $re->afterOrderMsg]);
            DB::table('settings')->where('key', 'tax')->update(['value' => $re->tax]);
            DB::table('settings')->where('key', 'logo')->update(['value' => $re->logo]);
            DB::table('settings')->where('key', 'title')->update(['value' => $re->title]);
            DB::table('settings')->where('key', 'subTitle')->update(['value' => $re->subTitle]);
            DB::table('settings')->where('key', 'phone')->update(['value' => $re->phone]);
            DB::table('settings')->where('key', 'address')->update(['value' => $re->address]);
            DB::table('settings')->where('key', 'map')->update(['value' => $re->map]);
            DB::table('settings')->where('key', 'appId')->update(['value' => $re->appId]);
            DB::table('settings')->where('key', 'appSec')->update(['value' => $re->appSec]);
            DB::table('settings')->where('key', 'reg')->update(['value' => $re->reg]);
//            DB::table('settings')->where('key', 'lang')->update(['value' => $re->lang]);
            DB::table('settings')->where('key', 'paypalClientId')->update(['value' => $re->paypalClientId]);
            DB::table('settings')->where('key', 'paypalClientSecret')->update(['value' => $re->paypalClientSecret]);
            DB::table('settings')->where('key', 'wpUrl')->update(['value' => $re->wpUrl]);
            DB::table('settings')->where('key', 'wooConsumerKey')->update(['value' => $re->wooConsumerKey]);
            DB::table('settings')->where('key', 'wooConsumerSecret')->update(['value' => $re->wooConsumerSecret]);


            return "success";

        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function bot(){
        return view('botsettings');
    }

    public function botUpdate(Request $request){

        if($request->message == ""){
            $message = "Welcome to my Shop";
        }
        else{
            $message = $request->message;
        }
        $jsonGreeting = '{
  "setting_type":"greeting",
  "greeting":{
    "text":"'.$message.'"
  }
}';
        $jsonMenu = '{
  "setting_type" : "call_to_actions",
  "thread_state" : "existing_thread",
  "call_to_actions":[
  {
      "type":"postback",
      "title":"Products",
      "payload":"view_products"
    },
    {
      "type":"postback",
      "title":"My Account",
      "payload":"me"
    },
    
    {
      "type":"postback",
      "title":"My Cart",
      "payload":"my_cart"
    },
    {
      "type":"postback",
      "title":"My Orders",
      "payload":"user_orders"
    },
    {
      "type":"postback",
      "title":"Help",
      "payload":"help"
    }
  ]
}';
        $jsonGetStartBtn = '{
  "setting_type":"call_to_actions",
  "thread_state":"new_thread",
  "call_to_actions":[
    {
      "payload":"menu"
    }
  ]
}';

        $jsonWhitelist = '{
  "setting_type" : "domain_whitelisting",
  "whitelisted_domains" : ["'.secure_url('/').'"],
  "domain_action_type": "add"
}';

        Boot::thread($jsonGetStartBtn);
        Boot::thread($jsonGreeting);
        Boot::thread($jsonMenu);
        Boot::thread($jsonWhitelist);


    }


}
