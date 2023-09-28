<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\V1\{
    AuthController, DepartmentController, EducationController, PositionController,
    ReligionController, SexController, TaxController, UserController, UnitController, StatusEmploymentController,
    JobController, RelationshipController, IdentityTypeController, MaritalStatusController,
    LegalityTypeController, ProvinceController, CityController, DistrictController, VillageController,
    EmployeeController, EmployeeOrganizationController, EmployeeExperienceController,
    EmployeeEducationController, EmployeePositionHistoryController, EmployeeLegalityController,
    EmployeeFamilyController, SkillTypeController, EmployeeCertificateController, EmployeeSkillController,
    LeaveTypeController, LeaveStatusController, LeaveController, LeaveApprovalController, LeaveHistoryController,
    ShiftGroupController, ShiftController, LogFingerController, OvertimeStatusController, OvertimeController,
    ContractTypeController, PayrollComponentController, EmployeeContractController, EmployeeContractDetailController
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
        Route::resource('departments', DepartmentController::class)->parameters(['departments' => 'department']);
        // route for educations
        Route::resource('educations', EducationController::class)->parameters(['educations' => 'education']);
        // route for position
        Route::resource('positions', PositionController::class)->parameters(['positions' => 'position']);
        // route for religions
        Route::resource('religions', ReligionController::class)->parameters(['religions' => 'religion']);
        // route for sexs
        Route::resource('sexs', SexController::class)->parameters(['sexs' => 'sex']);
        // route for taxs
        Route::resource('taxs', TaxController::class)->parameters(['taxs' => 'tax']);
        // route for users
        Route::resource('users', UserController::class)->parameters(['users' => 'user']);
        // route for units
        Route::resource('units', UnitController::class)->parameters(['units' => 'unit']);
        // route for status-employments
        Route::resource('status-employments', StatusEmploymentController::class)->parameters(['status-employments' => 'status_employment']);
        // route for contract-types
        Route::resource('contract-types', ContractTypeController::class)->parameters(['contract-types' => 'contract_type']);
        // route for jobs
        Route::resource('jobs', JobController::class)->parameters(['jobs' => 'job']);
        // route for relationships
        Route::resource('relationships', RelationshipController::class)->parameters(['relationships' => 'relationship']);
        // route for identity-types
        Route::resource('identity-types', IdentityTypeController::class)->parameters(['identity-types' => 'identity_type']);
        // route for marital-statuses
        Route::resource('marital-statuses', MaritalStatusController::class)->parameters(['marital-statuses' => 'marital_status']);
        // route for legality-types
        Route::resource('legality-types', LegalityTypeController::class)->parameters(['legality-types' => 'legality_type']);
        // route for skill-types
        Route::resource('skill-types', SkillTypeController::class)->parameters(['skill-types' => 'skill_type']);
        // route for payroll-components
        Route::resource('payroll-components', PayrollComponentController::class)->parameters(['payroll-components' => 'payroll_component']);
        // route for provinces
        Route::resource('provinces', ProvinceController::class)->parameters(['provinces' => 'province']);
        // route for cities
        Route::resource('cities', CityController::class)->parameters(['cities' => 'city']);
        // route for districts
        Route::resource('districts', DistrictController::class)->parameters(['districts' => 'district']);
        // route for villages
        Route::resource('villages', VillageController::class)->parameters(['villages' => 'village']);
        // route for employees
        Route::resource('employees', EmployeeController::class)->parameters(['employees' => 'employee']);
        // route for employee-number-null
        Route::get('employee-number-null', [EmployeeController::class, 'employeeNumberNull'])->name('employee-number-null');
        // route for employee-end-contracts
        Route::get('employee-end-contracts', [EmployeeController::class, 'employeeEndContract'])->name('employee-end-contracts');
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
        Route::resource('leave-types', LeaveTypeController::class)->parameters(['leave-types' => 'leave_type']);
        // route for leave-statuses
        Route::resource('leave-statuses', LeaveStatusController::class)->parameters(['leave-statuses' => 'leave_status']);
        // route for leaves
        Route::resource('leaves', LeaveController::class);
        // route for leave-approvals
        Route::resource('leave-approvals', LeaveApprovalController::class);
        // route for leave-histories
        Route::resource('leave-histories', LeaveHistoryController::class);
        // route for shift-groups
        Route::resource('shift-groups', ShiftGroupController::class)->parameters(['shift-groups' => 'shift_group']);
        // route for shifts
        Route::resource('shifts', ShiftController::class)->parameters(['shifts' => 'shift']);
        // route for log-fingers
        Route::resource('log-fingers', LogFingerController::class)->parameters(['log-fingers' => 'log_finger']);
        // route for overtime-statuses
        Route::resource('overtime-statuses', OvertimeStatusController::class)->parameters(['overtime-statuses' => 'overtime_status']);
        // route for overtimes
        Route::resource('overtimes', OvertimeController::class)->parameters(['overtimes' => 'overtime']);
        // route for employee-contracts
        Route::resource('employee-contracts', EmployeeContractController::class)->parameters(['employee-contracts' => 'employee_contract']);
        // route for employee-contract-details
        Route::resource('employee-contract-details', EmployeeContractDetailController::class)->parameters(['employee-contract-details' => 'employee_contract_detail']);
    });
});
