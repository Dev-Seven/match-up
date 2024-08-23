<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\CmsPage;
use Validator;
use JWTAuth;
use Response;
use JWTFactory;
use Illuminate\Support\Str;
use Tymon\JWTAuth\Exceptions\JWTException;
use App;

class CmsController extends Controller
{
    public function cms_page(Request $request)
    {
        $inputData = $request->all();

        $header = $request->header('AuthorizationUser');
        if(empty($header))
        {
            $message = 'Authorisation required' ;
            return InvalidResponse($message,101);
        }

        $response = veriftyAPITokenData($header);
        $success = $response->original['success'];
        
        if (!$success) {
            return $response;
        }

        $lang = 'en';
        if(isset($request->language) && $request->language != '' && $request->language == 'de') {
            $lang = 'de';
        }

        if(!App::isLocale($lang)){
            App::setLocale($lang);
        }

        $page = new CmsPage;
        if(isset($request->slug) && $request->slug != ''){
            $slug = $request->slug;
            $page = $page->where('slug','LIKE','%'.$slug.'%');
        }
        $page = $page->first();

        if(!empty($page)){
            $message = __('messages.api.cms.page_detail');
            return SuccessResponse($message,200,$page);
        } else {
            $message = __('messages.api.cms.oops_page_not_found');
            return InvalidResponse($message,101);
        }
    }
}
