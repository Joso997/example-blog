<?php

use App\Http\Controllers\EntityController;
use App\Http\Controllers\RegionController;
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

/*Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});*/

/*Route::group( ['middleware' => 'none'],
    function () {
        Route::resource('entity', 'EntityController');
});*/

Route::resource('entity', EntityController::class);
Route::get('form', [RegionController::class, 'resolveRegion']);

