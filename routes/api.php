<?php

use App\Http\Controllers\Calendartask;
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
    Route::apiResource('task' ,TaskController::class);
    Route::apiResource('project' ,ProjectController::class);
    Route::apiResource('users' , UserController::class)->except(['index' , 'store']);
    Route::get('calendar-tasks' , [Calendartask::class , 'list']);

});

Route::post('register' ,[LoginController::class , 'register'] )->name('register');
Route::post('login' ,[LoginController::class , 'login'] )->name('login');
Route::get('test' ,[TestController::class , 'index'] );
