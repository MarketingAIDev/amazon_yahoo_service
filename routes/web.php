<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;

use App\Http\Controllers\AdminController;

use App\Http\Controllers\ItemController;
use App\Http\Controllers\CategoryController;

// Authentication Routes
Route::get('/signup/{role?}', [RegisterController::class, 'index']);
Route::post('/register', [RegisterController::class, 'register'])->name('register');
Route::post('/login', [LoginController::class, 'login']);
Route::get('/loginview', [LoginController::class, 'loginview']);
Route::get('/logout', [LoginController::class, 'logout'])->name('logout');
Route::get('forgot-password', [LoginController::class, 'forgotPwd'])->name('forgot');
Route::get('reset_password', [LoginController::class, 'resetPwd'])->name('reset');
Route::get('reset_pwd', function() { return view('auth.reset'); });
Route::post('update_password', [LoginController::class, 'updatePwd'])->name('password.update');

// Main Routes
Route::group(['middleware' => ['auth']], function() {

	Route::view('/', 'mypage.dashboard')->name('welcome');

	// Item Routes
	Route::prefix('item')->group(function() {
		Route::get('/add/{id}', [ItemController::class, 'add_item'])->name('add_item');
		Route::get('/list/{id}', [ItemController::class, 'list'])->name('item_list');
		Route::get('/item_datatable', [ItemController::class, 'item_datatable'])->name('item_datatable');
		Route::post('/delete', [ItemController::class, 'delete_item'])->name('delete_item');
		Route::post('/save', [ItemController::class, 'save_item'])->name('save_item');
		Route::get('/csv/{id}', [ItemController::class, 'csv_download'])->name('csv');
	});

	// Category Routes
	Route::prefix('category')->group(function() {
		Route::get('/', [CategoryController::class, 'index'])->name('category_list');
		Route::post('/add', [CategoryController::class, 'create'])->name('add_category');
		Route::post('/edit', [CategoryController::class, 'edit'])->name('edit_category');
		Route::get('/delete/{id}', [CategoryController::class, 'delete'])->name('delete_category');
		Route::post('/set/column', [CategoryController::class, 'set_column'])->name('set_column');
		Route::get('/scan/{id}', [CategoryController::class, 'scan'])->name('scan');
		Route::get('/stop/{id}', [CategoryController::class, 'stop'])->name('stop');
		Route::get('/restart/{id}', [CategoryController::class, 'restart'])->name('restart');
		Route::post('/save/{id}', [CategoryController::class, 'save'])->name('save_category');
	});
	
	Route::get('/mypage/register_tracking', [MypageController::class, 'register_tracking'])->name('register_tracking');
	Route::get('/mypage/update_tracking', [MypageController::class, 'update_tracking'])->name('update_tracking');
	Route::get('/mypage/shop_list/{id}', [MypageController::class, 'shop_list'])->name('shop_list');
	Route::post('/mypage/get_allitems', [MypageController::class, 'get_allitems'])->name('get_allitems');
	Route::get('/mypage/edit_track', [MypageController::class, 'edit_track'])->name('edit_track');
	Route::get('/mypage/search', [MypageController::class, 'search'])->name('search');
	Route::get('/mypage/individual', [MypageController::class, 'regTrack'])->name('reg');
	Route::get('/mypage/change_percent', [MypageController::class, 'change_percent'])->name('change_percent');
	Route::get('/mypage/set_registering_state', [MypageController::class, 'set_state'])->name('set_state');
	Route::get('/mypage/get_registering_state', [MypageController::class, 'get_state'])->name('get_state');
	Route::get('/mypage/save_name_index', [MypageController::class, 'save_name_index'])->name('save_name_index');

	// register yahoo and amazon token
	Route::post('/mypage/register_yahoo', [MypageController::class, 'register_yahoo'])->name('register_yahoo');
	Route::post('/mypage/register_amazon', [MypageController::class, 'register_amazon'])->name('register_amazon');
	Route::post('/mypage/register_exhibition', [MypageController::class, 'register_exhibition'])->name('register_exhibition');

	// send a alert message to the client with eamil
	Route::get('/mypage/update_alert', [MypageController::class, 'updateAlert'])->name('alert');
	
	//download zip file
	Route::get('/mypage/ext_download', [MypageController::class, 'extDownload'])->name('extDownload');

	Route::view('/custom', 'layouts.main');
});

// Admin Routes
Route::group(['middleware' => ['auth', 'admin']], function() {
	Route::prefix('admin')->group(function() {
		Route::get('/account', [AdminController::class, 'list_account'])->name('list_account');
		Route::get('/delete', [AdminController::class, 'delete_account'])->name('delete_account');
		Route::get('/permit', [AdminController::class, 'permit_account'])->name('permit_account');
	});
});

Route::middleware(['cors'])->group(function () {
    Route::get('http://localhost:32768/');
});