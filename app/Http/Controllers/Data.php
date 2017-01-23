<?php

namespace App\Http\Controllers;

use App\Customers;
use App\FacebookPages;
use App\Notifications;
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
     * @Facebook App ID
     */
    public static function getAppId()
    {
        return SiteSettings::where('key','appId')->value('value');
    }

    /**
     * @return mixed
     * @Facebook App Secrete
     */
    public static function getAppSec()
    {
        return SiteSettings::where('key','appSec')->value('value');
    }

    /**
     * @return mixed
     */
    public static function getCurrency()
    {
        return Settings::get('currency');
    }

    /**
     * @return string
     */
    public static function getUnit()
    {
        if (self::getCurrency() == 'USD') {
            return "\$";
        } elseif (self::getCurrency() == 'EURO') {
            return "€";
        } elseif (self::getCurrency() == 'BDT') {
            return "৳";
        } elseif (self::getCurrency() == 'GBP') {
            return "£";
        } else {
            return "\$";
        }

    }

    /**
     * @return mixed
     */
    public static function getPaymentMethod()
    {
        return Settings::get('paymentMethod');
    }

    public static function getPaypalAccount()
    {
        return Settings::get('paypalEmail');
    }

    /**
     * @return mixed
     */
    public static function getTax()
    {
        if (Settings::get('tax') == "") {
            return "0";
        } else {
            return Settings::get('tax');
        }

    }

    /**
     * @return mixed
     */
    public static function getShippingCost()
    {
        if (Settings::get('shipping') == "") {
            return "0";
        } else {
            return Settings::get('shipping');
        }
    }

    /**
     * @return mixed
     */
    public static function getEmail()
    {
        return Settings::get('email');
    }

    /**
     * @return mixed
     */
    public static function getAfterOrderMsg()
    {
        return Settings::get('afterOrderMsg');
    }

    /**
     * @return string
     */
    public static function getLogo()
    {
        return url('/uploads') . "/" . Settings::get('logo');
    }

    /**
     * @return mixed
     */
    public static function getLogoData()
    {
        return Settings::get('logo');
    }

    /**
     * @return mixed
     */
    public static function getShopTitle()
    {
        return Settings::get('title');
    }

    /**
     * @return mixed
     */
    public static function getShopSubTitle()
    {
        return Settings::get('subTitle');
    }

    /**
     * @return mixed
     */
    public static function getShopAddress()
    {
        return Settings::get('address');
    }

    /**
     * @return mixed
     */
    public static function getPhone()
    {
        return Settings::get('phone');
    }

    /**
     * @return string
     */
    public static function getMap()
    {
        return "http://maps.googleapis.com/maps/api/staticmap?zoom=14&size=250x150&markers=icon:|" . Settings::get('map');
    }

    /**
     * @return mixed
     */
    public static function getMapData()
    {
        return Settings::get('map');
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

    public static function convert($text)
    {

        $translate = new TranslateClient(null);

        $translate->translate($text);
        $lang = $translate->getLastDetectedSource();
        return $lang;

    }

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
