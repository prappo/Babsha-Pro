<?php

namespace App\Http\Controllers;

use App\Cart;
use App\Catagories;
use App\Customers;
//use App\Orders;
use App\FacebookPages;
use App\Products;
use Stichoza\GoogleTranslate\TranslateClient;

class Run extends Controller
{
    /**
     * Main execution point
     *
     * @param $input
     */

    public static function now($input)
    {
        $sender = isset($input['entry'][0]['messaging'][0]['sender']['id']) ? $input['entry'][0]['messaging'][0]['sender']['id'] : $input['entry'][0]['standby'][0]['sender']['id'];
        $pageId = isset($input['entry'][0]['messaging'][0]['recipient']['id']) ? $input['entry'][0]['messaging'][0]['recipient']['id'] : $input['entry'][0]['standby'][0]['recipient']['id'];
        $postback = isset($input['entry'][0]['messaging'][0]['postback']['payload']) ? $input['entry'][0]['messaging'][0]['postback']['payload'] : "nothing";
        $catPostBack = isset($input['entry'][0]['messaging'][0]['message']['quick_reply']['payload']) ? $input['entry'][0]['messaging'][0]['message']['quick_reply']['payload'] : "nothing";
        $message = isset($input['entry'][0]['messaging'][0]['message']['text']) ? $input['entry'][0]['messaging'][0]['message']['text'] : "nothing";
        $url = 'https://graph.facebook.com/v2.6/me/messages?access_token=' . Data::getToken($pageId);
        $linking = isset($input['entry'][0]['messaging'][0]['account_linking']['status']) ? $input['entry'][0]['messaging'][0]['account_linking']['status'] : "nothing";

        $location = isset($input['entry'][0]['messaging'][0]['message']['attachments'][0]['payload']['coordinates']) ? $input['entry'][0]['messaging'][0]['message']['attachments'][0]['payload']['coordinates'] : "nothing";
        $ch = curl_init($url);
        $jsonData = "";
        $msg = "";

        /*
         * Boot up section
         *
         * */

//        Boot::up();

        if ($linking != "nothing") {
            if ($input['entry'][0]['messaging'][0]['account_linking']['authorization_code'] == "1337") {
                self::fire(Send::sendText($sender, "Thank you for updating your information"), $pageId);
            } elseif ($input['entry'][0]['messaging'][0]['account_linking']['authorization_code'] == "7331") {
//                self::fire(Send::sendText($sender, "Payment successfully received"));
            }

            exit;
        }


        $lang = 'en';
        $translatedText = $message;
        /* blocking bot for user */
        if (Customers::where('fbId', $sender)->where('pageId', $pageId)->exists()) {
            $botStatus = Customers::where('fbId', $sender)->where('pageId', $pageId)->value('bot');
            if ($botStatus == 'no') {
                exit;
            }
        }

//        if ($location != "nothing") {
//            $lat = $input['entry'][0]['messaging'][0]['message']['attachments'][0]['payload']['coordinates']['lat'];
//            $long = $input['entry'][0]['messaging'][0]['message']['attachments'][0]['payload']['coordinates']['long'];
//            $coordinates = $lat . "," . $long;
//            $mapJson = file_get_contents("http://maps.googleapis.com/maps/api/geocode/json?latlng=" . $coordinates);
//            $data = json_decode($mapJson);
//            $address = $data->results[0]->address_components[0]->long_name . ", " . $data->results[0]->address_components[1]->long_name . ", " . $data->results[0]->address_components[2]->long_name . ", " . $data->results[0]->address_components[3]->long_name . ", " . $data->results[0]->address_components[4]->long_name . ", " . $data->results[0]->address_components[5]->long_name . ", " . $data->results[0]->address_components[6]->long_name;
//            try {
//                Customers::where('fbId', $sender)->update([
//                    'coordinates' => $coordinates,
//                    'address' => $address
//                ]);
//                self::fire(Send::sendText($sender, "Thank you for updating shipping address"),$pageId);
//                exit;
//            } catch (\Exception $e) {
//                self::fire(Send::sendText($sender, "Something went wrong . We couldn't store your location"),$pageId);
//                exit;
//            }
//
//
//        }


        if (!empty($input['entry'][0]['messaging'][0]['message'])) {

            if ($message != "nothing") {


                /*
                 * find if existed in array
                 *
                 * */
                $arr = ["hi", "Hi", "hello", "Hello"];

                if (in_array($message, $arr)) {
                    if (Customers::where('fbId', $sender)->where('pageId', $pageId)->exists()) {
                        self::fire(Send::sendText($sender, "Welcome back " . Customers::where('fbId', $sender)->value('name')), $pageId);
                        self::fire(Send::sendText($sender, "Check out our menu"), $pageId);
                        self::fire(Send::sendText($sender, "Or, If you need more help please type 'help'"), $pageId);
                        self::fire(self::getMenu($sender, $pageId), $pageId);
                        echo "Customer already exists";
                        exit;


                    } else {
                        //                        Create new customer
                        try {
                            if (Customers::where('fbId', $sender)->where('pageId', $pageId)->exists()) {

                            } else {
                                try {
                                    $user = Receive::getProfile($sender, Data::getToken($pageId));
                                    $customer = new Customers();
                                    $customer->fbId = $sender;
                                    $customer->name = $user->first_name . " " . $user->last_name;
                                    $customer->image = $user->profile_pic;
                                    $customer->street = "none";
                                    $customer->city = "none";
                                    $customer->country = "none";
                                    $customer->postal_code = "none";
                                    $customer->state = "none";
                                    $customer->mobile = "none";
                                    $customer->lang = $lang;
                                    $customer->pageId = $pageId;
                                    $customer->userId = FacebookPages::where('pageId', $pageId)->value('userId');
                                    $customer->save();
                                } catch (\Exception $exception) {

                                }


                            }
                        } catch (\Exception $e) {
                            echo $e->getMessage() . "\n";
                        }

                        self::fire(Send::sendText($sender, "Hi " . Customers::where('fbId', $sender)->value('name')), $pageId);
                        self::fire(Send::sendText($sender, "Check out our menu"), $pageId);
                        self::fire(Send::sendText($sender, "Or, If you need more help please type 'help'"), $pageId);
                        self::fire(self::getMenu($sender, $pageId), $pageId);
                        echo "New customer detected";


                        exit;
                    }
                } elseif (Bot::checkPer($message) >= 65) {

                    self::fire(Send::sendMessage($sender, Bot::check($message, $pageId)), $pageId);
                    exit;

                } elseif (Data::similar(strtolower($message), "help") >= 70) {
                    self::fire(Send::sendText($sender, Send::helpText()), $pageId);
                    exit;
                } elseif (Data::similar(strtolower($message), "my cart") >= 70) {
                    self::myCart($sender, $pageId);
                    exit;
                } elseif (Data::similar(strtolower($message), "my orders") >= 70) {
                    self::myOrders($sender, $pageId);
                    exit;
                } elseif (Data::similar(strtolower($message), "ordered item list") >= 70) {
                    self::myOrdersItem($sender, $pageId);
                    exit;
                } elseif (Data::similar(strtolower($message), "menu") >= 70) {
                    self::fire(self::getMenu($sender, $pageId), $pageId);
                    exit;
                } elseif (Data::similar(strtolower($message), "contact") >= 70) {
                    self::fire(self::contactOptions($sender, $pageId), $pageId);
                    exit;
                } elseif (Data::similar(strtolower($message), "account") >= 70) {
                    self::fire(self::showProfile($sender, $pageId), $pageId);
                    exit;
                } elseif (Data::similar(strtolower($message), "products") >= 70) {
                    self::fire(Send::sendText($sender, "Select a category"), $pageId);
                    $json = self::getCategories($sender, $pageId);
                    self::fire($json, $pageId);
                    exit;
                } elseif (Data::similar(strtolower($message), "featured products") >= 70) {
                    self::showFeaturedProducts($sender, $pageId);
                    exit;
                } elseif (Data::similar(strtolower($message), "more options") >= 70) {
                    $jsonData = self::moreOptions($sender, $pageId);
                    self::fire($jsonData, $pageId);
                    exit;
                } elseif (Data::similar(strtolower($message), "checkout") >= 70) {
                    Send::paymentMethod($sender, $pageId);
                    exit;
                } elseif (Data::similar(strtolower($message), "email") >= 70) {
                    $jsonData = Send::sendMessage($sender, Data::getEmail($pageId));
                    self::fire($jsonData, $pageId);
                    exit;
                } else {
                    if ($catPostBack == "nothing") {
                        if (Customers::where('fbId', $sender)->exists()) {
                            $botStatus = Customers::where('fbId', $sender)->value('bot');
                            if ($botStatus == 'no') {
                                exit;
                            }
                        }
//


                        $msgArr = ["Oops, I didn't catch that. For things I can help you with, type 'help' or check out our menu.", "I’m sorry; I’m not sure I understand. Try typing 'help' or check out our menu"];
                        $index = array_rand($msgArr);
                        self::fire(Send::sendText($sender, $msgArr[$index]), $pageId);
                        exit;


                    }

                }
            }

        }

        /*
         * Checking user if already exist
         * */
        echo "get incoming connection\n";


        if ($catPostBack == "nothing") {

            if ($postback == "nothing") {
                $jsonData = self::getMenu($sender, $pageId);
//                self::fire($jsonData,$pageId);
//                exit;
            } else {
                if ($postback == "contact_us") {

                    $jsonData = self::contactOptions($sender, $pageId);
                    self::fire($jsonData, $pageId);
                    exit;
                } elseif ($postback == "menu") {
                    $jsonData = self::getMenu($sender, $pageId);
                    self::fire($jsonData, $pageId);
                } elseif ($postback == "me") {
                    $jsonData = self::showProfile($sender, $pageId);
                    self::fire($jsonData, $pageId);
                } elseif ($postback == "view_products") {
                    self::fire(Send::sendText($sender, "Please select a category"), $pageId);
                    $jsonData = self::getCategories($sender, $pageId);
                    self::fire($jsonData, $pageId);
                    exit;
                } elseif ($postback == "help") {
                    self::fire(Send::sendText($sender, Send::helpText()), $pageId);
                    exit;
                } elseif ($postback == "more_options") {

                    $jsonData = self::moreOptions($sender, $pageId);
                    self::fire($jsonData, $pageId);
                    exit;

                } elseif ($postback == "mail_us") {
                    $jsonData = Send::sendText($sender, Data::getEmail($pageId));
                    self::fire($jsonData, $pageId);
                    exit;
                } elseif ($postback == "subscribe") {
                    self::fire(Send::sendText($sender, Customer::subscribe($sender, $pageId)), $pageId);
                    exit;
                } elseif ($postback == "user_orders") {
//                    $myOrders = \App\Orders::where('sender', $sender)->where('status', 'pending')->get();
//                    $price = 0;
//                    $count = 0;
//                    foreach ($myOrders as $order) {
//                        foreach (Products::where('id', $order->productid)->get() as $por) {
//
//                            $count++;
//                            $price += $por->price;
//                            self::fire(Send::showSingleOrder($sender, $por->title, $por->short_description, url('/uploads') . "/" . $por->image, $order->id));
//                        }
//                    }
//                    self::fire(Send::sendText($sender, "Total Orders : " . $count));
//                    self::fire(Send::sendText($sender, "Total Cost : " . Data::getUnit() . $price));
                    if (\App\Orders::where('status', 'pending')->where('sender', $sender)->count() == 0) {
                        self::fire(Send::sendText($sender, "You don't have any order"), $pageId);
                        exit;
                    }
                    $orders = \App\Orders::distinct()->where('status', 'pending')->where('sender', $sender)->get(['orderId', 'created_at', 'sender']);
                    foreach ($orders as $order) {
                        self::fire(Send::showSingleOrderList($sender, "Order No #" . $order->orderId, $order->orderId), $pageId);
                    }
                    exit;

                } elseif ($postback == "my_cart") {
                    self::myCart($sender, $pageId);

                } elseif ($postback == "checkout") {
                    Send::paymentMethod($sender, $pageId);

                } elseif ($postback == "paypal") {
                    Send::placeOrderViaPaypal($sender, $pageId);
                } elseif ($postback == "paymentMethod") {
                    self::fire(Send::paymentMethod($sender, $pageId), $pageId);
                } elseif ($postback == "order") {
                    /*
                      * make  order
                      *
                      * */
                    $orderID = uniqid();
                    $cart = Cart::where('sender', $sender)->get();
                    foreach ($cart as $c) {
                        $newOrder = new \App\Orders();
                        $newOrder->sender = $sender;
                        $newOrder->orderId = $orderID;
                        $newOrder->productid = $c->productid;
                        $newOrder->status = "pending";
                        $newOrder->price = $c->price;
                        $newOrder->method = "Cash on delivery";
                        $newOrder->payment = "Not paid";
                        $newOrder->type = $c->type;
                        $newOrder->save();
                    }
                    try {
                        Cart::where('sender', $sender)->delete();
                    } catch (\Exception $e) {
                        self::fire(Send::sendText($sender, "Something went wrong we couldn't remove products form cart"), $pageId);
                    }
                    self::fire(Send::sendText($sender, Data::getAfterOrderMsg($pageId)), $pageId);
                    self::fire(Send::sendText($sender, "Your Order ID #" . $orderID), $pageId);
                    self::fire(Send::updateInfo($sender), $pageId);

                    Data::notify(Customers::where('fbId', $sender)->value('name') . " Placed and order with Cash on Delivery Method");

                } elseif ($postback == "featured") {
//                    show featured products
                    self::showFeaturedProducts($sender, $pageId);

                    exit;
                } else {
                    $data = explode("_", $postback);
                    $prefex = $data[0];
                    $postfex = isset($data[1]) ? $data[1] : "";

                    if ($prefex == "cart") {
                        /*
                         * Add to cart
                         *
                         * */

                        $orderId = $data[1];
                        $productId = $data[2];
                        $type = $data[3];
                        if ($type != "woo") {
                            if (!Products::where('id', $productId)->exists()) {
                                self::fire(Send::sendText($sender, "Product don't exists"), $pageId);
                                exit;
                            }
                        }
                        if ($type == "woo") {
                            $wooProduct = new WooProduct($productId, $pageId);
                            $cart = new Cart();
                            $cart->orderId = $orderId;
                            $cart->sender = $sender;
                            $cart->productid = $productId;
                            $cart->status = "pending";
                            $cart->price = $wooProduct->price;
                            $cart->type = $type;
                            $cart->userId = FacebookPages::where('pageId', $pageId)->value('userId');
                            $cart->pageId = $pageId;
                            $cart->save();
                        } else {
                            $productPrice = Products::where('id', $productId)->value('price');
                            $cart = new Cart();
                            $cart->orderId = $orderId;
                            $cart->sender = $sender;
                            $cart->productid = $productId;
                            $cart->status = "pending";
                            $cart->price = $productPrice;
                            $cart->type = $type;
                            $cart->userId = FacebookPages::where('pageId', $pageId)->value('userId');
                            $cart->pageId = $pageId;
                            $cart->save();
                        }

                        self::fire(Send::sendText($sender, "Added to cart"), $pageId);
                        self::fire(Send::sendText($sender, "You can check your cart by typing 'my cart'"), $pageId);
                        self::fire(Send::sendText($sender, "For check out type 'checkout'"), $pageId);
                        Send::continueShoppin($sender, $pageId);
                        exit;

                    } elseif ($prefex == "order") {


                    } elseif ($prefex == "share") {
                        $sharedProduct = Products::where('id', $postfex)->where('status', 'published')->first();
                        self::fire(Send::shareSingleItem($sender, $sharedProduct->title, $sharedProduct->short_description, $sharedProduct->image), $pageId);
                        exit;
                    } elseif ($prefex == "details") {
                        /**
                         *
                         * @Show product details
                         *
                         * */

                        try {
                            $d = Products::where('id', $postfex)
                                ->where('status', 'published')
                                ->first();
                            self::fire(Send::sendImage($sender, url('/uploads') . "/" . $d->image), $pageId);

                            self::fire(Send::sendText($sender, $d->title), $pageId);
                            self::fire(Send::sendText($sender, Data::getUnit($pageId) . $d->price), $pageId);
                            self::fire(Send::sendText($sender, $d->short_description), $pageId);
                            self::fire(Send::sendText($sender, $d->long_description), $pageId);
                            $products = [];

                            $btnOrder = [
                                "type" => "postback",
                                "payload" => "cart_" . $sender . "_" . $d->id . "_" . "babsha",
                                "title" => "Add to cart"
                            ];


                            $btnBack = [
                                "type" => "postback",
                                "payload" => "view_products",
                                "title" => "Continue Shopping"
                            ];
                            $cartCount = Cart::where('sender', $sender)->count();

                            $btnBuy = [
                                "type" => "postback",
                                "payload" => "my_cart",
                                "title" => "My Cart " . "(" . $cartCount . ")"
                            ];

                            array_push($products, Send::elements("Select an option" . "", "", "", [$btnOrder, $btnBuy, $btnBack]));

                            $jsonData = Send::item($sender, $products);
                            self::fire($jsonData, $pageId);
                            self::fire(Send::buttonSingle($sender, $d->id), $pageId);

                            exit;
                        } catch (\Exception $e) {

                            /**
                             * if products can't find in database then try
                             * to find if it's exists in WooCommerce
                             */

                            $products = [];
                            $woo = new WooController($pageId);
                            $product = $woo->getProductById($postfex);

                            self::fire(Send::sendImage($sender, $product['images'][0]['src']), $pageId);


                            self::fire(Send::sendText($sender, $product['name']), $pageId);
                            self::fire(Send::sendText($sender, Data::getUnit($pageId) . $product['price']), $pageId);
                            self::fire(Send::sendText($sender, strip_tags($product['description'])), $pageId);

                            /*
                             * request to client for place an order
                             *
                             * */
                            $btnOrder = [
                                "type" => "postback",
                                "payload" => "cart_" . $sender . "_" . $product['id'] . "_" . "woo",
                                "title" => "Yes"
                            ];


                            $btnBack = [
                                "type" => "postback",
                                "payload" => "view_products",
                                "title" => "No thanks"
                            ];
                            array_push($products, Send::elements("Do you want to add to cart ?" . "", "", "", [$btnOrder, $btnBack]));

                            $jsonData = Send::item($sender, $products);
                            self::fire($jsonData, $pageId);
                            exit;

                        }
                    } elseif ($prefex == "cancel") {
                        /*
                         * cancel order
                         *
                         * */

                        try {
                            \App\Orders::where('id', $postfex)->delete();
                            self::fire(Send::sendText($sender, "Removed order"), $pageId);
                        } catch (\Exception $e) {
                            self::fire(Send::sendText($sender, "Something went wrong , for this moment we can't cancel your order please try again later"), $pageId);
                        }
                    } elseif ($prefex == "cancelcart") {
                        /*
                         * cancel order
                         *
                         * */

                        try {
                            Cart::where('id', $postfex)->delete();
                            self::fire(Send::sendText($sender, "Removed from cart"), $pageId);
                            Send::continueShoppin($sender, $pageId);
                        } catch (\Exception $e) {
                            self::fire(Send::sendText($sender, "Something went wrong , for this moment we can't cancel your order please try again later"), $pageId);
                        }
                    }
                }
            }
        } else {
//            show products by categories

            $data = explode("_", $catPostBack);
            $key = $data[0];
            $value = $data[1];
            if ($key == "cat") {

                /*
                 * try to get products form WooCommerce and show them
                 *
                 * */
                try {
                    $wooCommerce = new WooController($pageId);
                    if ($wooCommerce->catExists($value)) {
                        if ($wooCommerce->getProducts($value) != "none") {
                            $products = [];
                            $productsMainArr = $wooCommerce->getProducts($value);
                            foreach (array_chunk($productsMainArr, 10) as $productsArr) {
                                foreach ($productsArr as $pro) {
                                    $btnOrder = [
                                        "type" => "postback",
                                        "payload" => "cart_" . $sender . "_" . $pro['id'] . "_" . "woo",
                                        "title" => "Add to cart"
                                    ];
                                    $btnDetails = [
                                        "type" => "postback",
                                        "payload" => "details_" . $pro['id'],
                                        "title" => "Product Details"
                                    ];

                                    $btnBack = [
                                        "type" => "postback",
                                        "payload" => "view_products",
                                        "title" => "Back"
                                    ];
                                    array_push($products, Send::elements($pro['name'] . " (" . Data::getUnit($pageId) . $pro['price'] . ")", strip_tags($pro['short_description']), $pro['images'][0]['src'], [$btnOrder, $btnDetails, $btnBack]));
                                }
                                $jsonData = Send::item($sender, $products);
                                self::fire($jsonData, $pageId);
                            }

                            exit;


                        } else {
                            self::fire(Send::sendText($sender, "Sorry ! Products could not found \nPlease select another category"), $pageId);
                            self::fire(self::getCategories($sender, $pageId), $pageId);
                            exit;

                        }

                    }
                } catch (\Exception $e) {
                }


                if (Products::where('category', $value)
                        ->where('status', 'published')
                        ->count() == 0
                ) {
                    self::fire(Send::sendText($sender, "Sorry ! Products could not found \nPlease select another category"), $pageId);
                    self::fire(self::getCategories($sender, $pageId), $pageId);
                    exit;
                } else {
                    $d = Products::where('category', $value)
                        ->where('status', 'published')
                        ->get();
                    $products = [];

                    foreach ($d->chunk(10) as $i => $chunk) {
                        foreach ($chunk as $pro) {
                            $btnOrder = [
                                "type" => "postback",
                                "payload" => "cart_" . $sender . "_" . $pro->id . "_" . "babsha",
                                "title" => "Add to cart"
                            ];
                            $btnDetails = [
                                "type" => "postback",
                                "payload" => "details_" . $pro->id,
                                "title" => "Product Details"
                            ];

                            $btnBack = [
                                "type" => "postback",
                                "payload" => "view_products",
                                "title" => "Back"
                            ];
                            array_push($products, Send::elements($pro->title . " (" . Data::getUnit($pageId) . $pro->price . ")", $pro->short_description, url('/uploads') . "/" . $pro->image, [$btnOrder, $btnDetails, $btnBack]));
                        }
                        $jsonData = Send::item($sender, $products);
                        self::fire($jsonData, $pageId);
                        $products = [];

                    }

                    exit;


                }
            }


        }

//        curl_setopt($ch, CURLOPT_POST, 1);
//        curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
//        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
//
//        if (!empty($input['entry'][0]['messaging'][0]['message']) || isset($input['entry'][0]['messaging'][0]['postback']['payload'])) {
//
//            $result = curl_exec($ch);
//        }
    }

    /**
     * @param $sender
     * @return string
     */
    public static function getCategories($sender, $pageId)
    {
        $menu = [];
//        $wooCommerce = new WooController($pageId);
//        $woo = $wooCommerce->woo;

        $data = Catagories::where('pageId', $pageId)->get();
        foreach ($data as $d) {
            array_push($menu, Send::quickBtn($d->name, "cat_" . $d->id));
        }
//        try {
//            $categories = $woo->get('products/categories');
//            foreach ($categories as $category) {
//                array_push($menu, Send::quickBtn($category['name'], "cat_" . $category['id']));
//            }
//        } catch (\Exception $e) {
//        }


        return Send::button($sender, "Categories", $menu);

    }

    /**
     * @param $sender
     * @return string
     */
    public static function getMenu($sender, $pageId)
    {
        $data = [
            "recipient" => [
                "id" => $sender
            ],
            "message" => [
                "attachment" => [
                    "type" => "template",
                    "payload" => [
                        "template_type" => "generic",
                        "elements" => [
                            [
                                "title" => Data::getShopTitle($pageId),
                                "image_url" => Data::getLogo($pageId),
                                "subtitle" => Data::getShopSubTitle($pageId),
                                "buttons" => [
                                    [
                                        "type" => "postback",
                                        "title" => "Our products",
                                        "payload" => "view_products"
                                    ],
                                    [
                                        "type" => "postback",
                                        "title" => "More Options",
                                        "payload" => "more_options"
                                    ],
                                    [
                                        "type" => "postback",
                                        "title" => "Contact us",
                                        "payload" => "contact_us"
                                    ]

                                ]
                            ]
                        ]
                    ]
                ]

            ]];

        return json_encode($data);


    }

    /**
     * @param $sender
     * @return string
     */
    public static function moreOptions($sender, $pageId)
    {
        $data = [
            "recipient" => [
                "id" => $sender
            ],
            "message" => [
                "attachment" => [
                    "type" => "template",
                    "payload" => [
                        "template_type" => "generic",
                        "elements" => [
                            [
                                "title" => Data::getShopTitle($pageId),
                                "image_url" => Data::getLogo($pageId),
                                "subtitle" => Data::getShopSubTitle($pageId),
                                "buttons" => [
                                    [
                                        "type" => "postback",
                                        "title" => "My account",
                                        "payload" => "me"
                                    ],
                                    [
                                        "type" => "postback",
                                        "title" => "Featured Items",
                                        "payload" => "featured"
                                    ],
                                    [
                                        "type" => "postback",
                                        "title" => "Back",
                                        "payload" => "menu"
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]

            ]];

        return json_encode($data);


    }

    /**
     * @param $sender
     * @return string
     */
    public static function contactOptions($sender, $pageId)
    {
        $data = [
            "recipient" => [
                "id" => $sender
            ],
            "message" => [
                "attachment" => [
                    "type" => "template",
                    "payload" => [
                        "template_type" => "generic",
                        "elements" => [
                            [
                                "title" => Data::getShopTitle($pageId),
                                "image_url" => Data::getMap($pageId),
                                "subtitle" => Data::getShopSubTitle($pageId),
                                "buttons" => [
                                    [
                                        "type" => "postback",
                                        "title" => "Email",
                                        "payload" => "mail_us"
                                    ],
                                    [
                                        "type" => "phone_number",
                                        "title" => "Call us",
                                        "payload" => Data::getPhone($pageId)
                                    ],
                                    [
                                        "type" => "postback",
                                        "title" => "Back",
                                        "payload" => "menu"
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]

            ]];


        return json_encode($data);


    }

    /**
     * @param $jsonData
     */
    public static function fire($jsonData, $pageId)
    {
        $url = 'https://graph.facebook.com/v2.6/me/messages?access_token=' . Data::getToken($pageId);
        $ch = curl_init($url);

        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        curl_exec($ch);


    }

    /**
     * @param $sender
     * @return string
     */
    public static function showProfile($sender, $pageId)
    {
        $user = Receive::getProfile($sender, Data::getToken($pageId));
        $pic = $user->profile_pic;
        $name = $user->first_name . " " . $user->last_name;
        $gender = $user->gender;
        $orderCount = \App\Orders::where('sender', $sender)->where('status', 'pending')->count();
        $cartCount = Cart::where('sender', $sender)->count();
        $data = [
            "recipient" => [
                "id" => $sender
            ],
            "message" => [
                "attachment" => [
                    "type" => "template",
                    "payload" => [
                        "template_type" => "generic",
                        "elements" => [
                            [
                                "title" => $name,
                                "image_url" => $pic,
                                "subtitle" => $gender,
                                "buttons" => [
                                    [
                                        "type" => "postback",
                                        "title" => "My Orders " . "(" . $orderCount . ")",
                                        "payload" => "user_orders"
                                    ],
                                    [
                                        "type" => "postback",
                                        "title" => "My Cart " . "(" . $cartCount . ")",
                                        "payload" => "my_cart"
                                    ],
                                    [
                                        "type" => "postback",
                                        "title" => "Subscribe / Unsubscribe",
                                        "payload" => "subscribe"
                                    ]

                                ]
                            ]
                        ]
                    ]
                ]

            ]];

        return json_encode($data);
    }

    /**
     * @param $sender
     */
    public static function myOrdersItem($sender, $pageId)
    {
        $myOrders = \App\Orders::where('sender', $sender)
            ->where('status', 'pending')
            ->get();
        $price = 0;
        $count = 0;

        foreach ($myOrders as $order) {

            foreach (Products::where('id', $order->productid)->get() as $por) {

                $count++;
                $price += $por->price;
                self::fire(Send::showSingleOrder($sender, $por->title, $por->short_description . "\nPrice :" . Data::getUnit($pageId) . $por->price . "\n", url('/uploads') . "/" . $por->image, $order->id), $pageId);
            }
        }
        self::fire(Send::sendText($sender, "Total Orders : " . $count), $pageId);
        self::fire(Send::sendText($sender, "Total Cost : " . Data::getUnit($pageId) . $price), $pageId);
    }

    public static function myOrders($sender, $pagId)
    {
        $orders = \App\Orders::distinct()->where('status', 'pending')->where('sender', $sender)->get(['orderId', 'created_at', 'sender']);
        foreach ($orders as $order) {
            self::fire(Send::showSingleOrderList($sender, "Order No #" . $order->orderId, $order->orderId), $pagId);
        }
        exit;
    }

    public static function myCart($sender, $pageId)
    {
        $myOrders = Cart::where('sender', $sender)
            ->where('status', 'pending')
            ->get();
        $price = 0;
        $count = 0;

        foreach ($myOrders as $order) {
            if ($order->type == "woo") {
                $woo = new WooProduct($order->productid, $pageId);


                $count++;
                $price += $woo->price;
                self::fire(Send::showSingleCart($sender, $woo->name . " (Price :" . Data::getUnit($pageId) . $woo->price . ")", strip_tags($woo->shortDescription), $woo->image, $order->id), $pageId);

            } else {
                foreach (Products::where('id', $order->productid)->get() as $por) {

                    $count++;
                    $price += $por->price;
                    self::fire(Send::showSingleCart($sender, $por->title, $por->short_description . "\nPrice :" . Data::getUnit($pageId) . $por->price . "\n", url('/uploads') . "/" . $por->image, $order->id), $pageId);
                }
            }

        }
        self::fire(Send::sendText($sender, "Total product : " . $count), $pageId);
        self::fire(Send::sendText($sender, "Total Cost : " . Data::getUnit($pageId) . $price), $pageId);
        if ($count != 0) {
            Send::askForOrder($sender, $pageId);
        }

    }

    public static function showFeaturedProducts($sender, $pageId)
    {
        if (Products::where('featured', 'yes')->where('status', 'published')->where('pageId', $pageId)->count() == 0) {
            self::fire(Send::sendText($sender, "Sorry , couldn't find featured products"), $pageId);

            exit;
        }
        $d = Products::where('featured', "yes")
            ->where('status', 'published')
            ->get();
        $products = [];

        foreach ($d->chunk(10) as $i => $chunk) {
            foreach ($chunk as $pro) {
                $btnOrder = [
                    "type" => "postback",
                    "payload" => "cart_" . $sender . "_" . $pro->id . "_" . "babsha",
                    "title" => "Add to cart"
                ];


                $btnDetails = [
                    "type" => "postback",
                    "payload" => "details_" . $pro->id,
                    "title" => "Product Details"
                ];

                $btnBack = [
                    "type" => "postback",
                    "payload" => "view_products",
                    "title" => "Back"
                ];
                array_push($products, Send::elements($pro->title . " (" . Data::getUnit($pageId) . $pro->price . ")", $pro->short_description, url('/uploads') . "/" . $pro->image, [$btnOrder, $btnDetails, $btnBack]));
            }
            $jsonData = Send::item($sender, $products);
            self::fire($jsonData, $pageId);
            $products = [];

        }
    }


}
