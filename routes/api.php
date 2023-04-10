<?php

use App\Http\Controllers\Products\ProductController;
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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


Route::group(['middleware' => ['auth:sanctum']],function (){

    Route::get("allProducts",[ProductController::class,"index"]);

        Route::group(['middleware' => ['is_admin']],function (){

            Route::post("addProduct",[ProductController::class,"create"]);

            Route::put("updateProduct/{product}",[ProductController::class,"update"]);

            Route::put("updateProduct/{product}",[ProductController::class,"update"]);

            Route::delete("deleteProduct/{product}",[ProductController::class,"delete"]);

        });

});
