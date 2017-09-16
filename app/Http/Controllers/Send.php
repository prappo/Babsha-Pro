<?php

namespace App\Http\Controllers;

use App\Cart;
use App\Customers;
use App\FacebookPages;
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
                "text" => $message
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
                                "subtitle" => $subTitle,
                                "buttons" => [

                                    [
                                        "type" => "postback",
                                        "title" => "Remove",
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
                                "subtitle" => $subTitle,
                                "buttons" => [

                                    [
                                        "type" => "postback",
                                        "title" => "Remove",
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
    public static function receipt($userId, $orderNumber, $userName, $title, $shortDescription, $price, $quanity, $total, $subtotal, $street, $city, $postalCode, $state, $country, $image, $paymentMethod, $pageId)
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
                        "currency" => Data::getCurrency($pageId),
                        "payment_method" => $paymentMethod,
                        "order_url" => "https://zubehor.heroku.com",
//                        "timestamp" => "1428444852",
                        "elements" => [
                            [
                                "title" => $title,
                                "subtitle" => $shortDescription,
                                "quantity" => (int)$quanity,
                                "price" => $price,
                                "currency" => Data::getCurrency($pageId),
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
                            "shipping_cost" => Data::getShippingCost($pageId),
                            "total_tax" => Data::getTax($pageId),
                            "total_cost" => $total
                        ]
                    ]
                ]
            ]
        ];


        return json_encode($data);
    }

    public static function receiptList($userId, $userName, $orderNumber, $items, $subtotal, $total, $paymentMethod, $pageId)
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
        "currency":"' . Data::getCurrency($pageId) . '",
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
          "shipping_cost":' . Data::getShippingCost($pageId) . ',
          "total_tax":' . Data::getTax($pageId) . ',
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
        $data = Customers::where('fbId', $request->sender)->first();
        $pageId = $data->pageId;
        try {
            if ($request->message != "") {
                 Run::fire(self::sendMessage($request->sender, $request->message), $pageId);
            }

            if ($request->image != "") {
                Run::fire(self::sendImage($request->sender, $request->image), $pageId);
            }

            if ($request->audio != "") {
                Run::fire(self::sendAudio($request->sender, $request->audio), $pageId);
            }

            if ($request->video != "") {
                 Run::fire(self::sendVideo($request->sender, $request->video), $pageId);
            }


        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public static function continueShoppin($sender, $pageId)
    {
        $buttons = [];

        $btnYes = [
            "type" => "postback",
            "payload" => "view_products",
            "title" => "Yes"
        ];


        $btnCheckOut = [
            "type" => "postback",
            "payload" => "checkout",
            "title" => "Checkout"
        ];
        $cartCount = Cart::where('sender', $sender)->count();
        $btnCart = [
            "type" => "postback",
            "payload" => "my_cart",
            "title" => "My Cart (" . $cartCount . ")"
        ];


        array_push($buttons, Send::elements("Continue shopping ?" . "", "", "", [$btnYes, $btnCart, $btnCheckOut]));
        $jsonData = Send::item($sender, $buttons);
        Run::fire($jsonData, $pageId);
    }


    public static function askForOrder($sender, $pageId)
    {
        $buttons = [];

        $btnYes = [
            "type" => "postback",
            "payload" => "paymentMethod",
            "title" => "Yes"
        ];


        $btnNo = [
            "type" => "postback",
            "payload" => "menu",
            "title" => "No"
        ];

        $btnShopping = [
            "type" => "postback",
            "payload" => "view_products",
            "title" => "Continue Shopping"
        ];


        array_push($buttons, Send::elements("Do you want to place order ?" . "", "", "", [$btnYes, $btnNo, $btnShopping]));

        $jsonData = Send::item($sender, $buttons);
        Run::fire($jsonData, $pageId);
    }

    public static function paymentMethod($sender, $pageId)
    {
        $buttons = [];

        $btnPyaPal = [
            "type" => "postback",
            "payload" => "paypal",
            "title" => "Pay Via PayPal"
        ];


        $btnCash = [
            "type" => "postback",
            "payload" => "order",
            "title" => "Cash on delivery"
        ];

        $btnShopping = [
            "type" => "postback",
            "payload" => "view_products",
            "title" => "Continue Shopping"
        ];


        array_push($buttons, Send::elements("Select a payment method" . "", "", "", [$btnPyaPal, $btnCash, $btnShopping]));

        $jsonData = Send::item($sender, $buttons);
        Run::fire($jsonData, $pageId);
    }

    public static function checkout($sender, $pageId)
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
        Run::fire($data, $pageId);
    }

    public static function placeOrderViaPaypal($userId, $pageId)
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
                            "title" => "Login to pay via PayPal",
                            "buttons" => [[
                                "type" => "account_link",
                                "url" => url('/payment') . "/" . $userId . "/" . $pageId
                            ]]
                        ]]
                    ]
                ]
            ]
        ];


        Run::fire(json_encode($data), $pageId);

    }


}
