<?php

namespace App\Http\Controllers;

use App\Models\User;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function list_account() {
		$user = Auth::user();
		$users = User::all();
		return view('admin.account', ['user' => $user, 'users' => $users]);
	}

	public function delete_account(Request $request) {
		$id = $request->id;
		User::find($id)->delete();
	}

	public function permit_account(Request $request) {
		$id = $request['id'];
		$user = User::find($id);
		$user->is_permitted = $request['isPermitted'];
		$user->save();
	}
}
