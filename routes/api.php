<?php

use App\Http\Controllers\API\ProductController;
use App\Http\Controllers\API\Usercontroller;
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

Route::controller(Usercontroller::class)->group(function(){
    Route::post('login', 'loginUser');
    Route::post('updatePassword', 'Update_pass');
    Route::post('createUser', 'store');
});
Route::controller(Usercontroller::class)->group(function(){
    Route::get('user', 'getUserDetail');
    Route::get('users', 'index');
    Route::get('users/{id}', 'showUser');
    Route::get('logout', 'userLogout');
})->middleware('auth:api');

//product routes
Route::controller(ProductController::class)->group(function(){
    Route::get('products', 'index');
    Route::post('storeProduct', 'store');
    Route::put('updateProduct/{id}', 'update');
    Route::get('products/{id}', 'show');
    Route::delete('deleteProduct/{id}', 'destroy');
})->middleware('auth:api');

