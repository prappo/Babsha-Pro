<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

class Boot extends Controller
{
    public static function up()
    {
        Run::fire(self::getStartButton());
    }

    public static function greeting()
    {
        $json = '"setting_type":"greeting",
  "greeting":{
    "text":"Welcome to our shop"    
  }';
        return $json;
    }

    public static function getStartButton()
    {
        $json = '{
  "setting_type":"call_to_actions",
  "thread_state":"new_thread",
  "call_to_actions":[
    {
      "payload":"USER_DEFINED_PAYLOAD"
    }
  ]
}';
        return $json;
    }

    public static function menu()
    {
        $json = '{
        "recipient":{
    "id":"1014447361971297"
  },
  "setting_type" : "call_to_actions",
  "thread_state" : "existing_thread",
  "call_to_actions":[
    {
      "type":"postback",
      "title":"Help",
      "payload":"DEVELOPER_DEFINED_PAYLOAD_FOR_HELP"
    },
    {
      "type":"postback",
      "title":"Start a New Order",
      "payload":"DEVELOPER_DEFINED_PAYLOAD_FOR_START_ORDER"
    },
    {
      "type":"web_url",
      "title":"Checkout",
      "url":"http://petersapparel.parseapp.com/checkout",
      "webview_height_ratio": "full",
      "messenger_extensions": true
    },
    {
      "type":"web_url",
      "title":"View Website",
      "url":"http://petersapparel.parseapp.com/"
    }
  ]
}';
        return $json;
    }

    public static function thread($jsonData,$pageId)
    {
        $url = 'https://graph.facebook.com/v2.6/me/thread_settings?access_token=' . Data::getToken($pageId);
        $ch = curl_init($url);

        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        curl_exec($ch);
    }


}
