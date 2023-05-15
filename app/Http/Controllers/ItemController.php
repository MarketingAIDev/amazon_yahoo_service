<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Item;
use App\Models\Category;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use DataTables;

class ItemController extends Controller
{
    public function add_item($id) {
		$user = Auth::user();
		$category = Category::find($id);

		return view('items.add_item', ['user' => $user, 'category' => $category]);
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

	public function list(Request $request, $id) {
		$user = Auth::user();
		$category = Category::find($id);
		return view('items.item_list', ['user' => $user, 'category' => $category]);
	}

	public function item_datatable(Request $request) {
		if ($request->ajax()) {
			$data = Item::select('id', 'img_url', 'name', 'asin', 'jan', 'register_price', 'target_price', 'min_price', 'shop_url')->where('category_id', $_GET['categoryId'])->where('status', 1)->get();
			return Datatables::of($data)->make(true);
		}
	}

	public function delete_item(Request $request) {
		if ($request->condition == 'all') {
			Item::where('category_id', $request->id)->delete();
		} else if ($request->condition == 'one') {
			Item::find($request->id)->delete();
		}
		return;
	}

	public function csv_download(Request $request, $id) {
		$data = "";
		$filename = "";
		$category = Category::find($id);

		$data .= "ASIN\n";
		$items = $category->items;
		foreach ($items as $i) {
			$data .= $i['asin']."\n";
		}
		
		$filename = $category->name . "_ASINリスト";
		
		header('Content-Type: application/csv');
		header('Content-Disposition: attachment; filename="' . $filename . "_" . date("Y-m-d") . '.csv"');
		echo $data;
		exit();
	}
}
