<?php

use App\Http\Controllers\LoginController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\TestController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
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
Route::middleware('auth:sanctum')->group(function (){
    Route::post('logout' ,[LoginController::class , 'logout'])->name('logout');

});

Route::post('sign-up' ,[LoginController::class , 'register'] )->name('sign-up');
Route::post('login' ,[LoginController::class , 'login'] )->name('login');

Route::controller(UserController::class)->group(function () {
    Route::get('/user/{user:slug}', 'show');
    Route::put('/user/{user:slug}', 'update');
    Route::delete('/user/{user:slug}', 'destroy');
});

Route::apiResource('task' ,TaskController::class);
Route::apiResource('project' ,ProjectController::class);



Route::get('test' ,[TestController::class , 'index'] );
