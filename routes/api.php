<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DepartmentController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
 
    
Route::group([ 'middleware' => 'api', 'prefix' => 'auth' ], function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/refresh', [AuthController::class, 'refresh']);
    Route::get('/user-profile', [AuthController::class, 'userProfile']);    



    Route::group(['prefix' => 'masterdata/'], function () {
        Route::group(['prefix' => 'department/'], function () {
            Route::post('/insert', [DepartmentController::class, 'store']);
            Route::post('/update/{id}', [DepartmentController::class, 'update']);
            Route::get('/data/{id}', [DepartmentController::class, 'show']);
            Route::get('/data/all', [DepartmentController::class, 'create']);
        });
        
    });
});
