<?php

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

//註冊路由
Route::post('register', [\App\Http\Controllers\RegisterController::class, 'store']);
//登陸路由
Route::post('login', [\App\Http\Controllers\LoginController::class, 'login']);
Route::get("logintest", [\App\Http\Controllers\LoginController::class, 'logintest']);
Route::middleware("user_login")->delete("logout", [\App\Http\Controllers\LoginController::class, 'logout']);
//用戶列表
Route::apiResource('users', \App\Http\Controllers\UserController::class, ["except" => ["update"]]);
//商品路由
Route::apiResource('product', \App\Http\Controllers\ProductController::class);

//測試路由
Route::get("test.{id}", [\App\Http\Controllers\TestController::class, 'index']);

