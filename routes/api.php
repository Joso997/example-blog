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

function modifyBit($n, $p, $b)
{
    $mask = 1 << $p;
    return ($n & ~$mask) |
           (($b << $p) & $mask);
}

Route::post('/testing', function (Request $request) {
    $tempRequest = $request->all();
    $temp = json_decode($request->get('objectJSON'));
	$name = $request->get('deviceName');
    if(property_exists($temp, 'master')){
        if($temp->master == 144 && $name != "tri-m-59fcc45e"){
            $temp->master = 128;
            $tempRequest['objectJSON'] = json_encode($temp);
        }
		/*if($temp->master == 239 && $name == "tri-m-59fcc45e"){
            $temp->master = 255;
            $tempRequest['objectJSON'] = json_encode($temp);
        }*/
		if(($temp->master & (1 << 5)) && ($temp->master & (1 << 6)) && $name == "tri-m-59fcc45e"){
			$temp->master = modifyBit($temp->master, 4,1);
			$tempRequest['objectJSON'] = json_encode($temp);
		}else if(!($temp->master & (1 << 5)) && !($temp->master & (1 << 6)) && $name == "tri-m-59fcc45e"){
			$temp->master = modifyBit($temp->master, 4,0);
			$tempRequest['objectJSON'] = json_encode($temp);
		}
        if(($temp->master & (1 << 2)) && ($temp->master & (1 << 1)) && $name == "tri-m-59fcc45e"){
            $temp->master = modifyBit($temp->master, 3,1);
            $tempRequest['objectJSON'] = json_encode($temp);
        }else if(!($temp->master & (1 << 2)) && !($temp->master & (1 << 1)) && $name == "tri-m-59fcc45e"){
            $temp->master = modifyBit($temp->master, 3,0);
            $tempRequest['objectJSON'] = json_encode($temp);//test
        }
    }
    Http::post('https://campsabout.com/api/helium', $tempRequest);
    return 'yes';
});
