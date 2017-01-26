<?php

namespace App\Http\Controllers;

use App\FacebookPages;
use App\Settings;
use Automattic\WooCommerce\Client;
use Illuminate\Http\Request;

use App\Http\Requests;

class WooController extends Controller
{
    public $woo;

    public function __construct($pageId)
    {
        $userId = FacebookPages::where('pageId',$pageId)->value('userId');
        $this->woo = new Client(Settings::where('userId',$userId)->value('wpUrl'),
            Settings::where('userId',$userId)->value('wooConsumerKey'),
            Settings::where('userId',$userId)->value('wooConsumerSecret'),
            [
                'wp_api' => true,
                'version' => 'wc/v1',
            ]);

    }

    public function catExists($categoryId)
    {
        try {
            $categories = $this->woo->get('products/categories');
            foreach ($categories as $category) {
                if (isset($category['id']))
                    if ($category['id'] == $categoryId)
                        return true;
            }
            return false;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function getProducts($category)
    {
        $productArray = [];
        try {

            $products = $this->woo->get('products', [
                    'per_page' => 100,
                    'category' => $category
                ]
            );
            foreach ($products as $product) {
                array_push($productArray, $product);
            }
            if (!empty($productArray)) {
                return $productArray;
            } else {
                return "none";
            }
        } catch (\Exception $e) {
            return "none";
        }
    }

    public function getProductById($productId)
    {

        try {
            $products = $this->woo->get('products',['per_page' => 100]);
            foreach ($products as $product) {
                if ($product['id'] == $productId) {
                    return $product;
                }
            }
            return "none";
        } catch (\Exception $e) {
            return "none";
        }
    }

    public function viewProducts()
    {
        try {
            $data = $this->woo->get('products', ['per_page' => 100]);

        } catch (\Exception $e) {

            $msg = "WooCommerce settings is not well configured . Please check settings";
            return view('errors.error', compact('msg'));
        }


        return view('wooproducts', compact('data'));
    }

    public function updateProduct($id)
    {


        $product = $this->woo->get('products/' . $id);
        $name = $product['name'];
        $price = $product['price'];
        $short_description = $product['short_description'];
        $description = $product['description'];
        $categories = $this->woo->get('products/categories');
        $category = isset($product['categories'][0]['name']) ? $product['categories'][0]['name'] : "";


        try {
            return view('wooupdateproduct', compact('id', 'name', 'price', 'short_description', 'description', 'categories', 'category'));
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function updateWooProduct(Request $request)
    {
        $data = [
            'name' => $request->name,
            'regular_price' => $request->price,
            'short_description' => $request->short_description,
            'description' => $request->description,
            'categories' => [
                ['id' => $request->category]
            ]
        ];
        try {
            $this->woo->put('products/' . $request->id, $data);
            return "success";
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function deleteProduct(Request $request)
    {
        try {
            $this->woo->delete('products/' . $request->id, ['force' => true]);
            return "success";
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }



    public function viewCategory()
    {
        $data = $this->woo->get('products/categories');
        return view('woocategory', compact('data'));
    }

    public function addCategory(Request $request)
    {
        $data = [
            'name' => $request->name

        ];
        try {
            $this->woo->post('products/categories', $data);
            return "success";
        } catch (\Exception $exception) {
            return $exception->getMessage();
        }
    }


    public function editCategory(Request $request)
    {

        $data = [
            'name' => $request->name
        ];
        try {
            $this->woo->put('products/categories/' . $request->id, $data);
            return "success";
        } catch (\Exception $exception) {
            return $exception->getMessage();
        }
    }

    public function deleteCategory(Request $request)
    {
        try {
            $this->woo->delete('products/categories/' . $request->id, ['force' => true]);
            return "success";
        } catch (\Exception $exception) {
            return $exception->getMessage();
        }
    }
}
