<?php

use App\Http\Controllers\AttributeController;
use App\Http\Controllers\DivisionController;
use App\Http\Controllers\EntityController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\RegionController;
use App\Http\Controllers\SearchController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
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
Route::middleware('auth:api')->get('/user/logout', function (Request $request) {
    $user = Auth::user()->token();
    $user->revoke();
    return response('logged out');
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
Route::middleware('auth:api')->get('form/attribute/{option}', [AttributeController::class, 'resolveUserChoiceForm']);

Route::middleware('auth:api')->post('editAll/permission', [PermissionController::class, 'customUpdate']);

Route::middleware('auth:api')->post('search', [SearchController::class, 'index']);

Route::post('/testing', function (Request $request) {
    $tempRequest = $request->all();
    $temp = json_decode($request->get('objectJSON'));
    if(key_exists('master', $temp)){
        if($temp->master == 144){
            $temp->master = 128;
            $tempRequest['objectJSON'] = json_encode($temp);
        }
    }
    Http::post('https://eoq5gro7kjr6leg.m.pipedream.net', [$temp->master]);
    return 'yes';
});


