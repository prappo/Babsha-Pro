<?php

namespace App\Http\Controllers;

use App\Cart;
use App\Catagories;
use App\Customers;
//use App\Orders;
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
        $sender = $input['entry'][0]['messaging'][0]['sender']['id'];
        $pageId = $input['entry'][0]['messaging'][0]['recipient']['id'];
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
                self::fire(Send::sendText($sender, "Thank you for updating your information"),$pageId);
            } elseif ($input['entry'][0]['messaging'][0]['account_linking']['authorization_code'] == "7331") {
//                self::fire(Send::sendText($sender, "Payment successfully received"));
            }

            exit;
        }


        $lang = 'en';
        $translatedText = $message;
        /* blocking bot for user */
        if (Customers::where('fbId', $sender)->exists()) {
            $botStatus = Customers::where('fbId', $sender)->value('bot');
            if ($botStatus == 'no') {
                exit;
            }
        }

        if ($location != "nothing") {
            $lat = $input['entry'][0]['messaging'][0]['message']['attachments'][0]['payload']['coordinates']['lat'];
            $long = $input['entry'][0]['messaging'][0]['message']['attachments'][0]['payload']['coordinates']['long'];
            $coordinates = $lat . "," . $long;
            $mapJson = file_get_contents("http://maps.googleapis.com/maps/api/geocode/json?latlng=" . $coordinates);
            $data = json_decode($mapJson);
            $address = $data->results[0]->address_components[0]->long_name . ", " . $data->results[0]->address_components[1]->long_name . ", " . $data->results[0]->address_components[2]->long_name . ", " . $data->results[0]->address_components[3]->long_name . ", " . $data->results[0]->address_components[4]->long_name . ", " . $data->results[0]->address_components[5]->long_name . ", " . $data->results[0]->address_components[6]->long_name;
            try {
                Customers::where('fbId', $sender)->update([
                    'coordinates' => $coordinates,
                    'address' => $address
                ]);
                self::fire(Send::sendText($sender, "Thank you for updating shipping address"),$pageId);
                exit;
            } catch (\Exception $e) {
                self::fire(Send::sendText($sender, "Something went wrong . We couldn't store your location"),$pageId);
                exit;
            }


        }

        /*
         * Checking user if already exist
         * */
        try {
            if (Customers::where('fbId', $sender)->exists()) {

                $botStatus = Customers::where('fbId', $sender)->value('bot');
                if ($botStatus == 'no') {
                    exit;
                }
                $customer = new Customers();
                $msg = Data::translate("Welcome back ", $sender) . $customer->name . "\n" . Data::translate(" Check out our menu . If you need more help please type help and send us", $sender);

            } else {
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
                $customer->save();
                $msg = "Hello " . $user->first_name . "\nCheck out our menu . If you need more help please type 'help'";
            }
        } catch (\Exception $e) {
        }

        $arr = ["hi", "Hi", "hello", "Hello"];
        $optArr = ['menu', 'products', 'contact', 'email', 'my account'];
        if (!empty($input['entry'][0]['messaging'][0]['message'])) {

            if ($message != "nothing") {


                /*
                 * find if existed in array
                 *
                 * */

                if (in_array($message, $arr)) {


                    self::fire(Send::sendMessage($sender, $msg),$pageId);
                } elseif (Bot::checkPer($message) >= 65) {
                    self::fire(Send::sendMessage($sender, Bot::check($message)),$pageId);
                    exit;
                } elseif (in_array($translatedText, $arr)) {
                    $user = Receive::getProfile($sender, Data::getToken($pageId));
                    if (Customers::where('fbId', $sender)->exists()) {
                        $botStatus = Customers::where('fbId', $sender)->value('bot');
                        if ($botStatus == 'no') {
                            exit;
                        }
                        $msg = "Welcome back " . $user->first_name . "\n" . " Check out our menu . If you need more help please type help and send us";

                    } else {
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
                        $customer->save();
                        $msg = "Hello " . $user->first_name . "\nCheck out our menu . If you need more help please type 'help'";
                    }

                    self::fire(Send::sendText($sender, $msg),$pageId);
                } elseif (Data::similar(strtolower($message), "help") >= 70) {
                    self::fire(Send::sendText($sender, Send::helpText()),$pageId);
                    exit;
                } elseif (Data::similar(strtolower($message), "my cart") >= 70) {
                    self::myCart($sender);
                    exit;
                } elseif (Data::similar(strtolower($message), "my orders") >= 70) {
                    self::myOrders($sender);
                    exit;
                } elseif (Data::similar(strtolower($message), "ordered item list") >= 70) {
                    self::myOrdersItem($sender);
                    exit;
                } elseif (Data::similar(strtolower($message), "menu") >= 70) {
                    self::fire(self::getMenu($sender, $lang),$pageId);
                    exit;
                } elseif (Data::similar(strtolower($message), "contact") >= 70) {
                    self::fire(self::contactOptions($sender),$pageId);
                    exit;
                } elseif (Data::similar(strtolower($message), "account") >= 70) {
                    self::fire(self::showProfile($sender,$pageId),$pageId);
                    exit;
                } elseif (Data::similar(strtolower($message), "products") >= 70) {
                    self::fire(Send::sendText($sender, "Select a category"),$pageId);
                    $json = self::getCategories($sender);
                    self::fire($json,$pageId);
                    exit;
                } elseif (Data::similar(strtolower($message), "featured products") >= 70) {
                    self::showFeaturedProducts($sender);
                    exit;
                } elseif (Data::similar(strtolower($message), "more options") >= 70) {
                    $jsonData = self::moreOptions($sender);
                    self::fire($jsonData,$pageId);
                    exit;
                } elseif (Data::similar(strtolower($message), "checkout") >= 70) {
                    Send::paymentMethod($sender);
                    exit;
                } elseif (Data::similar(strtolower($message), "email") >= 70) {
                    $jsonData = Send::sendMessage($sender, Data::getEmail());
                    self::fire($jsonData,$pageId);
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
                        self::fire(Send::sendText($sender, Data::translate($msgArr[$index], $lang)),$pageId);


                    }

                }
            }

        }


        if ($catPostBack == "nothing") {

            if ($postback == "nothing") {
                $jsonData = self::getMenu($sender, $lang);
            } else {
                if ($postback == "contact_us") {

                    $jsonData = self::contactOptions($sender);
                    self::fire($jsonData,$pageId);
                    exit;
                } elseif ($postback == "menu") {
                    $jsonData = self::getMenu($sender, $lang);
                } elseif ($postback == "me") {
                    $jsonData = self::showProfile($sender,$pageId);
                } elseif ($postback == "view_products") {
                    self::fire(Send::sendText($sender, "Please select a category"),$pageId);
                    $jsonData = self::getCategories($sender);
                    self::fire($jsonData,$pageId);
                    exit;
                } elseif ($postback == "help") {
                    self::fire(Send::sendText($sender, Send::helpText()),$pageId);
                    exit;
                } elseif ($postback == "more_options") {

                    $jsonData = self::moreOptions($sender);
                    self::fire($jsonData,$pageId);
                    exit;

                } elseif ($postback == "mail_us") {
                    $jsonData = Send::sendText($sender, Data::getEmail());
                    self::fire($jsonData,$pageId);
                    exit;
                } elseif ($postback == "subscribe") {
                    self::fire(Send::sendText($sender, Customer::subscribe($sender)),$pageId);
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
                    if(\App\Orders::where('status','pending')->where('sender',$sender)->count() == 0){
                        self::fire(Send::sendText($sender,"You don't have any order"),$pageId);
                        exit;
                    }
                    $orders = \App\Orders::distinct()->where('status', 'pending')->where('sender', $sender)->get(['orderId', 'created_at', 'sender']);
                    foreach ($orders as $order) {
                        self::fire(Send::showSingleOrderList($sender, "Order No #" . $order->orderId, $order->orderId),$pageId);
                    }
                    exit;

                } elseif ($postback == "my_cart") {
                    self::myCart($sender);

                } elseif ($postback == "checkout") {
                    Send::paymentMethod($sender);

                } elseif ($postback == "paypal") {
                    Send::placeOrderViaPaypal($sender);
                } elseif ($postback == "paymentMethod") {
                    self::fire(Send::paymentMethod($sender),$pageId);
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
                        self::fire(Send::sendText($sender, "Something went wrong we couldn't remove products form cart"),$pageId);
                    }
                    self::fire(Send::sendText($sender, Data::getAfterOrderMsg()),$pageId);
                    self::fire(Send::sendText($sender, "Your Order ID #" . $orderID),$pageId);
                    self::fire(Send::updateInfo($sender),$pageId);

                    Data::notify(Customers::where('fbId', $sender)->value('name') . " Placed and order with Cash on Delivery Method");

                } elseif ($postback == "featured") {
//                    show featured products
                    self::showFeaturedProducts($sender);

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
                                self::fire(Send::sendText($sender, "Product don't exists"),$pageId);
                                exit;
                            }
                        }
                        if ($type == "woo") {
                            $wooProduct = new WooProduct($productId);
                            $cart = new Cart();
                            $cart->orderId = $orderId;
                            $cart->sender = $sender;
                            $cart->productid = $productId;
                            $cart->status = "pending";
                            $cart->price = $wooProduct->price;
                            $cart->type = $type;
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
                            $cart->save();
                        }

                        self::fire(Send::sendText($sender, "Added to cart"),$pageId);
                        self::fire(Send::sendText($sender, "You can check your cart by typing 'my cart'"),$pageId);
                        self::fire(Send::sendText($sender, "For check out type 'checkout'"),$pageId);
                        Send::continueShoppin($sender);
                        exit;

                    } elseif ($prefex == "order") {


                    } elseif ($prefex == "share") {
                        $sharedProduct = Products::where('id', $postfex)->where('status', 'published')->first();
                        self::fire(Send::shareSingleItem($sender, $sharedProduct->title, $sharedProduct->short_description, $sharedProduct->image),$pageId);
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
                            self::fire(Send::sendImage($sender, url('/uploads') . "/" . $d->image),$pageId);

                            self::fire(Send::sendText($sender, $d->title),$pageId);
                            self::fire(Send::sendText($sender, Data::getUnit() . $d->price),$pageId);
                            self::fire(Send::sendText($sender, $d->short_description),$pageId);
                            self::fire(Send::sendText($sender, $d->long_description),$pageId);
                            $products = [];

                            $btnOrder = [
                                "type" => "postback",
                                "payload" => "cart_" . $sender . "_" . $d->id . "_" . "babsha",
                                "title" => Data::translate("Add to cart", Data::getUserLang($sender))
                            ];


                            $btnBack = [
                                "type" => "postback",
                                "payload" => "view_products",
                                "title" => Data::translate("Continue Shopping", Data::getUserLang($sender))
                            ];
                            $cartCount = Cart::where('sender', $sender)->count();

                            $btnBuy = [
                                "type" => "postback",
                                "payload" => "my_cart",
                                "title" => Data::translate("My Cart " . "(" . $cartCount . ")", Data::getUserLang($sender))
                            ];

                            array_push($products, Send::elements(Data::translate("Select an option", Data::getUserLang($sender)) . "", "", "", [$btnOrder, $btnBuy, $btnBack]));

                            $jsonData = Send::item($sender, $products);
                            self::fire($jsonData,$pageId);
                            self::fire(Send::buttonSingle($sender, $d->id),$pageId);

                            exit;
                        } catch (\Exception $e) {

                            /**
                             * if products can't find in database then try
                             * to find if it's exists in WooCommerce
                             */

                            $products = [];
                            $woo = new WooController();
                            $product = $woo->getProductById($postfex);

                            self::fire(Send::sendImage($sender, $product['images'][0]['src']),$pageId);


                            self::fire(Send::sendText($sender, $product['name']),$pageId);
                            self::fire(Send::sendText($sender, Data::getUnit() . $product['price']),$pageId);
                            self::fire(Send::sendText($sender, strip_tags($product['description'])),$pageId);

                            /*
                             * request to client for place an order
                             *
                             * */
                            $btnOrder = [
                                "type" => "postback",
                                "payload" => "cart_" . $sender . "_" . $product['id'] . "_" . "woo",
                                "title" => Data::translate("Yes", Data::getUserLang($sender))
                            ];


                            $btnBack = [
                                "type" => "postback",
                                "payload" => "view_products",
                                "title" => Data::translate("No thanks", Data::getUserLang($sender))
                            ];
                            array_push($products, Send::elements(Data::translate("Do you want to add to cart ?", Data::getUserLang($sender)) . "", "", "", [$btnOrder, $btnBack]));

                            $jsonData = Send::item($sender, $products);
                            self::fire($jsonData,$pageId);
                            exit;

                        }
                    } elseif ($prefex == "cancel") {
                        /*
                         * cancel order
                         *
                         * */

                        try {
                            \App\Orders::where('id', $postfex)->delete();
                            self::fire(Send::sendText($sender, "Removed order"),$pageId);
                        } catch (\Exception $e) {
                            self::fire(Send::sendText($sender, "Something went wrong , for this moment we can't cancel your order please try again later"),$pageId);
                        }
                    } elseif ($prefex == "cancelcart") {
                        /*
                         * cancel order
                         *
                         * */

                        try {
                            Cart::where('id', $postfex)->delete();
                            self::fire(Send::sendText($sender, "Removed from cart"),$pageId);
                            Send::continueShoppin($sender);
                        } catch (\Exception $e) {
                            self::fire(Send::sendText($sender, "Something went wrong , for this moment we can't cancel your order please try again later"),$pageId);
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
                    $wooCommerce = new WooController();
                    if ($wooCommerce->catExists($value)) {
                        if ($wooCommerce->getProducts($value) != "none") {
                            $products = [];
                            $productsMainArr = $wooCommerce->getProducts($value);
                            foreach (array_chunk($productsMainArr, 10) as $productsArr) {
                                foreach ($productsArr as $pro) {
                                    $btnOrder = [
                                        "type" => "postback",
                                        "payload" => "cart_" . $sender . "_" . $pro['id'] . "_" . "woo",
                                        "title" => Data::translate("Add to cart", Data::getUserLang($sender))
                                    ];
                                    $btnDetails = [
                                        "type" => "postback",
                                        "payload" => "details_" . $pro['id'],
                                        "title" => Data::translate("Product Details", Data::getUserLang($sender))
                                    ];

                                    $btnBack = [
                                        "type" => "postback",
                                        "payload" => "view_products",
                                        "title" => Data::translate("Back", Data::getUserLang($sender))
                                    ];
                                    array_push($products, Send::elements($pro['name'] . " (" . Data::getUnit() . $pro['price'] . ")", Data::translate(strip_tags($pro['short_description']), Data::getUserLang($sender)), $pro['images'][0]['src'], [$btnOrder, $btnDetails, $btnBack]));
                                }
                                $jsonData = Send::item($sender, $products);
                                self::fire($jsonData,$pageId);
                            }

                            exit;


                        } else {
                            self::fire(Send::sendText($sender, Data::translate("Sorry ! Products could not found \nPlease select another category", Data::getUserLang($sender))),$pageId);
                            self::fire(self::getCategories($sender),$pageId);
                            exit;

                        }

                    }
                } catch (\Exception $e) {
                }


                if (Products::where('category', $value)
                        ->where('status', 'published')
                        ->count() == 0
                ) {
                    self::fire(Send::sendText($sender, Data::translate("Sorry ! Products could not found \nPlease select another category", Data::getUserLang($sender))),$pageId);
                    self::fire(self::getCategories($sender),$pageId);
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
                                "title" => Data::translate("Add to cart", Data::getUserLang($sender))
                            ];
                            $btnDetails = [
                                "type" => "postback",
                                "payload" => "details_" . $pro->id,
                                "title" => Data::translate("Product Details", Data::getUserLang($sender))
                            ];

                            $btnBack = [
                                "type" => "postback",
                                "payload" => "view_products",
                                "title" => Data::translate("Back", Data::getUserLang($sender))
                            ];
                            array_push($products, Send::elements($pro->title . " (" . Data::getUnit() . $pro->price . ")", Data::translate($pro->short_description, Data::getUserLang($sender)), url('/uploads') . "/" . $pro->image, [$btnOrder, $btnDetails, $btnBack]));
                        }
                        $jsonData = Send::item($sender, $products);
                        self::fire($jsonData,$pageId);
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
    public static function getCategories($sender)
    {
        $menu = [];
        $wooCommerce = new WooController();
        $woo = $wooCommerce->woo;

        $data = Catagories::all();
        foreach ($data as $d) {
            array_push($menu, Send::quickBtn($d->name, "cat_" . $d->name));
        }
        try {
            $categories = $woo->get('products/categories');
            foreach ($categories as $category) {
                array_push($menu, Send::quickBtn($category['name'], "cat_" . $category['id']));
            }
        } catch (\Exception $e) {
        }


        return Send::button($sender, Data::translate("Categories", Data::getUserLang($sender)), $menu);

    }

    /**
     * @param $sender
     * @return string
     */
    public static function getMenu($sender, $lang)
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
                                "title" => Data::getShopTitle(),
                                "image_url" => Data::getLogo(),
                                "subtitle" => Data::translate(Data::getShopSubTitle(), $lang),
                                "buttons" => [
                                    [
                                        "type" => "postback",
                                        "title" => Data::translate("Our products", $lang),
                                        "payload" => "view_products"
                                    ],
                                    [
                                        "type" => "postback",
                                        "title" => Data::translate("More Options", $lang),
                                        "payload" => "more_options"
                                    ],
                                    [
                                        "type" => "postback",
                                        "title" => Data::translate("Contact us", $lang),
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
    public static function moreOptions($sender)
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
                                "title" => Data::getShopTitle(),
                                "image_url" => Data::getLogo(),
                                "subtitle" => Data::getShopSubTitle(),
                                "buttons" => [
                                    [
                                        "type" => "postback",
                                        "title" => Data::translate("My account", Data::getUserLang($sender)),
                                        "payload" => "me"
                                    ],
                                    [
                                        "type" => "postback",
                                        "title" => Data::translate("Featured Items", Data::getUserLang($sender)),
                                        "payload" => "featured"
                                    ],
                                    [
                                        "type" => "postback",
                                        "title" => Data::translate("Back", Data::getUserLang($sender)),
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
    public static function contactOptions($sender)
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
                                "title" => Data::getShopTitle(),
                                "image_url" => Data::getMap(),
                                "subtitle" => Data::translate(Data::getShopSubTitle(), Data::getUserLang($sender)),
                                "buttons" => [
                                    [
                                        "type" => "postback",
                                        "title" => Data::translate("Email", Data::getUserLang($sender)),
                                        "payload" => "mail_us"
                                    ],
                                    [
                                        "type" => "phone_number",
                                        "title" => Data::translate("Call us", Data::getUserLang($sender)),
                                        "payload" => Data::getPhone()
                                    ],
                                    [
                                        "type" => "postback",
                                        "title" => Data::translate("Back", Data::getUserLang($sender)),
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
    public static function fire($jsonData,$pageId)
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
    public static function showProfile($sender,$pageId)
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
                                        "title" => Data::translate("My Orders " . "(" . $orderCount . ")", Data::getUserLang($sender)),
                                        "payload" => "user_orders"
                                    ],
                                    [
                                        "type" => "postback",
                                        "title" => Data::translate("My Cart " . "(" . $cartCount . ")", Data::getUserLang($sender)),
                                        "payload" => "my_cart"
                                    ],
                                    [
                                        "type" => "postback",
                                        "title" => Data::translate("Subscribe / Unsubscribe", Data::getUserLang($sender)),
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
    public static function myOrdersItem($sender)
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
                self::fire(Send::showSingleOrder($sender, $por->title, $por->short_description . "\nPrice :" . Data::getUnit() . $por->price . "\n", url('/uploads') . "/" . $por->image, $order->id));
            }
        }
        self::fire(Send::sendText($sender, "Total Orders : " . $count));
        self::fire(Send::sendText($sender, "Total Cost : " . Data::getUnit() . $price));
    }

    public static function myOrders($sender)
    {
        $orders = \App\Orders::distinct()->where('status', 'pending')->where('sender', $sender)->get(['orderId', 'created_at', 'sender']);
        foreach ($orders as $order) {
            self::fire(Send::showSingleOrderList($sender, "Order No #" . $order->orderId, $order->orderId));
        }
        exit;
    }

    public static function myCart($sender)
    {
        $myOrders = Cart::where('sender', $sender)
            ->where('status', 'pending')
            ->get();
        $price = 0;
        $count = 0;

        foreach ($myOrders as $order) {
            if ($order->type == "woo") {
                $woo = new WooProduct($order->productid);


                $count++;
                $price += $woo->price;
                self::fire(Send::showSingleCart($sender, $woo->name . " (Price :" . Data::getUnit() . $woo->price . ")", strip_tags($woo->shortDescription), $woo->image, $order->id));

            } else {
                foreach (Products::where('id', $order->productid)->get() as $por) {

                    $count++;
                    $price += $por->price;
                    self::fire(Send::showSingleCart($sender, $por->title, $por->short_description . "\nPrice :" . Data::getUnit() . $por->price . "\n", url('/uploads') . "/" . $por->image, $order->id));
                }
            }

        }
        self::fire(Send::sendText($sender, "Total product : " . $count));
        self::fire(Send::sendText($sender, "Total Cost : " . Data::getUnit() . $price));
        if ($count != 0) {
            Send::askForOrder($sender);
        }

    }

    public static function showFeaturedProducts($sender)
    {
        if (Products::where('featured', 'yes')->where('status', 'published')->count() == 0) {
            self::fire(Send::sendText($sender, "Sorry , couldn't find featured products"));

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
                    "title" => Data::translate("Add to cart", Data::getUserLang($sender))
                ];


                $btnDetails = [
                    "type" => "postback",
                    "payload" => "details_" . $pro->id,
                    "title" => Data::translate("Product Details", Data::getUserLang($sender))
                ];

                $btnBack = [
                    "type" => "postback",
                    "payload" => "view_products",
                    "title" => Data::translate("Back", Data::getUserLang($sender))
                ];
                array_push($products, Send::elements($pro->title . " (" . Data::getUnit() . $pro->price . ")", Data::translate($pro->short_description, Data::getUserLang($sender)), url('/uploads') . "/" . $pro->image, [$btnOrder, $btnDetails, $btnBack]));
            }
            $jsonData = Send::item($sender, $products);
            self::fire($jsonData);
            $products = [];

        }
    }


}
