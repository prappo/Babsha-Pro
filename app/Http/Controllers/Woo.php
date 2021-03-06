<?php

namespace App\Http\Controllers;

use App\FacebookPages;
use Automattic\WooCommerce\Client;
use Illuminate\Http\Request;
use App\Settings;
use App\Http\Requests;
use Illuminate\Support\Facades\Auth;

class Woo extends Controller
{
    public $name;
    public $shortDescription;
    public $description;
    public $image;
    public $price;
    public $category;
    public $url;


    public function __construct($id)
    {
        $userId = Auth::user()->id;
        $woo = new Client(Settings::where('userId',$userId)->value('wpUrl'),
            Settings::where('userId',$userId)->value('wooConsumerKey'),
            Settings::where('userId',$userId)->value('wooConsumerSecret'),
            [
                'wp_api' => true,
                'version' => 'wc/v1',
            ]);

        $product = $woo->get('products/' . $id);
        $this->name = $product['name'];
        $this->shortDescription = $product['short_description'];
        $this->description = $product['description'];
        $this->image = $product['images'][0]['src'];
        $this->price = $product['price'];
        $this->category = $product['categories'][0]['name'];
        $this->url = $product['permalink'];


    }
}
