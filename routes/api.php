<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\ProductController;
use App\Models\Category;
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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });


Route::group([
    'prefix' => 'auth'
], function () {

    Route::post('login', [AuthController::class, 'login']);

    Route::group([
        'middleware' => 'auth:api'
    ], function () {
        Route::post('files/storage', [FileController::class, 'storage']);
        Route::apiResource('products', ProductController::class);
        Route::post('products/active/{id}', [ProductController::class, 'active']);
        Route::get('categories/list', [CategoryController::class, 'list']);
        Route::apiResource('categories', CategoryController::class);
    });
});




Route::get('categories/list', [CategoryController::class, 'list']);
Route::get('categories/{id}', [CategoryController::class, 'show']);
Route::get('products/novelties', [ProductController::class, 'novelties']);
Route::post('products/byCategory', [ProductController::class, 'category']);
Route::post('products/recommended', [ProductController::class, 'listRecommended']);
