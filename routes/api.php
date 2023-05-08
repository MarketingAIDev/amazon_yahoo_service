<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\RecruitmentController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\MatterController;
use App\Http\Controllers\API\ApplicationController;
use App\Http\Controllers\API\ChatController;
use App\Http\Controllers\API\NoticeController;
use App\Http\Controllers\MypageController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::prefix('v1')->group(function() {
    Route::post('login', [AuthController::class, 'signIn']);
    Route::post('register', [AuthController::class, 'signUp']);

    Route::middleware(['auth:sanctum'])->group( function () {
        // get user data
        Route::get('user', [AuthController::class, 'user']);
        // get profile data
        Route::get('profile', [UserController::class, 'get_profile']);
        // update profile data
        Route::post('profile/update', [UserController::class, 'update']);
        // get history recruitments for both of producer and worker
        Route::get('recruitments/history', [RecruitmentController::class, 'get_history']);

        Route::middleware(['auth:sanctum', 'ability:producerRole'])->group( function () {
            Route::get('recruitments', [RecruitmentController::class, 'get_by_producer']);
            Route::put('recruitment/status', [RecruitmentController::class, 'set_recruitment_status']);
            Route::post('recruitment', [RecruitmentController::class, 'create']);
            Route::post('recruitment/update', [RecruitmentController::class, 'update']);
            Route::delete('recruitment/{id}', [RecruitmentController::class, 'delete']);
            Route::post('recruitment/addon', [RecruitmentController::class, 'add_postscript']);

            Route::get('applicants/{recruitment_id}', [RecruitmentController::class, 'get_applicants']);
            Route::put('applicant/status', [RecruitmentController::class, 'set_applicant_status']);
            Route::put('applicant/evaluate_worker', [RecruitmentController::class, 'evaluate_worker']);

            Route::get('favourite/user', [UserController::class, 'get_favourite_user']);
            Route::put('favourite/user', [UserController::class, 'set_favourite_user']);
        });

        Route::middleware(['auth:sanctum', 'ability:workerRole'])->group( function () {
            Route::get('favourite/recruitment', [UserController::class, 'get_favourite_recruitment']);

            Route::post('matters', [MatterController::class, 'get']);
            Route::get('matters/recent', [MatterController::class, 'get_recent']);
            Route::put('matter/apply', [MatterController::class, 'apply']);

            Route::post('applications', [ApplicationController::class, 'get']);
            Route::get('applications/events', [ApplicationController::class, 'getAll']);
        });

        Route::get('chat/recruitments', [ChatController::class, 'get_recruitments']);
        Route::get('chat/favourites', [ChatController::class, 'get_favourites']);
        Route::get('chat/unread', [ChatController::class, 'get_unread_messages']);
        Route::get('chat/applicants/{recruitment_id}', [ChatController::class, 'get_applicants']);
        Route::get('chat/info/{recruitment_id}/{receiver_id}', [ChatController::class, 'get_info']);
        Route::post('chat/messages', [ChatController::class, 'get_messages']);
        Route::post('chat/send', [ChatController::class, 'sendMessage']);
        Route::put('chat/read', [ChatController::class, 'readMessage']);
        Route::delete('chat/{recruitment_id}/{receiver_id}', [ChatController::class, 'deleteMessages']);

        Route::get('notice/unread', [NoticeController::class, 'get_unread_news']);
        Route::put('notice/read', [NoticeController::class, 'set_read']);
    });

    Route::get('update_alert', [MypageController::class, 'updateAlert']);
    Route::get('toast_warning', [MypageController::class, 'toastWarning']);
    Route::get('toast_error', [MypageController::class, 'toastError']);
});