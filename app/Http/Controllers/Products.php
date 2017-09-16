<?php

namespace App\Http\Controllers;

use App\Catagories;
use Facebook\Exceptions\FacebookAuthenticationException;
use Facebook\Exceptions\FacebookSDKException;
use Facebook\Facebook;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Auth;

class Products extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function addProductIndex()
    {

        $woo = new \Automattic\WooCommerce\Client(
            Settings::get('wpUrl'),
            Settings::get('wooConsumerKey'),
            Settings::get('wooConsumerSecret'),
            [
                'wp_api' => true,
                'version' => 'wc/v1',
            ]
        );
        try {
            $wooCategories = $woo->get('products/categories');
        } catch (\Exception $e) {
            $wooCategories = "none";
        }

        $categories = Catagories::where('userId', Auth::user()->id)->get();

        return view('addproduct', compact('categories', 'wooCategories'));
    }

    /**
     * @param Request $re
     * @return string
     */
    public function addProduct(Request $re)
    {


        $title = $re->title;
        $shortDescription = $re->shortDescription;
        $longDescription = $re->longDescription;
        $image = $re->image;
        $price = $re->price;
        $category = $re->category;
        $featured = $re->featured;

        if ($title == "" || $shortDescription == "" || $longDescription == "" || $image == "") {
            return "Please fill required field/fields";
        }
        if (ctype_digit($category)) {
            if ($re->postWp == "no") {
                return "You can't select WooCommerce category because you are not creating WooCommerce product . Please select without WooCommerce category";
            }
        }


        if ($re->postFb == 'yes') {
            $pageToken = Data::getToken($re->pageId);
            $fb = new Facebook([
                'app_id' => Data::getAppId(),
                'app_secret' => Data::getAppSec(),
                'default_graph_version' => 'v2.6',
            ]);
            $data = $title . "\n" . $shortDescription . "\n" . $longDescription . "\n" . Data::getUnit() . $price;
            $content = [
                "message" => $data,
                "source" => $fb->fileToUpload(public_path() . "/uploads/" . $image)

            ];

            try {
                $post = $fb->post('/me' . "/photos", $content, $pageToken);
                $id = $post->getDecodedBody();
                $fbPostId = $id['id'];
                $product = new \App\Products();
                $product->fbId = $fbPostId;
                $product->title = $title;
                $product->short_description = $shortDescription;
                $product->long_description = $longDescription;
                $product->image = $image;
                $product->price = $price;
                $product->category = $category;
                $product->status = "published";
                $product->featured = $featured;
                $product->userId = Auth::user()->id;
                $product->pageId = $re->pageId;
                $product->save();
                Customer::sendProductNotification($re->title, $re->shortDescription, $re->image, Data::getUnit() . $re->price, $re->pageId);

                /* store product to database */


                return "success";
            } catch (FacebookAuthenticationException $e) {
                return $e->getMessage();
            } catch (FacebookSDKException $d) {
                return $d->getMessage();
            }
        } elseif ($re->postWp == "yes") {

            /* WooCommerce product add*/

            $woo = new \Automattic\WooCommerce\Client(
                Settings::get('wpUrl'),
                Settings::get('wooConsumerKey'),
                Settings::get('wooConsumerSecret'),
                [
                    'wp_api' => true,
                    'version' => 'wc/v1',
                ]
            );
            $data = [
                'name' => $title,
                'type' => 'simple',
                'regular_price' => $price,
                'description' => $longDescription,
                'short_description' => $shortDescription,
                'categories' => [
                    [
                        'name' => "Clothing"
                    ]
                ],
                'images' => [
                    [
                        'src' => url('/uploads') . "/" . $image,
                        'position' => 0
                    ]
                ]
            ];
            try {
                $woo->post('products', $data);
                return "success";

            } catch (\Exception $e) {
                return $e->getMessage();
            }
        } else {
            $product = new \App\Products();
            $product->fbId = "notinfb";
            $product->title = $title;
            $product->short_description = $shortDescription;
            $product->long_description = $longDescription;
            $product->image = $image;
            $product->price = $price;
            $product->category = $category;
            $product->status = "published";
            $product->featured = $featured;
            $product->userId = Auth::user()->id;
            $product->pageId = $re->pageId;
            $product->save();
            Customer::sendProductNotification($re->title, $re->shortDescription, $re->image, Data::getUnit($re->pageId) . $re->price, $re->pageId);
            return "success";
        }


    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showProducts()
    {
        $data = \App\Products::where('userId', Auth::user()->id)->paginate(10);
        return view('showproducts', compact('data'));
    }

    /**
     * @param Request $re
     * @return string
     */
    public function editProducts(Request $re)
    {
//        $fbId = \App\Products::where('id',$re->id)->value('fbId');
//        $fb = new Facebook([
//            'app_id' => Data::getAppId(),
//            'app_secret' => Data::getAppSec(),
//            'default_graph_version' => 'v2.6',
//        ]);
//
//        $data = $re->title."\n".$re->shortDescription."\n".$re->longDescription."\n".Data::getUnit().$re->price;
//        try {
//            $fb->post($fbId,['message'=>$data],Data::getToken());
//            \App\Products::where('id', $re->id)->update([
//                'title' => $re->title,
//                'short_description' => $re->shortDescription,
//                'long_description' => $re->longDescription,
//                'image' => $re->image,
//                'price' => $re->price,
//                'category' => $re->category,
//                'featured' => $re->featured
//            ]);
//
//            return 'success';
//        } catch (\Exception $e) {
//            \App\Products::where('id', $re->id)->update([
//                'title' => $re->title,
//                'short_description' => $re->shortDescription,
//                'long_description' => $re->longDescription,
//                'image' => $re->image,
//                'price' => $re->price,
//                'category' => $re->category,
//                'featured' => $re->featured
//            ]);
//
//            return 'success';
//        }
        try {
            \App\Products::where('id', $re->id)->update([
                'title' => $re->title,
                'short_description' => $re->shortDescription,
                'long_description' => $re->longDescription,
                'image' => $re->image,
                'price' => $re->price,
                'category' => $re->category,
                'featured' => $re->featured
            ]);
            return "success";
        } catch (\Exception $exception) {
            return $exception->getMessage();
        }
    }

    /**
     * @param Request $re
     * @return string
     */
    public function deleteProducts(Request $re)
    {
        $fbId = \App\Products::where('id', $re->id)->value('fbId');
        if ($fbId == "notinfb") {
            \App\Products::where('id', $re->id)->delete();
            return "success";
        } else {
            $fb = new Facebook([
                'app_id' => Data::getAppId(),
                'app_secret' => Data::getAppSec(),
                'default_graph_version' => 'v2.6',
            ]);
            try {
                \App\Products::where('id', $re->id)->delete();
                $fb->delete($fbId, [], Data::getToken());
                return 'success';
            } catch (\Exception $e) {
                return $e->getMessage();
            }
        }

    }

    /**
     * @param Request $re
     * @return string
     */
    public function statusUpdate(Request $re)
    {
        $status = $re->status;
        if ($status == 'published') {
            $status = 'unpublished';
        } else {
            $status = 'published';
        }
        try {
            \App\Products::where('id', $re->id)->update(['status' => $status]);
            return "success";
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function updateProduct($id)
    {
        $data = \App\Products::where('id', $id)->first();
        $categories = Catagories::all();
        $id = $data->id;
        $title = $data->title;
        $shortDescription = $data->short_description;
        $longDescription = $data->long_description;
        $image = $data->image;
        $price = $data->price;
        $category = $data->category;
        $featured = $data->featured;

        return view('updateproduct', compact('id', 'title', 'shortDescription', 'longDescription', 'image', 'price', 'category', 'categories', 'featured'));
    }


}
