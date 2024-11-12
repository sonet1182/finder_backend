<?php

use App\Http\Controllers\API\AreaController;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\CaytegoryController;
use App\Http\Controllers\API\ProductController;
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

Route::post('register', [AuthController::class, 'register'])->name('user.register');
Route::post('login', [AuthController::class, 'login'])->name('user.login');

// Auth Routes...
Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::post('logout', [AuthController::class, 'logout']);
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('products', [ProductController::class, 'product_list']);


//Area routes
Route::get('division_list', [AreaController::class, 'division_list']);
Route::get('district_list/{id}', [AreaController::class, 'district_list']);
Route::get('thana_list/{id}', [AreaController::class, 'thana_list']);

//Category List
Route::get('category_list', [CaytegoryController::class, 'category_list']);
Route::get('category_list/{id}', [CaytegoryController::class, 'category_list_under']);
