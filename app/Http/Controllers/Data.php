<?php

namespace App\Http\Controllers;

use App\Customers;
use App\FacebookPages;
use App\Notifications;
use App\Settings;
use App\SiteSettings;
use App\Translate;
use Illuminate\Http\Request;

use App\Http\Requests;
use Stichoza\GoogleTranslate\TranslateClient;

class Data extends Controller
{

    /**
     * @return mixed
     * @Facebook page token
     */
    public static function getToken($pageId)
    {
        return FacebookPages::where('pageId',$pageId)->value('pageToken');
    }

    /**
     * @return mixed
     */
    public static function getLang()
    {
        return Settings::get('lang');
    }

    /**
     * @return mixed
     */
    public static function getUserLang($userFacebookId)
    {
        if (Customers::where('fbId', $userFacebookId)->value('lang') != null) {
            return Customers::where('fbId', $userFacebookId)->value('lang');
        } else {
            return 'en';
        }


    }



    /**
     * @return mixed
     */
    public static function getCurrency($pageId)
    {
        $userId = FacebookPages::where('pageId',$pageId)->value('userId');
        return Settings::where('userId',$userId)->value('currency');
    }

    /**
     * @return string
     */
    public static function getUnit($pageId)
    {
        if (self::getCurrency($pageId) == 'USD') {
            return "\$";
        } elseif (self::getCurrency($pageId) == 'EURO') {
            return "€";
        } elseif (self::getCurrency($pageId) == 'BDT') {
            return "৳";
        } elseif (self::getCurrency($pageId) == 'GBP') {
            return "£";
        } else {
            return "\$";
        }

    }

    /**
     * @return mixed
     */
    public static function getPaymentMethod($pageId)
    {
        $userId = FacebookPages::where('pageId',$pageId)->value('userId');
        return Settings::where('userId',$userId)->value('paymentMethod');
//        return Settings::get('paymentMethod');
    }

    public static function getPaypalAccount($pageId)
    {
        $userId = FacebookPages::where('pageId',$pageId)->value('userId');
        return Settings::where('userId',$userId)->value('paypalEmail');
//        return Settings::get('paypalEmail');
    }

    /**
     * @return mixed
     */
    public static function getTax($pageId)
    {
        $userId = FacebookPages::where('pageId',$pageId)->value('userId');


        if (Settings::where('useId',$userId)->value('tax') == "") {
            return "0";
        } else {
            return Settings::where('useId',$userId)->value('tax');
        }

    }

    /**
     * @return mixed
     */
    public static function getShippingCost($pageId)
    {
        $userId = FacebookPages::where('pageId',$pageId)->value('userId');
        if (Settings::where('userId',$userId)->value('shipping') == "") {
            return "0";
        } else {
            return Settings::where('userId',$userId)->value('shipping');
        }
    }

    /**
     * @return mixed
     */
    public static function getEmail($pageId)
    {
        $userId = FacebookPages::where('pageId',$pageId)->value('userId');
        return Settings::where('userId',$userId)->value('email');
    }

    /**
     * @return mixed
     */
    public static function getAfterOrderMsg($pageId)
    {
        $userId = FacebookPages::where('pageId',$pageId)->value('userId');
        return Settings::where('userId',$userId)->value('afterOrderMsg');
    }

    /**
     * @return string
     */
    public static function getLogo($pageId)
    {
        $userId = FacebookPages::where('pageId',$pageId)->value('userId');
        return url('/uploads') . "/" . Settings::where('userId',$userId)->value('logo');
    }

    /**
     * @return mixed
     */
    public static function getLogoData($pageId)
    {
        $userId = FacebookPages::where('pageId',$pageId)->value('userId');
        return Settings::where('userId',$userId)->value('logo');
    }

    /**
     * @return mixed
     */
    public static function getShopTitle($pageId)
    {
        $userId = FacebookPages::where('pageId',$pageId)->value('userId');
        return Settings::where('userId',$userId)->value('title');
    }

    /**
     * @return mixed
     */
    public static function getShopSubTitle($pageId)
    {
        $userId = FacebookPages::where('pageId',$pageId)->value('userId');
        return Settings::where('userId',$userId)->value('subTitle');
    }

    /**
     * @return mixed
     */
    public static function getShopAddress($pageId)
    {
        $userId = FacebookPages::where('pageId',$pageId)->value('userId');
        return Settings::where('userId',$userId)->value('address');
    }

    /**
     * @return mixed
     */
    public static function getPhone($pageId)
    {
        $userId = FacebookPages::where('pageId',$pageId)->value('userId');
        return Settings::where('userId',$userId)->value('phone');
    }

    /**
     * @return string
     */
    public static function getMap($pageId)
    {
        $userId = FacebookPages::where('pageId',$pageId)->value('userId');
        return "http://maps.googleapis.com/maps/api/staticmap?zoom=14&size=250x150&markers=icon:|" . Settings::where('userId',$userId)->value('map');
    }

    /**
     * @return mixed
     */
    public static function getMapData($pageId)
    {
        $userId = FacebookPages::where('pageId',$pageId)->value('userId');
        return Settings::where('userId',$userId)->value('map');
    }

    /**
     * @param $string
     * @return bool|string
     */
    public static function date($string)
    {
        $s = $string;
        $date = strtotime($s);
        return date('d/M/Y', $date);
    }

    /**
     * @param $txt1
     * @param $txt2
     * @return int
     */
    public static function similar($txt1, $txt2)
    {
        $per = 0;
        similar_text($txt1, $txt2, $per);
        return (int)$per;
    }

    public static function getReg()
    {
        return Settings::get('reg');
    }

    public static function translate($text, $lang)
    {
        if (self::getLang() == "yes") {
            $similar = 0;
            foreach (Translate::all() as $translate) {
                similar_text($translate->eng, $text, $similar);
                if ($similar >= 95) {
                    if ($translate->lang != "") {
                        return $translate->lang;
                    } else {
                        return $text;
                    }

                }

            }
            return $text;
        } else {
            return $text;
        }


    }

//    public static function convert($text)
//    {
//
//        $translate = new TranslateClient(null);
//
//        $translate->translate($text);
//        $lang = $translate->getLastDetectedSource();
//        return $lang;
//
//    }

    public static function notify($content)
    {
        try {
            $notify = new Notifications();
            $notify->content = $content;
            $notify->save();
        } catch (\Exception $e) {
        }
    }
}
