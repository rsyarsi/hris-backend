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
    ContractTypeController, PayrollComponentController, EmployeeContractController, EmployeeContractDetailController,
    ShiftScheduleController, RoleController, PermissionController, GenerateAbsenController
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
        // route for master roles
        Route::resource('roles', RoleController::class)->parameters(['roles' => 'role']);
        Route::controller(RoleController::class)->group(function () {
            // route for give permission to roles
            Route::post('/roles/{role}/permissions', 'givePermission')->name('roles.permissions');
            // route for revoke permission from roles
            Route::delete('/roles/{role}/permissions/{permission}', 'revokePermission')->name('roles.permissions.revoke');
        });
        // route for master permissions
        Route::resource('permissions', PermissionController::class)->parameters(['permissions' => 'permission']);
        Route::controller(PermissionController::class)->group(function () {
            // route for asign role to permission
            Route::post('/permissions/{permission}/roles', 'assignRole')->name('permissions.roles');
            // route for remove role from permission
            Route::delete('/permissions/{permission}/roles/{role}', 'removeRole')->name('permissions.roles.remove');
        });
        // route for master departments
        Route::resource('departments', DepartmentController::class)->parameters(['departments' => 'department']);
        // route for master educations
        Route::resource('educations', EducationController::class)->parameters(['educations' => 'education']);
        // route for master position
        Route::resource('positions', PositionController::class)->parameters(['positions' => 'position']);
        // route for master religions
        Route::resource('religions', ReligionController::class)->parameters(['religions' => 'religion']);
        // route for master sexs
        Route::resource('sexs', SexController::class)->parameters(['sexs' => 'sex']);
        // route for master taxs
        Route::resource('taxs', TaxController::class)->parameters(['taxs' => 'tax']);
        // route for master users
        Route::resource('users', UserController::class)->parameters(['users' => 'user']);
        Route::controller(UserController::class)->group(function () {
            // route for asign role to user
            Route::post('/users/{user}/roles', 'assignRole')->name('users.roles');
            // route for remove role from user
            Route::delete('/users/{user}/roles/{role}', 'removeRole')->name('users.roles.remove');
            // route for give permission to user
            Route::post('/users/{user}/permissions', 'givePermission')->name('users.permissions');
            // route for revoke permission from user
            Route::delete('/users/{user}/permissions/{permission}', 'revokePermission')->name('users.permissions.revoke');
        });
        // route for master units
        Route::resource('units', UnitController::class)->parameters(['units' => 'unit']);
        // route for master status employments
        Route::resource('status-employments', StatusEmploymentController::class)->parameters(['status-employments' => 'status_employment']);
        // route for master contract types
        Route::resource('contract-types', ContractTypeController::class)->parameters(['contract-types' => 'contract_type']);
        // route for master jobs
        Route::resource('jobs', JobController::class)->parameters(['jobs' => 'job']);
        // route for master relationships
        Route::resource('relationships', RelationshipController::class)->parameters(['relationships' => 'relationship']);
        // route for master identity-types
        Route::resource('identity-types', IdentityTypeController::class)->parameters(['identity-types' => 'identity_type']);
        // route for master marital-statuses
        Route::resource('marital-statuses', MaritalStatusController::class)->parameters(['marital-statuses' => 'marital_status']);
        // route for master legality types
        Route::resource('legality-types', LegalityTypeController::class)->parameters(['legality-types' => 'legality_type']);
        // route for master skill types
        Route::resource('skill-types', SkillTypeController::class)->parameters(['skill-types' => 'skill_type']);
        // route for master payroll components
        Route::resource('payroll-components', PayrollComponentController::class)->parameters(['payroll-components' => 'payroll_component']);
        // route for master provinces
        Route::resource('provinces', ProvinceController::class)->parameters(['provinces' => 'province']);
        // route for master cities
        Route::resource('cities', CityController::class)->parameters(['cities' => 'city']);
        // route for master districts
        Route::resource('districts', DistrictController::class)->parameters(['districts' => 'district']);
        // route for master villages
        Route::resource('villages', VillageController::class)->parameters(['villages' => 'village']);
        // route for master employees
        Route::resource('employees', EmployeeController::class)->parameters(['employees' => 'employee']);
        Route::controller(EmployeeController::class)->group(function () {
            // route for employee number null(employee before have contract)
            Route::get('employee-number-null', 'employeeNumberNull')->name('employee-number-null');
            // route for employee end contracts (employee have contract ended 14 days)
            Route::get('employee-end-contracts', 'employeeEndContract')->name('employee-end-contracts');
        });
        // route for master employee organizations
        Route::resource('employee-organizations', EmployeeOrganizationController::class);
        // route for master employee experiences
        Route::resource('employee-experiences', EmployeeExperienceController::class);
        // route for master employee educations
        Route::resource('employee-educations', EmployeeEducationController::class);
        // route for master employee position histories
        Route::resource('employee-position-histories', EmployeePositionHistoryController::class);
        // route for master employee legalities
        Route::resource('employee-legalities', EmployeeLegalityController::class);
        // route for master employee families
        Route::resource('employee-families', EmployeeFamilyController::class);
        // route for master employee certificates
        Route::resource('employee-certificates', EmployeeCertificateController::class);
        // route for master employee skills
        Route::resource('employee-skills', EmployeeSkillController::class);
        // route for master leave types
        Route::resource('leave-types', LeaveTypeController::class)->parameters(['leave-types' => 'leave_type']);
        // route for master leave statuses
        Route::resource('leave-statuses', LeaveStatusController::class)->parameters(['leave-statuses' => 'leave_status']);
        // route for master overtime statuses
        Route::resource('overtime-statuses', OvertimeStatusController::class)->parameters(['overtime-statuses' => 'overtime_status']);
        // route for master shift groups
        Route::resource('shift-groups', ShiftGroupController::class)->parameters(['shift-groups' => 'shift_group']);
        // route for master shifts
        Route::resource('shifts', ShiftController::class)->parameters(['shifts' => 'shift']);
        // route for master shift schedules
        Route::resource('shift-schedules', ShiftScheduleController::class)->parameters(['shift-schedules' => 'shift_schedules']);
        Route::controller(ShiftScheduleController::class)->group(function () {
            // route for shift schedules where employee
            Route::get('shift-schedules-where-employee', 'shiftScheduleEmployee')->name('shift-schedules-where-employee');
            // route for multiple shift schedule
            Route::post('multiple-shift-schedules', 'storeMultiple')->name('multiple-shift-schedules');
            // route for import shift schedule
            Route::post('import-shift-schedule', 'importShiftSchedule')->name('import-shift-schedule');
        });
        // route for master log fingers
        Route::resource('log-fingers', LogFingerController::class)->parameters(['log-fingers' => 'log_finger']);
        Route::controller(LogFingerController::class)->group(function () {
            // route for import log finger
            Route::post('import-log-finger', 'importLogFinger')->name('import-log-finger');
        });
        // route for employee-contracts
        Route::resource('employee-contracts', EmployeeContractController::class)->parameters(['employee-contracts' => 'employee_contract']);
        // route for employee-contract-details
        Route::resource('employee-contract-details', EmployeeContractDetailController::class)->parameters(['employee-contract-details' => 'employee_contract_detail']);
        // route for generate absen
        Route::resource('generate-absen', GenerateAbsenController::class)->parameters(['generate-absen' => 'generate-absen']);
    });
});

Route::middleware('api')->prefix('v1/')->group(function () {
    // route for leaves
    Route::resource('leaves', LeaveController::class);
    Route::controller(LeaveController::class)->group(function () {
        // route for leave where employee login
        Route::get('leave-where-employee', 'leaveEmployee')->name('leave-where-employee');
        // route for leave where supervisor or manager login
        Route::get('leave-supervisor-manager', 'leaveSupervisorOrManager')->name('leave-supervisor-manager');
        // route for leave where status
        Route::get('leave-where-statuses', 'leaveStatus')->name('leave-where-statuses');
        // route for leave update status(approval/rejected)
        Route::post('leave-update-statuses/{id}', 'updateStatus')->name('leave-update-statuses');
    });
    // route for leave-histories
    Route::resource('leave-histories', LeaveHistoryController::class);
    // route for leave-approvals
    Route::resource('leave-approvals', LeaveApprovalController::class);
    // route for overtimes
    Route::resource('overtimes', OvertimeController::class)->parameters(['overtimes' => 'overtime']);
    Route::controller(OvertimeController::class)->group(function () {
        // route for overtime overtime where employee login
        Route::get('overtime-where-employee', 'overtimeEmployee')->name('overtime-where-employee');
        // route for Overtime where supervisor or manager login
        Route::get('overtime-supervisor-manager', 'overtimeSupervisorOrManager')->name('overtime-supervisor-manager');
        // route for overtime where status
        Route::get('overtime-where-statuses', 'overtimeStatus')->name('overtime-where-statuses');
        // route for overtime update status(approval/rejected)
        Route::post('overtime-update-statuses/{id}', 'updateStatus')->name('overtime-update-statuses');
    });
});
