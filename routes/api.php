<?php

use App\Http\Controllers\AttributeController;
use App\Http\Controllers\DivisionController;
use App\Http\Controllers\EntityController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\PermissionController;
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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('auth:api')->resource('entity', EntityController::class);
Route::middleware('auth:api')->resource('group', GroupController::class);
Route::middleware('auth:api')->resource('division', DivisionController::class);
Route::middleware('auth:api')->resource('permission', PermissionController::class);
Route::middleware('auth:api')->resource('attribute', AttributeController::class);
Route::middleware('auth:api')->get('form/entity', [EntityController::class, 'resolveRegionForm']);
Route::middleware('auth:api')->get('form/group', [GroupController::class, 'resolveRegionForm']);
Route::middleware('auth:api')->get('form/division', [DivisionController::class, 'resolveRegionForm']);
Route::middleware('auth:api')->get('form/attribute', [AttributeController::class, 'resolveRegionForm']);
Route::middleware('auth:api')->get('filter/attribute/{parentId}', [AttributeController::class, 'filterIndex']);
