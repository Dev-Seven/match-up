<?php

namespace App\Http\Controllers\Api\Logged;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use JWTAuth;
use Response;
use JWTFactory;
use App\Models\User;
use App\Models\Faq;
use App\Models\FaqCategory;
use Tymon\JWTAuth\Exceptions\JWTException;
use App;

class RulesController extends Controller
{
    public function __construct()
    {
        $this->middleware('jwt.auth');
    }

    public function index(Request $request)
    {
        $inputData = $request->all();

        $header = $request->header('AuthorizationUser');
        $user_token = $request->header('authorization');

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

        $jwt_user = JWTAuth::parseToken()->authenticate($user_token);
        $checkUserExists = checkUserExists($jwt_user->id);
        if($checkUserExists == false)
        {
            $message = 'User not found';
            return InvalidResponse($message,505);
        }

        $lang = 'en';
        if(isset($request->language) && $request->language != '' && $request->language == 'de') {
            $lang = 'de';
        }

        if(!App::isLocale($lang)){
            App::setLocale($lang);
        }

        $jwt_user = JWTAuth::parseToken()->authenticate($user_token);
        $userDetail = User::where('id',$jwt_user->id)->first();

        $faqCategory = FaqCategory::with('faq')->where('status',1)->get()->toArray();
        $faqCategoryArr = [];

        if($lang == 'de') 
        {
            if(!empty($faqCategory) && count($faqCategory) > 0)
            {
                foreach($faqCategory as $key => $value)
                {
                    $faqCategoryArr[$key]['id'] = $value['id'];
                    $faqCategoryArr[$key]['name'] = $value['name_de'];
                    $faqCategoryArr[$key]['status'] = $value['status'];

                    $faqArr = [];
                    if(!empty($value['faq']) && count($value['faq']) > 0)
                    {
                        foreach($value['faq'] as $k => $v)
                        {
                            $faqArr[$k]['id'] = $v['id']; 
                            $faqArr[$k]['title'] = $v['title_de']; 
                            $faqArr[$k]['sub_title'] = $v['subtitle_de']; 
                            $faqArr[$k]['description'] = $v['description_de']; 
                        }
                    }
                    $faqCategoryArr[$key]['faq'] = $faqArr;
                }
            }
        }
        else
        {
            if(!empty($faqCategory) && count($faqCategory) > 0)
            {
                foreach($faqCategory as $key => $value)
                {
                    $faqCategoryArr[$key]['id'] = $value['id'];
                    $faqCategoryArr[$key]['name'] = $value['name'];
                    $faqCategoryArr[$key]['status'] = $value['status'];
                    $faqArr = [];
                    if(!empty($value['faq']) && count($value['faq']) > 0)
                    {
                        foreach($value['faq'] as $k => $v)
                        {
                            $faqArr[$k]['id'] = $v['id']; 
                            $faqArr[$k]['title'] = $v['title']; 
                            $faqArr[$k]['sub_title'] = $v['subtitle']; 
                            $faqArr[$k]['description'] = $v['description']; 
                        }
                    }
                    $faqCategoryArr[$key]['faq'] = $faqArr;
                }
            }
        }

        if(!empty($faqCategory)){
            $message = __('messages.api.faq.listing');
            return SuccessResponse($message,200,$faqCategoryArr);
        } else {
            $message = __('messages.api.faq.no_data_found');
            return InvalidResponse($message,101);
        }
    }
}
