<?php

namespace App\Http\Controllers;


use App\FacebookPages;
use App\Orders;
use App\Products;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use Stichoza\GoogleTranslate\TranslateClient;

class Prappo extends Controller
{
    public function index()
    {
        $json = '{
  "recipient":{
    "id":"1721733611174044"
  },
  "message":{
    "text":"Pick a color:",
    "quick_replies":[
      {
        "content_type":"text",
        "title":"Red",
        "payload":"DEVELOPER_DEFINED_PAYLOAD_FOR_PICKING_RED"
      },
      {
        "content_type":"text",
        "title":"Green",
        "payload":"DEVELOPER_DEFINED_PAYLOAD_FOR_PICKING_GREEN"
      }
    ]
  }
}';
//        Run::fire($json,"1219524874793021");
        try {
            Run::fire(Send::sendText('1047921465314649', 'Hi'), '1575304809176976');
        } catch (\Exception $exception) {
            return $exception->getMessage();
        }


    }

    public static function check($txt)
    {
        $translate = new TranslateClient(null);
        $translate->translate($txt);
        $lang = $translate->getLastDetectedSource();
        return $lang;
    }

    public function woo()
    {


    }


    public function subscribe($pageId)
    {
        $url = 'https://graph.facebook.com/v2.8/me/subscribed_apps?access_token=' . Data::getToken($pageId);
        $ch = curl_init($url);

        curl_setopt($ch, CURLOPT_POST, 1);

        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        curl_exec($ch);
    }

    public function getProduct($id)
    {
        return Products::where('id', $id)->get();
    }


    public function paymentStatus()
    {
        return "ok";
    }

    public function paymentCancel()
    {
        return "Canceled";
    }


}
