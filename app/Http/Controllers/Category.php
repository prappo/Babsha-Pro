<?php

namespace App\Http\Controllers;

use App\Catagories;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Auth;

class Category extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function addCategoryIndex(){
        return view('addcategory');
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function viewCategoryIndex(){
        $data = Catagories::where('userId',Auth::user()->id)->get();
        return view('category',compact('data'));
    }

    /**
     * @param Request $re
     * @return string
     */
    public function addCategory(Request $re){
        $name = $re->name;
        $wooCommerce = new WooController($re->pageId);
        $woo = $wooCommerce->woo;
        $data = [
            'name' => $name,

        ];
        try{
            if(Catagories::where('userId',Auth::user()->id)->where('pageId',$re->pageId)->count() >= 10){
                return "Sorry sir , you can't add more than 10 categories. All because facebook gui button don't allow more than 10 buttons . But in the next version we will make subcategory system fore more options . thanks for your patience";
            }
            try{
                $woo->post('products/categories',$data);
            }
            catch (\Exception $e){}

            $cat = new Catagories();
            $cat->name = $name;
            $cat->userId = Auth::user()->id;
            $cat->pageId = $re->pageId;
            $cat->save();
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
    public function delete(Request $re){
        $id = $re->id;
        try{
            Catagories::where('id',$id)->delete();
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
    public function edit(Request $re){
        $id = $re->id;
        $name = $re->name;
        try{

            Catagories::where('id',$id)->update(['name'=>$name]);
            return "success";
        }
        catch (\Exception $e){
            return $e->getMessage();
        }
    }

    public function wooAddCategoryIndex()
    {


        return view('wooaddcategory');
    }
}
