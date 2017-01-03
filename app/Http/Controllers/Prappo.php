<?php

namespace App\Http\Controllers;


use App\Orders;
use App\Products;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use Stichoza\GoogleTranslate\TranslateClient;

class Prappo extends Controller
{
    public function index()
    {
//        $woo = new \Automattic\WooCommerce\Client(
//            Settings::get('wpUrl'),
//            Settings::get('wooConsumerKey'),
//            Settings::get('wooConsumerSecret'),
//            [
//                'wp_api' => true,
//                'version' => 'wc/v1',
//            ]
//        );
//
//        try{
//            $product = $woo->get('products/49');
//            print_r($product);
//
//        }catch (\Exception $exception){
//            return $exception->getMessage();
//        }
//        $products = $woo->get('products');
//        foreach($products as $product){
//            echo "<img height='50' width='50' src='".$product['images'][0]['src']."'>";
//            echo $product['name']."<br>";
//            echo $product['price']."<br>";
//            echo $product['short_description']."<br>";
//            echo $product['description']."<br>";
//            echo $product['status']."<br>";
//            echo isset($product['categories'][0]['name']) ? $product['categories'][0]['name'] : "No category"."<br>";
//
//        }
        $jsonData1 = '{
  "recipient":{
    "id":"1014447361971297"
  },
  "message":{
    "attachment":{
      "type":"template",
      "payload":{
        "template_type":"generic",
        "elements":[
          {
            "title":"Classic White T-Shirt",
            "subtitle":"Soft white cotton t-shirt is back in style",
            "buttons":[
              {
                "type":"web_url",
                "url":"https://71a06e6d.ngrok.io/info/update/1014447361971297",
                "title":"View Item",
                "messenger_extensions": true, 
                "webview_height_ratio":"full"
              }
            ]
          }
        ]
      }
    }
  }
}';
//        $url = 'https://graph.facebook.com/v2.6/me/messages?access_token=' . Data::getToken();
//        $ch = curl_init($url);
//
//        curl_setopt($ch, CURLOPT_POST, 1);
//        curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
//        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
//        curl_exec($ch);
//Run::fire($jsonData);
        $jsonData = '{
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
        Boot::thread($jsonData);


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
