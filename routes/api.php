<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\V1\{
    AuthController, DepartmentController, EducationController, PositionController,
    ReligionController, SexController, TaxController, UnitController, StatusEmploymentController,
    JobController, RelationshipController, IdentityTypeController, MaritalStatusController,
    LegalityTypeController, ProvinceController, CityController, DistrictController, VillageController,
    EmployeeController, EmployeeOrganizationController, EmployeeExperienceController,
    EmployeeEducationController, EmployeePositionHistoryController, EmployeeLegalityController,
    EmployeeFamilyController, SkillTypeController, EmployeeCertificateController, EmployeeSkillController,
    LeaveTypeController, LeaveStatusController, LeaveController, LeaveApprovalController, LeaveHistoryController
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

});
Route::middleware('api')->prefix('v1/')->group(function () {
    Route::prefix('masterdata')->group(function () {
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
        // route for uniEMPLOYEEts
        Route::resource('units', UnitController::class);
        // route for status-employments
        Route::resource('status-employments', StatusEmploymentController::class);
        // route for jobs
        Route::resource('jobs', JobController::class);
        // route for relationships
        Route::resource('relationships', RelationshipController::class);
        // route for identity-types
        Route::resource('identity-types', IdentityTypeController::class);
        // route for marital-statuses
        Route::resource('marital-statuses', MaritalStatusController::class);
        // route for legality-types
        Route::resource('legality-types', LegalityTypeController::class);
        // route for skill-types
        Route::resource('skill-types', SkillTypeController::class);
        // route for provinces
        Route::resource('provinces', ProvinceController::class);
        // route for cities
        Route::resource('cities', CityController::class);
        // route for districts
        Route::resource('districts', DistrictController::class);
        // route for villages
        Route::resource('villages', VillageController::class);
        // route for employees
        Route::resource('employees', EmployeeController::class);
        // route for employee-organizations
        Route::resource('employee-organizations', EmployeeOrganizationController::class);
        // route for employee-experiences
        Route::resource('employee-experiences', EmployeeExperienceController::class);
        // route for employee-educations
        Route::resource('employee-educations', EmployeeEducationController::class);
        // route for employee-position-histories
        Route::resource('employee-position-histories', EmployeePositionHistoryController::class);
        // route for employee-legalities
        Route::resource('employee-legalities', EmployeeLegalityController::class);
        // route for employee-families
        Route::resource('employee-families', EmployeeFamilyController::class);
        // route for employee-certificates
        Route::resource('employee-certificates', EmployeeCertificateController::class);
        // route for employee-skills
        Route::resource('employee-skills', EmployeeSkillController::class);
        // route for leave-types
        Route::resource('leave-types', LeaveTypeController::class);
        // route for leave-statuses
        Route::resource('leave-statuses', LeaveStatusController::class);
        // route for leave-leaves
        Route::resource('leave-statuses', LeaveStatusController::class);
        // route for leaves
        Route::resource('leaves', LeaveController::class);
        // route for leave-approvals
        Route::resource('leave-approvals', LeaveApprovalController::class);
        // route for leave-histories
        Route::resource('leave-histories', LeaveHistoryController::class);
    });
});
