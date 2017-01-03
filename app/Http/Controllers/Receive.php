<?php

namespace App\Http\Controllers;

use App\Products;
use Illuminate\Http\Request;

use App\Http\Requests;

class Receive extends Controller
{

    /**
     * @param $userId
     * @param $token
     * @return mixed
     */
    public static function getProfile($userId, $token)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => 'https://graph.facebook.com/v2.6/' . $userId . '?access_token=' . $token,
            CURLOPT_USERAGENT => 'Codular Sample cURL Request'
        ));

        $resp = curl_exec($curl);

        curl_close($curl);
        return json_decode($resp);
    }

    /**
     * @param $msg
     */
    public static function check($msg)
    {
        $checkMsg = strtolower($msg);

    }

    /**
     * @param $productId
     * @param $field
     * @return mixed
     */
    public static function getProduct($productId, $field)
    {
        return Products::where('id',$productId)->value($field);

    }


}
