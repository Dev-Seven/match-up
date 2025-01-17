<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Crypt;
use App\Models\User as UserModel;
use Carbon\Carbon;
use Illuminate\Support\Facades\App;

CONST ADMIN_ROLE = 1;
CONST API_ROLE = 2;
CONST USER_ROLE = 3;


function veriftyAPITokenData($header) 
{
    //  $enc = Crypt::encrypt('apiuser@matchup.com|123456789');
    try 
    {
        $authorization_cred = Crypt::decrypt($header);
        // echo'<pre>'; print_r($authorization_cred); echo'<pre>'; die();
        $expcred = explode('|', $authorization_cred);
        $apiuser = $expcred[0];
        $apipassword = $expcred[1];
    } 
    catch(\Exception $e) 
    {
        $message = "Invalid User Authentication";
        return InvalidResponse($message,101);
    }

    $user = UserModel::where('email', $apiuser)->where('role',API_ROLE)->first();
    if ($user && Hash::check($apipassword, $user->password)) 
    {
        return response()->json([
            'success' => true,
            'status_code' => 200,
            'message' => 'Token valid',
            'data' => array(),
        ]);
    } 
    else 
    {
        $message = 'Invalid Token';
        return InvalidResponse($message,101);
    }
}

function checkUserExists($user_id)
{
    $response = false;
    $UserModel = UserModel::where('id',$user_id)->first();
    if(!empty($UserModel))
    {
        $response = true;
    }
    return $response;
}

function SuccessResponse($message,$status_code,$data)
{
    return response()->json(['success' => true,
		'status_code' => $status_code,
		'message' => $message,
		'data' => $data
    ]);
}

function numberFormat($number)
{
    $number_data = number_format((float)$number, 2, '.', '');
    return $number_data;
}

function InvalidResponse($message,$status_code)
{
    return response()->json(['success' => false,
		'status_code' => $status_code,
		'message' => $message,
		'data' => array()
    ]);
}

function sendOtp($mobile_number,$otp){
    $mobile_number = '91'.$mobile_number;
    $message = env('MSG91_MESSAGE').' '.$otp;

    \LaravelMsg91::sendOtp($mobile_number, $otp, $message);
}

function generateRandomString($length)
{
	$randomString = '';
	$characters = '123456789';
	$characterLengths = strlen($characters);
	for($i=0; $i<$length;$i++)
	{
		$randomString .= $characters[rand(0,$characterLengths - 1)];
	}
	return $randomString;
}

function generateRandomToken($length)
{
	$randomString = '';
	$characters = '123456789ABCDEFGHIJKLMNOPQRSTUVWZYabcdefghijklmnopqrstuvwxyz';
	$characterLengths = strlen($characters);
	for($i=0; $i<$length;$i++)
	{
		$randomString .= $characters[rand(0,$characterLengths - 1)];
	}
	return $randomString;
}

function sendPushNotification($title,$message)
{
    $getData = \App\Models\SupportDetail::where('type','server_key')->first();
    $getTopic = \App\Models\SupportDetail::where('type','topic')->first();

    $url = 'https://fcm.googleapis.com/fcm/send';

    $notification = ['title' =>$title, 'body' => $message ];

    if(!empty($getData)){
        $server_key = $getData->name;
    } else {
        $server_key = 'AAAAVGD3aEE:APA91bFpLtM0AeuPj07MzJaMj2DzvHBf6ErJ_kGq_akX5PVFLX9rUqWGTLzXV0dIZ7haF8r86eclyizs4C7DTZMorQmXOPsiEaOiurTEhrS_xR7Nje5kXI47fE39G_TFSctZXnQZ_tzW';
    }

    if(!empty($getTopic)){
        $topic = $getTopic->name;
    } else {
        $topic = "match-up-push";
    }

    $extraNotificationData = ["message" => $notification,"moredata" =>'dd'];

    $fcmNotification = [
        'to'  => $topic,
        'notification' => $notification,
        'data' => $extraNotificationData
    ];

    $fields = json_encode ( $fcmNotification );

    $headers = array (
        'Authorization: key=' .$server_key,
        'Content-Type: application/json'
    );

    $ch = curl_init ();
    curl_setopt ( $ch, CURLOPT_URL, $url );
    curl_setopt ( $ch, CURLOPT_POST, true );
    curl_setopt ( $ch, CURLOPT_HTTPHEADER, $headers );
    curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, true );
    curl_setopt ( $ch, CURLOPT_POSTFIELDS, $fields );

    $result = curl_exec ( $ch );
    echo $result;
    curl_close ( $ch );
}

function change_timezone_local($value,$timezone) {
    if($timezone == ''){
        $timezone = 'UTC';
    }
    return Carbon::createFromTimestamp(strtotime($value))->timezone($timezone)->toDateTimeString();
}

function utcToLocal($value) {
    return Carbon::parse($value, 'UTC')->setTimezone('Asia/Kolkata')->format('Y-m-d H:i:s');
}

function get_current_timezone()
{
    //$ip = "189.240.194.147";  //$_SERVER['REMOTE_ADDR'];

    $ip = $_SERVER['REMOTE_ADDR'];
    $ipInfo = file_get_contents('http://ip-api.com/json/' . $ip);
    $ipInfo = json_decode($ipInfo);

    if($ipInfo->status == 'fail'){
        $timezone = 'UTC';
    } else {
        $timezone = $ipInfo->timezone;  
    }
    return $timezone;
    //return 'Asia/Kolkata';
}

?>
