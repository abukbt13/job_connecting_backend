<?php

use App\Http\Controllers\ConnectController;
use App\Http\Controllers\EmployerController;
use App\Http\Controllers\JobController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\RefereeController;
use App\Http\Controllers\UsersController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('auth/register',[UsersController::class, 'store']);
Route::post('auth/login',[UsersController::class, 'login']);
Route::post('auth/reset_password',[UsersController::class, 'reset_password']);
Route::post('auth/change_password/{id}',[UsersController::class, 'change_password']);
Route::post('auth/login',[UsersController::class, 'login']);


Route::post('capture_payment',[PaymentController::class, 'capture']);

Route::group(['middleware'=>['auth:sanctum']],function(){

    Route::get('auth/user',[UsersController::class,'auth']);


    Route::get('more/user/{id}',[UsersController::class,'more']);
    Route::get('user/ref/{id}',[UsersController::class,'refs']);


    Route::post('auth/update',[UsersController::class, 'update']);


    Route::group(['middleware' => 'employee'], function () {
        //    Employees
        //notification
        Route::get('e_notifications',[NotificationController::class, 'e_notifications']);

        Route::get('show_job_seekers',[JobController::class, 'show']);
        Route::get('suggested_job_seekers',[JobController::class, 'suggested_job_seekers']);
        Route::get('posts/show_my_posts',[JobController::class, 'my_posts']);
        Route::post('post/post_job',[PostController::class, 'post_job']);
        Route::get('posts/my_connects',[PostController::class, 'e_connects']);
        Route::get('show_e_connects',[PostController::class, 'show_e_connects']);
        Route::get('employer/connects',[EmployerController::class, 'connects']);
        Route::post('job_seeker/connect_job_seeker',[ConnectController::class, 'connect_job_seeker']);

    });

    Route::group(['middleware' => 'job_seeker'], function () {
//    Job_seekers
        Route::get('e_details/{id}',[UsersController::class, 'e_details']);

        //reference
        Route::get('create_notification/{id}',[NotificationController::class, 'create_notification']);

        Route::get('referee/view',[RefereeController::class, 'view']);
        Route::get('show_posts',[PostController::class, 'show_post']);
        Route::get('posts/suggested_posts',[PostController::class, 'suggested_posts']);
        //connect
        Route::post('job_seeker/connect_employer',[ConnectController::class, 'connect_employer']);

        Route::get('posts/j_connects',[PostController::class, 'j_connects']);
        Route::get('show_j_connects',[PostController::class, 'show_j_connects']);
    });
});
