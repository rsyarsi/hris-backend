<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\V1\{
    AuthController, DepartmentController, EducationController, PositionController,
    ReligionController, SexController, TaxController, UnitController, StatusEmploymentController,
    JobController, IdentityTypeController, MaritalStatusController, LegalityTypeController
};

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


Route::middleware('api')->prefix('v1/auth')->group(function () {
    Route::controller(AuthController::class)->group(function () {
        Route::post('/login', 'login');
        Route::post('/register', 'register');
        Route::post('/refresh', 'refresh');
        Route::get('/user-profile', 'userProfile');
        Route::post('/logout', 'logout');
    });

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
        // route for taxs
        Route::resource('taxs', TaxController::class);
        // route for units
        Route::resource('units', UnitController::class);
        // route for status-employments
        Route::resource('status-employments', StatusEmploymentController::class);
        // route for jobs
        Route::resource('jobs', JobController::class);
        // route for identity-types
        Route::resource('identity-types', IdentityTypeController::class);
        // route for marital-statuses
        Route::resource('marital-statuses', MaritalStatusController::class);
        // route for legality-types
        Route::resource('legality-types', LegalityTypeController::class);
    });
});

