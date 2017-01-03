<?php

namespace App\Http\Controllers;

use App\Catagories;
use App\Products;
use Illuminate\Http\Request;

use App\Http\Requests;

class Hook extends Controller
{
    /**
     * Main entry point
     *
     * @param Request $re
     * @return mixed
     */
    public function index(Request $re)
    {

        $challenge = $re->hub_challenge;
        $verify_token = $re->hub_verify_token;

        if ($verify_token === 'prappo') {
            return $challenge;
        }

        $input = json_decode(file_get_contents('php://input'), true);
        Run::now($input);
    }
}
