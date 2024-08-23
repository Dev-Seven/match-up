<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PromocodeUser;
use Illuminate\Http\Request;
use App\Models\Promocode;
use App\Models\User;
use Auth;

class PromocodeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $Promocodes = Promocode::orderBy('id','DESC')->get()->toArray();

        $codeArr = [];
        if(!empty($Promocodes) && count($Promocodes) > 0)
        {
            foreach($Promocodes as $key => $value)
            {
                $codeArr[$key] = $value;
                $PromocodeUserCount = PromocodeUser::where('promocode_id',$value['id'])->count();
                $codeArr[$key]['users'] = $PromocodeUserCount;
            }
        }
        return view('admin.promocode.index',compact('codeArr'));
    }

    public function view($id)
    {
        $Promocodes = Promocode::where('id',$id)->first();
        if(empty($Promocodes))
        {
            return redirect()->back()->with('danger',__('messages.promocode.promocode_not_found'));
        }
        $Promocodes = $Promocodes->toArray();
        $PromocodeUsers = PromocodeUser::where('promocode_id',$Promocodes['id'])->get()->toArray();

        $userArr = [];
        if(!empty($PromocodeUsers) && count($PromocodeUsers) > 0)
        {
            foreach($PromocodeUsers as $key => $value)
            {
                $userDetails = User::where('id',$value['user_id'])->first();
                if(!empty($userDetails))
                {
                    $userDetails = $userDetails->toArray();
                    $userDetails['used_date'] = $value['created_at'];
                    $userArr[] = $userDetails;
                }
            }
        }
        $Promocodes['users'] = $userArr;
            
        return view('admin.promocode.view',compact('Promocodes'));
    }

    public function store(Request $request)
    {
        $createPromocode = new Promocode;
        $createPromocode->code = $request->promocode;
        $createPromocode->status = 1;
        $createPromocode->is_lifetime = $request->is_life_time;
        $createPromocode->save();

        return redirect()->route('admin.promocode.index')->with('success',__('messages.promocode.promocode_create_successfully'));
    }

    public function delete(Request $request)
    {
        $deletePromocode = Promocode::where('id',$request->id)->first();
        $deletePromocode->delete();

        return redirect()->back()->with('success',__('messages.promocode.promocode_deleted_successfully'));
    }
}
