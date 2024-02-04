<?php

use App\Http\Controllers\JobController;
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


Route::group(['middleware'=>['auth:sanctum']],function(){
    Route::get('auth/user',[UsersController::class,'auth']);

    Route::post('auth/update',[UsersController::class, 'update']);

    Route::post('post_job',[PostController::class, 'post_job']);
    Route::get('show_posts',[PostController::class, 'show_post']);

    Route::get('show_job_seekers',[JobController::class, 'show']);

    Route::post('referee/add',[RefereeController::class, 'create']);
    Route::get('referee/view',[RefereeController::class, 'view']);

});
