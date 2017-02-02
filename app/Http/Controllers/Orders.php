<?php

namespace App\Http\Controllers;

use App\Customers;
use App\FacebookPages;
use App\Income;
use App\Products;
use Illuminate\Http\Request;

use App\Http\Requests;

class Orders extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $orders = \App\Orders::distinct()->where('status', 'pending')->get(['sender', 'productid']);
        $orderCount = \App\Orders::where('status', 'pending')->count();
        return view('orders', compact('orders', 'orderCount'));
    }

    public function orderList()
    {
        $orders = \App\Orders::distinct()->where('status', 'pending')->get(['orderId', 'created_at', 'sender']);
        return view('orderlist', compact('orders'));
    }

    public function viewOrder($orderId)
    {
        $orderNumber = $orderId;
        return view('singleorder', compact('orderNumber'));
    }



    /**
     * @param Request $re
     * @return string
     */
    public function sendMessage(Request $re)
    {
        $userId = $re->userId;
        $msg = $re->msg;
        try {
            Run::fire(Send::sendText($userId, $msg),$re->pageId);

        } catch (\Exception $e) {
            return "error";
        }
    }

    /**
     * @param Request $re
     * @return string
     */
    public function removeProduct(Request $re)
    {

        $productId = $re->productid;
        $userId = $re->userId;
        $orderId = $re->orderId;

        try {

            \App\Orders::where('productid', $productId)
                ->where('sender', $userId)
                ->where('orderId',$orderId)
                ->delete();

            return "success";
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function deleteOrder(Request $request){
        $orderId = $request->orderId;
        try{
            \App\Orders::where('orderId',$orderId)->delete();
            return "success";
        }
        catch(\Exception $e){
            return $e->getMessage();
        }
    }

    public function cancelOrder(Request $request){
        try{
            \App\Orders::where('orderId',$request->orderId)->update([
                'status'=>'canceled',
            ]);
            return "success";
        }
        catch (\Exception $e){
            return $e->getMessage();
        }
    }

    /**
     * @param Request $re
     * @return string
     */
    public function requestUpdateAddress(Request $re)
    {
        $userId = $re->userId;
        try {
            Run::fire(Send::updateInfo($userId),$re->pageId);
            return "success";
        } catch (\Exception $e) {
            return "Something went wrong please try again later";
        }
    }

    /**
     * @param Request $re
     * @return string
     */
    public function orderConfirm(Request $re)
    {

        $orderId = $re->orderId;
        $sender = "";
        $orders = \App\Orders::where('status', 'pending')->where('orderId',$orderId)->distinct()->get(['productid','sender','type']);
//        $orders = \App\Orders::where('status', 'pending')->where('orderId',$orderId)->get();
        $items = array();
        $subTotal = 0;
        $wooCount = 0;
        $pCount = 0;
        $pageId = $re->pageId;
        foreach ($orders as $order) {
            $sender = $order->sender;
            $quantity = \App\Orders::where('status', 'pending')->where('sender', $sender)->where('productid', $order->productid)->count();
            if($order->type == "woo"){
                $wooCount++;
                $wooProduct = new Woo($order->productid);
                array_push($items, [
                    'title' => $wooProduct->name,
                    'subtitle' => "Unit price : (" . Data::getUnit($re->pageId) . $wooProduct->price . ") \n" .  $wooProduct->shortDescription,
                    'quantity' => $quantity,
                    'price' => $wooProduct->price * $quantity,
                    'image_url' => $wooProduct->image
                ]);
                $subTotal = $subTotal + ($wooProduct->price * $quantity);

            }else{
                $pCount++;
                array_push($items, [
                    'title' => Products::where('id', $order->productid)->value('title'),
                    'subtitle' => "Unit price : (" . Data::getUnit($pageId) . Products::where('id', $order->productid)->value('price') . ") \n" . Products::where('id', $order->productid)->value('short_description'),
                    'quantity' => $quantity,
                    'price' => Products::where('id', $order->productid)->value('price') * $quantity,
                    'image_url' => url('/uploads') . "/" . Products::where('id', $order->productid)->value('image')
                ]);
                $subTotal = $subTotal + (Products::where('id', $order->productid)->value('price') * $quantity);
            }



        }
//        return "Total woo product is ".$wooCount. " and other product : ".$pCount."And order ID $orderId";


        if(empty($items)){
            return "Cart is emptry";
        }
        $userId = $sender;
        $total = $subTotal + Data::getTax($pageId) + Data::getShippingCost($pageId);
        Run::fire(Send::receiptList($userId,Customers::where('fbId',$userId)->value('name'),$orderId,$items,$subTotal,$total,\App\Orders::where('orderId',$orderId)->value('method'),$pageId),$pageId);

        try {

            \App\Orders::where('orderId', $orderId)
                ->where('sender', $userId)
                ->update(
                    [
                        'status' => 'done',
                        'payment'=>'Paid',
                        'orderId' => $orderId
                    ]
                );
            $income = new Income();
            $income->money = (int)$subTotal;
            $income->orderid = $orderId;
            $income->userId = FacebookPages::where('pageId',$pageId)->value('userId');
            $income->save();
            return "success";
        } catch (\Exception $e) {
            return $e->getMessage();
        }


    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function orderHistory()
    {
        $orders = \App\Orders::select('orderId')->distinct()->get();
        return view('orderhistory', compact('orders'));
    }

    public function earningHistory()
    {
        $data = Income::all();
        return view('earninghistory', compact('data'));
    }

}
