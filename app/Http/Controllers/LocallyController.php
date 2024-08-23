<?php

namespace App\Http\Controllers;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class LocallyController extends Controller
{
    public function index($lang)
    {
        if(!App::isLocale($lang)){
            \Session::put('applocale', $lang);
            App::setLocale($lang);
        }

        return redirect()->back();
    }

    public function post(Request $request)
    {
    	$lang = $request->value;
    	if(!App::isLocale($lang)){
            \Session::put('applocale', $lang);
            App::setLocale($lang);
        }
    	echo 'true';
    }
}
