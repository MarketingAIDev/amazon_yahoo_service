<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Item;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use DataTables;

class ItemController extends Controller
{
    public function add_item() {
		$user = Auth::user();
		return view('items.add_item', ['user' => $user]);
	}
    
	public function save_item(Request $request) {
		$res = $request->all();
		$res["user_id"] = Auth::user()->id;
		
		$common_id = Item::select('id')->where('id', $res["sel"])->where('user_id', Auth::user()->id)->get();

		if (count($common_id) > 0) {			
			$sel = $res["sel"];
			unset($res["sel"]);
			Item::where("id", $sel)->update($res);
			$sel = Item::where("id", $sel)->get();
			echo json_encode($sel);
		} else {
			unset($res["sel"]);
			$sel = Item::create($res);
			$sel = Item::where("id", $sel["id"])->get();
			echo json_encode($sel);
		}
	}

	public function item_list() {
		$user = Auth::user();
		$items = Item::where('user_id', $user->id)->where('status', 1)->orderBy('id', 'desc')->paginate(50);
		return view('items.item_list', ['user' => $user, 'items' => $items]);
	}

	public function item_datatable(Request $request)
	{
		if ($request->ajax()) {
			$data = Item::select('id', 'y_img_url', 'item_name', 'asin', 'jan', 'y_register_price', 'y_target_price', 'y_min_price', 'y_shop_url', 'updated_time')->where('user_id', Auth::id())->where('status', 1)->get();
			return Datatables::of($data)->make(true);
		}
	}

	public function delete_item(Request $request) {
		if ($request->id == 'all') {
			Item::where('user_id', Auth::id())->delete();
		} else {
			Item::find($request->id)->delete();
		}
		return;
	}
}
