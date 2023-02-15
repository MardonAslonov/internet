<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\API\RegisterController;
use App\Http\Controllers\API\ProductController;


// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });


Route::post('refresh', [RegisterController::class, 'refresh']);
Route::post('login', [RegisterController::class, 'login']);
Route::post('salom', [RegisterController::class, 'salom']);


// Route::middleware('auth:api')->group( function () {
//     Route::resource('products', ProductController::class);
// });
