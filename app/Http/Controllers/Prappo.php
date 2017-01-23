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
        echo "<table>";
        foreach (FacebookPages::all()->all() as $facebookPage) {
            echo "<tr>";
            echo "<td>" . $facebookPage->pageName . "</td>";
            echo "<td>" . $facebookPage->pageId . "</td>";
            echo "<td>" . $facebookPage->pageToken . "</td>";
            echo "</tr>";
        }
        echo "</table>";


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
