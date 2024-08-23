<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\SupportDetail;
use Mail;
use Validator;
use Log;
use Exception;
use App;

class SupportController extends Controller
{
    public function index(Request $request)
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

        $support_data = SupportDetail::get();
        if(!empty($support_data)){
            
            $message = __('messages.api.support_data.list');
            return SuccessResponse($message,200,$support_data);
            
        } else {
            $message = __('messages.api.support_data.no_data_found');
            return InvalidResponse($message,200);
        }
    }
}
