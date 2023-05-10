<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\User;
use ZipArchive;

use Illuminate\Support\Facades\Mail;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MypageController extends Controller
{  
	public function index() {
		$user = Auth::user();
		$item_cnt = Item::where('user_id', $user->id)->where('status', 1)->count();
		$error_cnt = Item::where('user_id', $user->id)->where('status', 0)->count();
		return view('mypage.dashboard', ['user' => $user, 'item_cnt' => $item_cnt, 'error_cnt' => $error_cnt]);
	}

	public function error_list() {
		$user = Auth::user();
		$items = Item::where('user_id', $user->id)->where('status', 0)->paginate(50);
		return view('mypage.error_list', ['user' => $user, 'items' => $items]);
	}

	public function itemEdit($id) {
		$item = Item::find($id);
		return $item;
	}

	public function scanDB() {
		$user = User::find(Auth::user()->id);
		return $user;
	}

	public function register_tracking(Request $request) {
		$user = User::find(Auth::user()->id);
		$user['fall_pro'] = $request['percent'];
		$user['y_lower_bound'] = $request['lower'];
		$user['y_upper_bound'] = $request['upper'];
		$user['fee_include'] = $request['fee'];
		$user['ex_key'] = $request['ex_key'];
		$user->save();
	}

	public function save_name_index(Request $request)
	{
		$user = User::find(Auth::user()->id);
		$user['len'] = $request['len'];
		$user['name'] = $request['name'];
		$user->save();
	}

	public function update_tracking(Request $request)
	{
		$user = User::find(Auth::user()->id);
		$user['fall_pro'] = $request['percent'];
		$user->save();
		$items = Item::where('user_id', Auth::user()->id)->get();
		foreach ($items as $item) {
			$item['register_price'] = $item['min_price'];
			$item['target_price'] = $item['min_price'] * $request['percent'] / 100;
			$item->save();
			// sleep(2);
		}
	}

	public function shop_list($id) {
		$user = Auth::user();
		$item = Item::find($id);
		$lists = json_decode($item->y_shops);
		$prs = array();
		$pos = array();
		$total = array();

		if ($lists != null && count($lists) > 0) {
			foreach($lists as $list) {
				$curl = curl_init();
	
				curl_setopt_array($curl, array(
					CURLOPT_RETURNTRANSFER => 1,
					CURLOPT_URL => $list
				));
	
				$response = curl_exec($curl);
				
				$price_regex = '/span class="elPriceNumber">[0-9,]+/';
				preg_match($price_regex, $response, $price);
				if ($price == null) {
					$pr = 0;
				} else {
					$pr = preg_replace('/\D/', '', html_entity_decode($price[0]));
				}			
				array_push($prs, $pr);
	
				if ($user->fee_include == 0) {
					$po = 0;
				} else {
					$postage_regex = '/送料[0-9,]+円/';
					preg_match($postage_regex, $response, $postage);
					if ($postage == null) {
						$po = 0;
					} else {
						$po = preg_replace('/\D/', '', html_entity_decode($postage[0]));
					}
					array_push($pos, $po);
				}
				
				$list = 'https://ck.jp.ap.valuecommerce.com/servlet/referral?sid=3546031&pid=888145296&vc_url=' . urlencode($list);
				$total[$list] = $pr + $po;
				// array_push($total, $pr + $po);
			}
			
			asort($total);
	
			$item->min_price = reset($total);
			$item->register_price = reset($total);
			$item->target_price = round(reset($total) * $user->fall_pro / 100);
			$item->save();
			
			header('Location: ' . array_keys($total, reset($total))[0]);
			die();
		} else {
			header('Location: ' . $item['y_shop_list']);
			die();
		}

		// return view('mypage.shop_list', ['user' => $user, 'lists' => $lists, 'total' => $total]);
	}

	// public function shop_list($id) {
	// 	$item = Item::find($id);
	// 	$lists = json_decode($item->y_shop_list);
	// 	// return $lists;
	// 	$user = Auth::user();
	// 	return view('mypage.shop_list', ['user' => $user, 'lists' => $lists]);
	// }

	public function get_item($id) {
		$item = Item::find($id);
		return $item;
	}

	public function get_allitems() {
		$user = Auth::user();
		// $items = Item::where('user_id', $user->id)->get();
		$items = Item::select('id')->where('user_id', $user->id)->where('status', 1)->get();
		return $items;
	}

	public function edit_track(Request $request) {
		$item = Item::find($request['id']);
		$item->target_price = $request['price'];
		$item->is_notified = 0;
		$item->save();
	}

	public function search(Request $request) {
		$user = Auth::user();
		$k = explode(' ', $request->key);
		
		for ($i = 0; $i < count($k); $i++) {
			$GLOBALS['pattern'] = '%'.$k[$i].'%';
			$items = Item::where('user_id', $user->id)
							->where('status', 1)
							->where(function($query) {
								$query->where('name', 'like', $GLOBALS['pattern'])
											->orWhere('jan', 'like', $GLOBALS['pattern'])
											->orWhere('asin', 'like', $GLOBALS['pattern']);
							})
							->paginate(50);
		}
		return view('mypage.item_list', ['user' => $user, 'items' => $items]);
	}

	public function regTrack(Request $request) {
		$user = User::where('email', $request['email'])->first();
		if ($user->is_permitted == 0) {
			return redirect()->route('login');
		}

		$item = Item::where('user_id', $user['id'])->where('jan', $request['itemCode'])->first();
		if ($item == null) {
			$item = new Item;
		}

		$item->user_id = $user['id'];
		$item->img_url = $request['img-url'];
		$item->name = $request['itemName'];
		$item->code_kind = 0;
		// $item->asin = null;
		$item->jan = $request['itemCode'];
		$item->register_price = $request['current-price'];
		$item->target_price = $request['target-price'];
		$item->min_price = $request['current-price'];
		$item->y_shop_list = "https://ck.jp.ap.valuecommerce.com/servlet/referral?sid=3546031&pid=888145296&vc_url='" . urlencode($request['shop-url']);
		$item->y_shops = json_encode(array($request['shop-url']));
		$item->updated_time = date("Y.m.d H.i.s");

		if ($request['itemCode'] == '') {
			$item->status = 0;
		} else {
			$item->status = 1;
		}

		$item->save();

		return redirect()->route('item_list');
	}

	public function updateAlert(Request $request) {
		$res = $request->all();
		$details = [];
		$bccAry = [];

		$user = User::find($res['user_id']);
		$item = Item::find($res['item_id']);
		$item->is_notified = 1;

		$lists = json_decode($item->y_shops);
		$total = array();

		foreach($lists as $list) {
			$curl = curl_init();

			curl_setopt_array($curl, array(
				CURLOPT_RETURNTRANSFER => 1,
				CURLOPT_URL => $list
    	));

    	$response = curl_exec($curl);
			
			$price_regex = '/span class="elPriceNumber">[0-9,]+/';
			preg_match($price_regex, $response, $price);
			if ($price == null) {
				$pr = 0;
			} else {
				$pr = preg_replace('/\D/', '', html_entity_decode($price[0]));
			}

			if ($user->fee_include == 0) {
				$po = 0;
			} else {
				$postage_regex = '/送料[0-9,]+円/';
				preg_match($postage_regex, $response, $postage);
				if ($postage == null) {
					$po = 0;
				} else {
					$po = preg_replace('/\D/', '', html_entity_decode($postage[0]));
				}
			}

			$list = 'https://ck.jp.ap.valuecommerce.com/servlet/referral?sid=3546031&pid=888145296&vc_url=' . urlencode($list);
			$total[$list] = $pr + $po;
		}
		
		asort($total);
		if (reset($total) == 0) {
			return;
		}
		$item->min_price = reset($total);
		$item->y_shop_list = array_keys($total, reset($total))[0];
		$item->save();

		$item = Item::find($res['item_id']);

		$details['email'] = $user['email'];
		$details['name'] = $user['name'];
		$details['name'] = $item['name'];
		$details['register_price'] = $item['register_price'];
		$details['target_price'] = $item['target_price'];
		$details['min_price'] = $item['min_price'];
		$details['link'] = $item['y_shop_list'];
		$details['asin'] = $item['asin'];
		
		if ($details['target_price'] <= $details['min_price']) {
			return;
		}
		
		Mail::to($details["email"])
				->bcc($bccAry)
				->send(new \App\Mail\UpdateMail($details));

		$item->register_price = $item['min_price'];
		$item->target_price = round($item['min_price'] * $user['fall_pro'] / 100);
		$item->save();
	}

	public function extDownload() {
		$zip = new ZipArchive();
		//create the file and throw the error if unsuccessful
		$file = 'yahoo_ext/inject.js';
		// Открываем файл для получения существующего содержимого
		$current = file_get_contents($file);
		// Добавляем нового человека в файл
		$current = "let email = '".Auth::user()->email."';";

		$current .= 'async function init() {
    
			let janCode = document.getElementById("itm_cat").innerHTML.match(/\d{13}/)[0];
			let name = document.querySelector(\'p[class="elName"]\').innerHTML;
			let currentPrice = document.getElementsByClassName("elPriceNumber")[0].innerHTML.replace(/,/g, "");
			let img_url = document.getElementsByClassName("elPanelImage")[0].src;
			let y_shop_list = document.querySelector(\'meta[property="og:url"]\').content;
			
			let inject = 
			\'<form target="_blank" method="get" action="https://xs786968.xsrv.jp/mypage/individual">\' + 
				\'<div class="form-group">\' +
					\'<label for="titles"><ヤフカリ>\' + email + \'</label>\' + 
					\'<div style="margin-top: 10px;"><label for="target-price">目標価格</label>\' + 
					\'<input type="number" class="form-control" id="target-price" name="target-price" placeholder="1000" value="" style="width: 150px !important;" />円になったら通知する</div>\' + 
					\'<input type="hidden" name="email" value="\' + email + \'" />\' + 
					\'<input type="hidden" name="itemName" value="\' + name + \'" />\' + 
					\'<input type="hidden" name="itemCode" value="\' + janCode + \'" />\' + 
					\'<input type="hidden" name="current-price" value="\' + currentPrice + \'" />\' + 
					\'<input type="hidden" name="img-url" value="\' + img_url + \'" />\' + 
					\'<input type="hidden" name="shop-url" value="\' + y_shop_list + \'" />\' + 
					\'<div style="margin-top: 10px;"><button type="submit" class="btn btn-block btn-primary" style="background-color: lightblue; width: 200px !important;">トラッキング登録</button></div>\' + 
				\'</div>\' +
			\'</form>\';
			let target = document.getElementById("prcdsp");
			target.innerHTML += inject;
		}
		init();';

		// Пишем содержимое обратно в файл
		file_put_contents($file, $current);

		$tmp_file = 'assets/myzip.zip';
		if ($zip->open($tmp_file,  ZipArchive::CREATE)) {
			$zip->addFile('yahoo_ext/inject.js', 'inject.js');
			$zip->addFile('yahoo_ext/manifest.json', 'manifest.json');
			$zip->addFile('yahoo_ext/yahoo.png', 'yahoo.png');
			$zip->close();
			header('Content-disposition: attachment; filename=yahoo_tracking_ext.zip');
			header('Content-type: application/zip');
			header('Encoding: UTF-8');
			readfile($tmp_file);
		} else {
			echo 'Failed!';
		}
	}

	public function register_yahoo(Request $request) {
		$user = User::find(Auth::id());
		$user['yahoo_id'] = $request['token'];
		$user['yahoo_id1'] = $request['token1'];
		$user['yahoo_id2'] = $request['token2'];
		$user->save();
	}

	public function register_amazon(Request $request) {
		$user = User::find(Auth::id());
		$user['access_key'] = $request['access_key'];
		$user['secret_key'] = $request['secret_key'];
		$user['partner_tag'] = $request['partner_tag'];
		$user->save();
	}
	
	public function register_exhibition(Request $request) {
		$user = User::find(Auth::id());
		$user['fall_pro'] = $request['fall_pro'];
		$user['web_hook'] = $request['web_hook'];
		$user->save();
	}

	public function toastWarning(Request $request) {
		$res = $request->all();
		$user = User::find($res['user_id']);
		$warningItem = Item::find($res['item_id']);
		$items = Item::where('user_id', $user->id)->where('status', 1)->paginate(50);
		return view('mypage.item_list', ['user' => $user, 'items' => $items, 'warningItem' => $warningItem]);
	}

	public function toastError(Request $request) {
		$res = $request->all();
		$user = User::find($res['user_id']);
		$errorItem = Item::find($res['item_id']);
		$items = Item::where('user_id', $user->id)->where('status', 1)->paginate(50);
		return view('mypage.item_list', ['user' => $user, 'items' => $items, 'errorItem' => $errorItem]);
	}

	public function change_percent(Request $request) {
		$res = $request->all();
		$user = User::find(Auth::user()->id);
		$user['fall_pro'] = $res['pro'];
		$user->save();
		return $res['pro'];
	}

	public function set_state(Request $request)
	{
		$req = $request->all();
		$user = User::find(Auth::user()->id);
		$user['is_registering'] = $req['state'];
		$user->save();
	}

	public function get_state(Request $request)
	{
		$user = User::find(Auth::user()->id);
		return $user['is_registering'];
	}
}