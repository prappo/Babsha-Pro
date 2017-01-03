<?php

namespace App\Http\Controllers;

use Automattic\WooCommerce\Client;
use Illuminate\Http\Request;

use App\Http\Requests;

class WooProduct extends Controller
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
        $woo = new Client(Settings::get('wpUrl'),
            Settings::get('wooConsumerKey'),
            Settings::get('wooConsumerSecret'),
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
