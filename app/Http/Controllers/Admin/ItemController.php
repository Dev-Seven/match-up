<?php
namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use App\Models\Item;

class ItemController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $Check_item = Item::where('name','no item')->first();
        if(empty($Check_item))
        {
            $createNoItem = new Item;
            $createNoItem->name = 'no item';
            $createNoItem->status = 1;
            $createNoItem->save();
        }

        $gameItems = Item::orderBy('id','DESC')->get();
        return view('admin.game_item.index',compact('gameItems'));
    }

    public function create()
    {
        return view('admin.game_item.create');
    }

    public function store(Request $request)
    {
        $typeCreate = new Item;
        $typeCreate->name = $request->name;
        $typeCreate->name_de = $request->name_de;
        $typeCreate->status = $request->status;
        $typeCreate->single_selection = $request->item_selection;

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $name = time().'.'.$image->getClientOriginalExtension();
            $destinationPath = public_path('/game-image');
            $image->move($destinationPath, $name);
            $typeCreate->image = $name;
        }
        $typeCreate->save();

        return redirect()->route('admin.game_item.index')->with('success',__('messages.game_item.item_added_successfully'));
    }
    
    public function edit($id)
    {
        $id = base64_decode($id);
        $type = Item::where('id',$id)->first();
        return view('admin.game_item.edit',compact('type'));
    }

    public function update(Request $request)
    {
        $typeUpdate = Item::where('id',$request->id)->first();
        $typeUpdate->name = $request->name;
        $typeUpdate->name_de = $request->name_de;
        $typeUpdate->status = $request->status;
        $typeUpdate->single_selection = $request->item_selection;

        if ($request->hasFile('image')) {

            $file_path = public_path('/game-image/'.$typeUpdate->image);
            if($typeUpdate->image != '' && file_exists($file_path)){
                unlink($file_path);
            }

            $image = $request->file('image');
            $name = time().'.'.$image->getClientOriginalExtension();
            $destinationPath = public_path('/game-image');
            $image->move($destinationPath, $name);
            $typeUpdate->image = $name;
        }
        $typeUpdate->save();

        return redirect()->route('admin.game_item.index')->with('success',__('messages.game_item.item_updated_successfully'));
    }

    public function delete(Request $request)
    {
        $id = $request->id;
        Item::where('id',$id)->delete();

        return redirect()->route('admin.game_item.index')->with('success',__('messages.game_item.item_deleted_successfully'));
    }

    public function confirm(Request $request)
    {
        $user_id = $request->user_id;
        $status = $request->status;

        $user = Item::where('id',$user_id)->first();
        $user->status = $status;
        $user->save();

        return true;
    }
}
