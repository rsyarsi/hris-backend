<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\V1\{AuthController, DepartmentController};

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


Route::middleware('api')->prefix('auth/v1')->group(function () {
    // route for authentication
    // Route::prefix('auth')->group(function () {
    // });
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/refresh', [AuthController::class, 'refresh']);
    Route::get('/user-profile', [AuthController::class, 'userProfile']);
    Route::post('/logout', [AuthController::class, 'logout']);


    Route::prefix('masterdata')->group(function () {
        // route for users
        Route::resource('users', UserController::class);
        // route for departments
        Route::resource('departments', DepartmentController::class);
        // route for educations
        Route::resource('educations', EducationController::class);
        // route for position
        Route::resource('positions', PositionController::class);
        // route for religions
        Route::resource('religions', ReligionController::class);
        // route for sexs
        Route::resource('sexs', SexController::class);
        // route for status-employements
        Route::resource('status-employements', StatusEmployementController::class);
        // route for taxs
        Route::resource('taxs', TaxController::class);
        // route for units
        Route::resource('units', UnitController::class);
    });
});
