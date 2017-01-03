<?php

namespace App\Http\Controllers;

use App\Cart;
use App\Customers;
use App\Orders;
use App\Payments;
use App\Products;
use Illuminate\Http\Request;

use App\Http\Requests;
use Facebook\HttpClients\FacebookGuzzleHttpClient;
use GuzzleHttp\Client;


use PayPal\Api\Amount;
use PayPal\Api\Details;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Transaction;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Exception\PayPalConnectionException;
use PayPal\Rest\ApiContext;
use ResultPrinter;

class PayPalController extends Controller
{
    /**
     *
     * request for payment
     *
     */


    public function paymentRequest($userID)
    {
        if(Settings::get('paypalClientId') == "" || Settings::get('paypalClientSecret') == ""){
            return "PayPal not available . Please contact admin";
        }
        $accountToken = $_GET['account_linking_token'];
        $redirect_uri = $_GET['redirect_uri'];
        $orderNumber = uniqid();
        $apiContext = new ApiContext(new OAuthTokenCredential(Settings::get('paypalClientId'), Settings::get('paypalClientSecret')));
        $payer = new Payer();

        $payer->setPaymentMethod("paypal");

        $allItems = array();
        $items = array();
        $index = 0;
        $orders = Cart::where('sender', $userID)
            ->distinct()->get(['productid','price','type']);
        $subTotal = 0;



        ////////////////
        foreach ($orders as $item) {
            $items[$index] = new Item();
            $quantity = Cart::where('sender', $userID)->where('productid', $item->productid)->count();
            if($item->type == "woo"){
            $woo = new WooProduct($item->productid);
                $productPrice = $item->price;
                $items[$index]->setName($woo->name)
                    ->setCurrency(Data::getCurrency())
                    ->setQuantity($quantity)
                    ->setSku($item->productid)
                    ->setPrice($productPrice);

                $subTotal = (int)$subTotal + ((int)$productPrice * $quantity);
                array_push($allItems, $items[$index]);
                $index++;
            }else{
                $productPrice = Products::where('id', $item->productid)->value('price');
                $items[$index]->setName(Products::where('id', $item->productid)->value('title'))
                    ->setCurrency(Data::getCurrency())
                    ->setQuantity($quantity)
                    ->setSku($item->productid)
                    ->setPrice($productPrice);

                $subTotal = (int)$subTotal + ((int)$productPrice * $quantity);
                array_push($allItems, $items[$index]);
                $index++;
            }


        }

        $total = (int)$subTotal + (int)Data::getTax() + (int)Data::getShippingCost();

        $itemList = new ItemList();

        $itemList->setItems($allItems);


        $details = new Details();
        $details->setShipping(Data::getShippingCost())
            ->setTax(Data::getTax())
            ->setSubtotal($subTotal);


        $amount = new Amount();
        $amount->setCurrency("USD")
            ->setTotal($total)
            ->setDetails($details);


        $transaction = new Transaction();
        $transaction->setAmount($amount)
            ->setItemList($itemList)
            ->setDescription("Payment For Products")
            ->setInvoiceNumber($orderNumber);


        $baseUrl = url('/');
        $redirectUrls = new RedirectUrls();
        $redirectUrls->setReturnUrl("$baseUrl/success/payment/" . $userID . "?accountToken=" . $accountToken . "&redirectUri=" . $redirect_uri . "&total=" . $total . "&orderNumber=" . $orderNumber)
            ->setCancelUrl("$baseUrl/cancel/payment");


        $payment = new Payment();
        $payment->setIntent("sale")
            ->setPayer($payer)
            ->setRedirectUrls($redirectUrls)
            ->setTransactions(array($transaction));


        $request = clone $payment;


        try {
            $payment->create($apiContext);
        } catch (PayPalConnectionException $ex) {
            return $ex->getData();
            exit(1);
        }

        $approvalUrl = $payment->getApprovalLink();

        foreach ($payment->getLinks() as $link) {
            if ($link->getRel() == "approval_url") {
                $redirectUrls = $link->getHref();
            }
        }

        return redirect($redirectUrls);
    }

    /**
     *
     * after successful payment
     *
     */
    public function success($userId)
    {
        $accountToken = $_GET['accountToken'];
        $redirectUri = $_GET['redirectUri'];
        $paymentId = $_GET['paymentId'];
        $token = $_GET['token'];
        $PayerID = $_GET['PayerID'];
        $orderNumber = $_GET['orderNumber'];
        $total = $_GET['total'];

        $cart = Cart::where('sender', $userId)->get();
        foreach ($cart as $c) {
            $newOrder = new \App\Orders();
            $newOrder->sender = $userId;
            $newOrder->orderId = $orderNumber;
            $newOrder->productid = $c->productid;
            $newOrder->status = "pending";
            $newOrder->price = $c->price;
            $newOrder->method = "PaYpal";
            $newOrder->payment = "Paid";
            $newOrder->type = $c->type;
            $newOrder->save();
        }
        try {
            Cart::where('sender', $userId)->delete();
        } catch (\Exception $e) {
            Run::fire(Send::sendText($userId, "Something went wrong we couldn't remove products form cart"));
        }
        try{

            $paymentInfo = new Payments();
            $paymentInfo->sender = $userId;
            $paymentInfo->orderId = $orderNumber;
            $paymentInfo->paymentId = $paymentId;
            $paymentInfo->token = $token;
            $paymentInfo->payerId = $PayerID;
            $paymentInfo->amount = $total;
            $paymentInfo->save();
        }
        catch (\Exception $e){
            Run::fire(Send::sendText($userId,"Something went wrong . We couldn't store your payment information ."));
        }
        Run::fire(Send::sendText($userId,'Payment successfully received'));
        Run::fire(Send::sendText($userId, Data::getAfterOrderMsg()));
        Run::fire(Send::sendText($userId, "Your Order ID #" . $orderNumber));
        Run::fire(Send::sendText($userId, "Your Payment ID " . $paymentId));
        Run::fire(Send::sendText($userId, "Your Payer ID " . $PayerID));
        Run::fire(Send::updateInfo($userId));
        Data::notify(Customers::where('fbId',$userId)->value('name'). " placed the order and paid via PayPal");
        return view('paymentsuccess', compact('accountToken', 'redirectUri'));
    }

    /**
     * after payment cancel
     *
     */
    public function cancel()
    {
//        return $request->token;
        return view('paymentcancel');

    }

    public function paypalHistory(){
        $data = Payments::all();
        return view('paypalhistory',compact('data'));
    }
}
