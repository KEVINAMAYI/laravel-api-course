<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
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

Route::post('/register',[AuthController::class,'register']);
Route::post('/login',[AuthController::class,'login']);

Route::group(["middleware" => ['auth:sanctum']],function (){
    Route::resource('products',ProductController::class);
    Route::resource('users',UserController::class);
    Route::post('/logout',[AuthController::class,'logout']);

});

Route::fallback(function (){
    return response()->json(['error' => 'This Route was Not Found'], 404);
});
