<?php

namespace App\Http\Controllers;

use App\Cart;
use App\Customers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

class Send extends Controller
{

    /**
     * @param $id
     * @return string
     */
    public static function convertId($id)
    {
        if (is_int($id)) {
            return (string)$id;
        } else {
            return $id;
        }
    }

    /**
     * @param $userId
     * @param $message
     * @return string
     */
    public static function sendText($userId, $message)
    {

        $data = [
            "recipient" => [
                "id" => self::convertId($userId)
            ],
            "message" => [
                "text" => Data::translate($message, Data::getUserLang($userId))
            ]
        ];
        return json_encode($data);
    }


    public static function sendMessage($userId, $message)
    {

        $data = [
            "recipient" => [
                "id" => self::convertId($userId)
            ],
            "message" => [
                "text" => $message
            ]
        ];
        return json_encode($data);
    }

    /**
     * @param $userId
     * @param $imageUrl
     * @return string
     */
    public static function sendImage($userId, $imageUrl)
    {

        $data = '{
  "recipient":{
    "id":"' . $userId . '"
  },
  "message":{
    "attachment":{
      "type":"image",
      "payload":{
        "url":"' . $imageUrl . '"
      }
    }
  }
}';
        return $data;

    }

    /**
     * @param $userId
     * @param $fileLink
     * @return string
     */
    public static function sendFile($userId, $fileLink)
    {

        $data = [
            "recipient" => [
                "id" => self::convertId($userId)
            ],
            "message" => [
                "attachment" => [
                    "type" => "file",
                    "payload" => [
                        "url" => $fileLink
                    ]
                ]

            ]
        ];
        return json_encode($data);
    }

    public static function sendAudio($userId, $fileLink)
    {

        $data = [
            "recipient" => [
                "id" => self::convertId($userId)
            ],
            "message" => [
                "attachment" => [
                    "type" => "audio",
                    "payload" => [
                        "url" => $fileLink
                    ]
                ]

            ]
        ];
        return json_encode($data);
    }

    public static function sendVideo($userId, $fileLink)
    {

        $data = [
            "recipient" => [
                "id" => self::convertId($userId)
            ],
            "message" => [
                "attachment" => [
                    "type" => "video",
                    "payload" => [
                        "url" => $fileLink
                    ]
                ]

            ]
        ];
        return json_encode($data);
    }

    /**
     * @param $userId
     * @param $title
     * @param array $buttons
     * @return string
     */
    public static function menu($userId, $title, $buttons = [])
    {

        $data = [
            "recipient" => [
                "id" => self::convertId($userId)
            ],
            "message" => [
                "attachment" => [
                    "type" => "template",
                    "payload" => [
                        "template_type" => "button",
                        "text" => $title,
                        "buttons" => $buttons
                    ]
                ]
            ]
        ];

        return json_encode($data);
    }

    /**
     * @param $type
     * @param $title
     * @param string $url
     * @param $payload
     * @return array
     */
    public static function makeMenu($type, $title, $url = "", $payload)
    {
        if ($type == "web_url") {
            $data = [
                "type" => "web_url",
                "url" => $url,
                "title" => $title,
            ];

            return $data;

        } elseif ($type == "postback") {
            $data = [
                "type" => "postback",
                "title" => $title,
                "payload" => $payload
            ];
            return $data;
        } elseif ($type == "phone_number") {
            $data = [
                "type" => "phone_number",
                "title" => $title,
                "payload" => $payload
            ];
            return $data;
        }
    }

    /**
     * @param array $btnArray
     * @return string
     */
    public static function btn($btnArray = [])
    {

        $arr = [];
        foreach ($btnArray as $data) {
            array_push($arr, $data);
        }

        return json_encode($arr);


    }

    /**
     * @param $title
     * @param $payload
     * @return array
     */
    public static function quickBtn($title, $payload)
    {

        $data = [
            "content_type" => "text",
            "title" => $title,
            "payload" => $payload
        ];
        return $data;

    }

    /**
     * @param $userId
     * @param $title
     * @param array $quickBtn
     * @return string
     */
    public static function button($userId, $title, $quickBtn = [])
    {
        $data = [
            "recipient" => [
                "id" => self::convertId($userId)
            ],
            "message" => [
                "text" => $title,
                "quick_replies" => $quickBtn
            ]
        ];

        return json_encode($data);
    }

    /**
     * @param $userId
     * @param array $elements
     * @return string
     */
    public static function item($userId, $elements = [])
    {
        $data = [
            "recipient" => [
                "id" => $userId
            ],
            "message" => [
                "attachment" => [
                    "type" => "template",
                    "payload" => [
                        "template_type" => "generic",
                        "elements" => $elements
                    ]
                ]
            ]
        ];
        return json_encode($data);
    }

    /**
     * @param $title
     * @param $subtitle
     * @param $imageUrl
     * @param array $buttons
     * @return array
     */
    public static function elements($title, $subtitle, $imageUrl, $buttons = [])
    {
        $data = [
            "title" => $title,
            "image_url" => $imageUrl,
            "subtitle" => $subtitle,
            "buttons" => $buttons
        ];

        return $data;

    }

    /**
     * @param Request $re
     * @return \Illuminate\Http\JsonResponse
     */
    public function iup(Request $re)
    {
        $file = $re->file('file');
        $fileName = date('YmdHis');
        $fileType = $file->getClientMimeType();
        if ($fileType == 'image/jpeg' || $fileType == 'image/png') {
            try {
                Input::file('file')->move(public_path() . '/uploads/', $fileName . "." . $file->getClientOriginalExtension());
                return response()->json(["status" => "success", "fileName" => $fileName . "." . $file->getClientOriginalExtension()]);
            } catch (\Exception $e) {
                echo "error";
            }
        } else {
            echo "invalid File";
        }
    }


    /**
     * @return string
     */
    public static function helpText()
    {
        return "Hi there . I can tell you about products . Tell me things like the following:\n
  • Menu
  • Products
  • Featured products
  • Contact
  • Email
  • My account
  • Cart
  • Checkout
  • Order list
  • More options
  ";
    }

    /**
     * @param $userId
     * @return string
     */
    public static function updateInfo($userId)
    {


        $json = '{
  "recipient":{
    "id":"' . $userId . '"
  },
  "message":{
    "attachment":{
      "type":"template",
      "payload":{
        "template_type":"generic",
        "elements":[
          {
            "title":"Update Shipping information",
            "buttons":[
              {
                "type":"web_url",
                "url":"' . secure_url('/info/update') . "/" . $userId . '",
                "title":"Update Now",
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


        return $json;
    }

    /**
     * @param $userId
     * @param $title
     * @param $subTitle
     * @param $image
     * @param $orderId
     * @return string
     */
    public static function showSingleOrder($userId, $title, $subTitle, $image, $orderId)
    {
        $data = [
            "recipient" => [
                "id" => $userId
            ],
            "message" => [
                "attachment" => [
                    "type" => "template",
                    "payload" => [
                        "template_type" => "generic",
                        "elements" => [
                            [
                                "title" => $title,
                                "image_url" => $image,
                                "subtitle" => Data::translate($subTitle, Data::getUserLang($userId)),
                                "buttons" => [

                                    [
                                        "type" => "postback",
                                        "title" => Data::translate("Remove", Data::getUserLang($userId)),
                                        "payload" => "cancel_" . $orderId
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        ];

        return json_encode($data);
    }

    public static function showSingleOrderList($userId, $title, $orderId)
    {
        $data = [
            "recipient" => [
                "id" => $userId
            ],
            "message" => [
                "attachment" => [
                    "type" => "template",
                    "payload" => [
                        "template_type" => "generic",
                        "elements" => [
                            [
                                "title" => $title,

                                "buttons" => [

                                    [
                                        "type" => "web_url",
                                        "url" => secure_url('/') . "/public/order/" . $orderId,
                                        "title" => "View Order Details",
                                        "webview_height_ratio" => "full",
                                        "messenger_extensions" => true,
                                        "fallback_url" => secure_url('/') . "/public/order/" . $orderId
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        ];

        return json_encode($data);
    }

    public static function showSingleCart($userId, $title, $subTitle, $image, $orderId)
    {
        $data = [
            "recipient" => [
                "id" => $userId
            ],
            "message" => [
                "attachment" => [
                    "type" => "template",
                    "payload" => [
                        "template_type" => "generic",
                        "elements" => [
                            [
                                "title" => $title,
                                "image_url" => $image,
                                "subtitle" => Data::translate($subTitle, Data::getUserLang($userId)),
                                "buttons" => [

                                    [
                                        "type" => "postback",
                                        "title" => Data::translate("Remove", Data::getUserLang($userId)),
                                        "payload" => "cancelcart_" . $orderId
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        ];

        return json_encode($data);
    }

    /**
     * @param $userId
     * @param $orderNumber
     * @param $userName
     * @param $title
     * @param $shortDescription
     * @param $price
     * @param $quanity
     * @param $total
     * @param $subtotal
     * @param $street
     * @param $city
     * @param $postalCode
     * @param $state
     * @param $country
     * @param $image
     * @return string
     */
    public static function receipt($userId, $orderNumber, $userName, $title, $shortDescription, $price, $quanity, $total, $subtotal, $street, $city, $postalCode, $state, $country, $image, $paymentMethod)
    {
        $data = [
            "recipient" => [
                "id" => $userId
            ],
            "message" => [
                "attachment" => [
                    "type" => "template",
                    "payload" => [
                        "template_type" => "receipt",
                        "recipient_name" => $userName,
                        "order_number" => $orderNumber,
                        "currency" => Data::getCurrency(),
                        "payment_method" => $paymentMethod,
                        "order_url" => "https://zubehor.heroku.com",
//                        "timestamp" => "1428444852",
                        "elements" => [
                            [
                                "title" => $title,
                                "subtitle" => $shortDescription,
                                "quantity" => (int)$quanity,
                                "price" => $price,
                                "currency" => Data::getCurrency(),
                                "image_url" => url('/uploads') . "/" . $image,

                            ]
                        ],
                        "address" => [
                            "street_1" => $street,
                            "street_2" => "",
                            "city" => $city,
                            "postal_code" => $postalCode,
                            "state" => $state,
                            "country" => $country
                        ],
                        "summary" => [
                            "subtotal" => $subtotal,
                            "shipping_cost" => Data::getShippingCost(),
                            "total_tax" => Data::getTax(),
                            "total_cost" => $total
                        ]
                    ]
                ]
            ]
        ];


        return json_encode($data);
    }

    public static function receiptList($userId, $userName, $orderNumber, $items, $subtotal, $total, $paymentMethod)
    {

        $street = Customers::where('fbId', $userId)->value('street');
        if ($street == "none") {
            $street = Customers::where('fbId', $userId)->value('address');
        }
        $city = Customers::where('fbId', $userId)->value('city');
        $postalCode = Customers::where('fbId', $userId)->value('postal_code');
        $state = Customers::where('fbId', $userId)->value('state');
        $country = Customers::where('fbId', $userId)->value('country');


        $data = '
        {
  "recipient":{
    "id":"' . $userId . '"
  },
  "message":{
    "attachment":{
      "type":"template",
      "payload":{
        "template_type":"receipt",
        "recipient_name":"' . $userName . '",
        "order_number":"' . $orderNumber . '",
        "currency":"' . Data::getCurrency() . '",
        "payment_method":"' . $paymentMethod . '",        
        "order_url":"' . url('/') . '/public/orders/' . $orderNumber . '", 
        "elements":' . json_encode($items) . ',
        "address":{
          "street_1":"' . $street . '",
          "street_2":"",
          "city":"' . $city . '",
          "postal_code":"' . $postalCode . '",
          "state":"' . $state . '",
          "country":"' . $country . '"
        },
        "summary":{
          "subtotal":' . $subtotal . ',
          "shipping_cost":' . Data::getShippingCost() . ',
          "total_tax":' . Data::getTax() . ',
          "total_cost":' . $total . '
        }
      }
    }
  }
}
        ';


        return $data;
    }

    public static function shareLocation($sender)
    {
        $data = '{
  "recipient":{
    "id":"' . $sender . '"
  },
  "message":{
    "text":"Click the button to share your location:",
    "quick_replies":[
      {
        "content_type":"location",
      }
    ]
  }
}';

        return $data;
    }

    public static function webView($sender)
    {
        $data = '{
  "recipient":{
    "id":"' . $sender . '"
  },
  "message":{
    "attachment":{
      "type":"template",
      "payload":{
        "template_type":"generic",
        "elements":[
          {
            "title":"Breaking News: Record Thunderstorms",
            "subtitle":"The local area is due for record thunderstorms over the weekend.",
            "image_url":"https://thechangreport.com/img/lightning.png",
            "buttons":[
              {
                "type":"element_share"
              }              
            ]
          },
          {
            "title":"Breaking News: Record Thunderstorms",
            "subtitle":"The local area is due for record thunderstorms over the weekend.",
            "image_url":"https://thechangreport.com/img/lightning.png",
            "buttons":[
              {
                "type":"element_share"
              }              
            ]
          }
        ]
      }
    }
  }
}';
        return $data;
    }

    public static function shareSingleItem($sender, $title, $subtitle, $image)
    {
        $data = '{
  "recipient":{
    "id":"' . $sender . '"
  },
  "message":{
    "attachment":{
      "type":"template",
      "payload":{
        "template_type":"generic",
        "elements":[
          {
            "title":"' . $title . '",
            "subtitle":"' . $subtitle . '",
            "image_url":"' . url('/uploads') . "/" . $image . '",
            "buttons":[
              {
                "type":"element_share"
              }              
            ]
          }
        ]
      }
    }
  }
}';

        return $data;
    }

    public static function buttonSingle($sender, $productId)
    {
        $data = '{
  "recipient":{
    "id":"' . $sender . '"
  },
  "message":{
    "attachment":{
      "type":"template",
      "payload":{
        "template_type":"generic",
        "elements":[
          {
          "title":"You can share with friends",
            "buttons":[
              {
                "type":"postback",
                "title":"Share this",
                "payload":"share_' . $productId . '"
              }
            ]
          }
        ]
      }
    }
  }
}';
        return $data;
    }

    public function toUser(Request $request)
    {
        try {
            if ($request->message != "") {
                $msg = Run::fire(self::sendMessage($request->sender, $request->message));
            }

            if ($request->image != "") {
                $img = Run::fire(self::sendImage($request->sender, $request->image));
            }

            if ($request->audio != "") {
                $audio = Run::fire(self::sendAudio($request->sender, $request->audio));
            }

            if ($request->video != "") {
                $video = Run::fire(self::sendVideo($request->sender, $request->video));
            }


        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public static function continueShoppin($sender)
    {
        $buttons = [];

        $btnYes = [
            "type" => "postback",
            "payload" => "view_products",
            "title" => Data::translate("Yes", Data::getUserLang($sender))
        ];


        $btnCheckOut = [
            "type" => "postback",
            "payload" => "checkout",
            "title" => Data::translate("Checkout", Data::getUserLang($sender))
        ];
        $cartCount = Cart::where('sender', $sender)->count();
        $btnCart = [
            "type" => "postback",
            "payload" => "my_cart",
            "title" => Data::translate("My Cart (" . $cartCount . ")", Data::getUserLang($sender))
        ];


        array_push($buttons, Send::elements(Data::translate("Continue shopping ?", Data::getUserLang($sender)) . "", "", "", [$btnYes, $btnCart, $btnCheckOut]));
        $jsonData = Send::item($sender, $buttons);
        Run::fire($jsonData);
    }


    public static function askForOrder($sender)
    {
        $buttons = [];

        $btnYes = [
            "type" => "postback",
            "payload" => "paymentMethod",
            "title" => Data::translate("Yes", Data::getUserLang($sender))
        ];


        $btnNo = [
            "type" => "postback",
            "payload" => "menu",
            "title" => Data::translate("No", Data::getUserLang($sender))
        ];

        $btnShopping = [
            "type" => "postback",
            "payload" => "view_products",
            "title" => Data::translate("Continue Shopping", Data::getUserLang($sender))
        ];


        array_push($buttons, Send::elements(Data::translate("Do you want to place order ?", Data::getUserLang($sender)) . "", "", "", [$btnYes, $btnNo, $btnShopping]));

        $jsonData = Send::item($sender, $buttons);
        Run::fire($jsonData);
    }

    public static function paymentMethod($sender)
    {
        $buttons = [];

        $btnPyaPal = [
            "type" => "postback",
            "payload" => "paypal",
            "title" => Data::translate("Pay Via PayPal", Data::getUserLang($sender))
        ];


        $btnCash = [
            "type" => "postback",
            "payload" => "order",
            "title" => Data::translate("Cash on delivery", Data::getUserLang($sender))
        ];

        $btnShopping = [
            "type" => "postback",
            "payload" => "view_products",
            "title" => Data::translate("Continue Shopping", Data::getUserLang($sender))
        ];


        array_push($buttons, Send::elements(Data::translate("Select a payment method", Data::getUserLang($sender)) . "", "", "", [$btnPyaPal, $btnCash, $btnShopping]));

        $jsonData = Send::item($sender, $buttons);
        Run::fire($jsonData);
    }

    public static function checkout($sender)
    {
        $data = '{
  "recipient":{
    "id":"' . $sender . '"
  },
  "message":{
    "attachment":{
      "type":"template",
      "payload":{
        "template_type":"generic",
        "elements":[
          {
          "title":"Checkout now",
            "buttons":[
              {
                "type":"postback",
                "title":"Checkout",
                "payload":"checkout"
              }
            ]
          }
        ]
      }
    }
  }
}';
        Run::fire($data);
    }

    public static function placeOrderViaPaypal($userId)
    {
        $data = [
            "recipient" => [
                "id" => $userId
            ],
            "message" => [
                "attachment" => [
                    "type" => "template",
                    "payload" => [
                        "template_type" => "generic",
                        "elements" => [[
                            "title" => Data::translate("Login to pay via PayPal", Data::getUserLang($userId)),
                            "buttons" => [[
                                "type" => "account_link",
                                "url" => url('/payment') . "/" . $userId
                            ]]
                        ]]
                    ]
                ]
            ]
        ];


        Run::fire(json_encode($data));

    }


}
