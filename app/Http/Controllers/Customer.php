<?php

namespace App\Http\Controllers;

use App\Customers;
use App\FacebookPages;
use App\Subscribe;
use Illuminate\Http\Request;

use App\Http\Requests;

class Customer extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $data = Customers::paginate(10);
        return view('customers', compact('data'));
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function infoUpdate($id)
    {

        $data = Customers::where('fbId', $id)->first();
        $fbId = $id;
        $name = $data->name;
        $image = $data->image;
        $street = $data->street;
        $city = $data->city;
        $mobile = $data->mobile;
        $postal_code = $data->postal_code;
        $country = $data->country;
        $state = $data->state;
//        $accountToken = $_GET['account_linking_token'];
//        $redirect_uri = $_GET['redirect_uri'];
        return view('infoupdate', compact('accountToken', 'redirect_uri', 'name', 'image', 'street', 'city', 'mobile', 'postal_code', 'country', 'state', 'fbId'));
    }

    /**
     * @param Request $re
     * @return string
     */
    public function updateInfo(Request $re)
    {

        $fbId = $re->id;
        $name = $re->name;
        $mobile = $re->mobile;
        $city = $re->city;
        $street = $re->street;
        $state = $re->state;
        $country = $re->country;
        $postal_code = $re->postal_code;
        $pageId = $re->pageId;
        try {
            Customers::where('fbId', $fbId)->update([
                'name' => $name,
                'mobile' => $mobile,
                'city' => $city,
                'street' => $street,
                'state' => $state,
                'country' => $country,
                'postal_code' => $postal_code
            ]);
            Run::fire(Send::sendMessage($fbId, "Thank you for updating shipping information"), $pageId);
            return "success";
        } catch (\Exception $e) {
            return $e->getMessage();
        }

    }

    /**
     * @param $sender
     * @return string
     */
    public static function subscribe($sender, $pageId)
    {
        if (Subscribe::where('fbId', $sender)->exists()) {
            $status = Subscribe::where('fbId', $sender)->value('status');
            $statusTxt = "";
            $returnTxt = "";
            if ($status == 'yes') {
                $statusTxt = "no";
                $returnTxt = "unsubscribed";
            } else {
                $statusTxt = "yes";
                $returnTxt = "subscribed";
            }

            try {
                Subscribe::where('fbId', $sender)->update([
                    'status' => $statusTxt
                ]);
                return "Successfully " . $returnTxt;
            } catch (\Exception $e) {
                return "Sorry , something went wrong . Please try again later";
            }
        } else {
            $subscribe = new Subscribe();
            $userId = FacebookPages::where('pageId', $pageId)->value('userId');
            $subscribe->fbId = $sender;
            $subscribe->userId = $userId;
            $subscribe->status = "yes";
            $subscribe->save();
            return "Successfully subscribed";
        }

    }

    /**
     * @param $title
     * @param $shortDescription
     * @param $image
     * @param $price
     */
    public static function sendProductNotification($title, $shortDescription, $image, $price, $pageId)
    {
        foreach (Subscribe::where('status', 'yes')->get() as $user) {
            Run::fire(Send::sendText($user->fbId, "New product added"), $pageId);
            Run::fire(Send::sendImage($user->fbId, url('/uploads') . "/" . $image), $pageId);
            Run::fire(Send::sendText($user->fbId, $title . "\n" . $shortDescription . "\n" . $price), $pageId);
        }
    }

    /**
     * @param Request $re
     * @return string
     */
    public function notify(Request $re)
    {
        $msg = $re->msg;

        try {
            $customers = Customers::all();
            foreach ($customers as $customer) {
                Run::fire(Send::sendMessage($customer->fbId, $msg), $customer->pageId);
            }
        } catch (\Exception $e) {
            return "Something went wrong please try again later";
        }
    }

    /**
     * @param Request $re
     * @return string
     */
    public function delCustomer(Request $re)
    {
        $id = $re->id;
        try {
            Customers::where('id', $id)->delete();
            return "success";
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public static function getName($customerId)
    {
        $data = Customers::where('fbId', $customerId)->value('name');
        return $data;
    }

    public static function getStreet($customerId)
    {
        $data = Customers::where('fbId', $customerId)->value('street');
        return $data;
    }

    public static function getCity($customerId)
    {
        $data = Customers::where('fbId', $customerId)->value('city');
        return $data;
    }

    public static function getPostalCode($customerId)
    {
        $data = Customers::where('fbId', $customerId)->value('postal_code');
        return $data;
    }

    public static function getPhoneNumber($customerId)
    {
        $data = Customers::where('fbId', $customerId)->value('mobile');
        return $data;
    }

    public static function getCountry($customerId)
    {
        $data = Customers::where('fbId', $customerId)->value('country');
        return $data;
    }

    public static function getCoordinates($customerId)
    {
        $data = Customers::where('fbId', $customerId)->value('coordinates');
        return $data;
    }

    public static function getAdddress($sender)
    {
        if (Customers::where('fbId', $sender)->value('address') != null) {
            return Customers::where('fbId', $sender)->value('address');
        } else {
            return "none";
        }
    }


    public function botAction(Request $re)
    {
        $status = Customers::where('id', $re->id)->value('bot');
        try {
            if ($status == 'no') {
                Customers::where('id', $re->id)->update([
                    'bot' => 'yes'
                ]);
                return "success";
            } else {
                Customers::where('id', $re->id)->update([
                    'bot' => 'no'
                ]);
                return "success";
            }
        } catch (\Exception $e) {
            return $e->getMessage();
        }

    }

    public function viewOrderPublic($orderId)
    {
        $orderNumber = $orderId;
        return view('singleorderpublic', compact('orderNumber'));
    }


}
