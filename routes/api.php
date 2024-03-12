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
    ShiftScheduleController, RoleController, PermissionController, GenerateAbsenController, LogFingerTempController,
    PphController, DeductionController, GeneratePayrollController, UmpController, AdjustmentCutiController,
    TimesheetOvertimeController, CatatanCutiController, OvertimeHistoryController, ShiftScheduleExchangeController,
    SuratPeringatanController, MutationController, PromotionDemotionController, PengembalianController,
    InformationController, OrderOvertimeController
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
        Route::post('/login-mobile-app', 'loginMobileApps');
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
            // route for update password mobile
            Route::post('/update-password-mobile', 'updatePasswordMobile')->name('update-password-mobile');
            // route for term condition verified
            Route::post('/term-condition-verified', 'termConditionVerified')->name('term-condition-verified');
            // route for asign role to user
            Route::post('/users/{user}/roles', 'assignRole')->name('users.roles');
            // route for remove role from user
            Route::delete('/users/{user}/roles/{role}', 'removeRole')->name('users.roles.remove');
            // route for give permission to user
            Route::post('/users/{user}/permissions', 'givePermission')->name('users.permissions');
            // route for revoke permission from user
            Route::delete('/users/{user}/permissions/{permission}', 'revokePermission')->name('users.permissions.revoke');
            // route for export user
            Route::get('users-export', 'export')->name('users-export');
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
            // route for employee active
            Route::get('employee-active', 'employeeActive')->name('employee-active');
            // route for employee number null(employee before have contract)
            Route::get('employee-number-null', 'employeeNumberNull')->name('employee-number-null');
            // route for employee end contracts (employee have contract ended 14 days)
            Route::get('employee-end-contracts', 'employeeEndContract')->name('employee-end-contracts');
            // route for import employee
            Route::post('import-employee', 'importEmployee')->name('import-employee');
            // route for employee where spv/kabag/manager yang login
            Route::get('employee-subordinate', 'employeeSubordinate')->name('employee-subordinate');
            // route for employee where spv/kabag/manager yang login mobile
            Route::post('employee-subordinate-mobile', 'employeeSubordinateMobile')->name('employee-subordinate-mobile');
            // route for employee where employee non shift
            Route::post('employee-non-shift', 'employeeNonShift')->name('employee-non-shift');
            // route for employee employee have contract
            Route::post('employee-have-contract', 'employeeHaveContract')->name('employee-have-contract');
            // route for employee resigned
            Route::get('employee-resigned', 'employeeResigned')->name('employee-resigned');
            // route for employee upload photo
            Route::post('employee-upload-photo/{employeeId}', 'employeeUploadPhoto')->name('employee-upload-photo');
            // route for employee upload photo mobile
            Route::post('employee-upload-photo-mobile', 'employeeUploadPhotoMobile')->name('employee-upload-photo-mobile');
            // route for employee profile mobile
            Route::post('employee-profile-mobile', 'employeeProfileMobile')->name('employee-profile-mobile');
            // route for check active employee mobile
            Route::post('check-active-employee-mobile', 'checkActiveEmployeeMobile')->name('check-active-employee-mobile');
            // route for employees user
            Route::get('employees-export', 'export')->name('employees-export');
        });
        // route for employee-contracts
        Route::resource('employee-contracts', EmployeeContractController::class)->parameters(['employee-contracts' => 'employee_contract']);
        Route::controller(EmployeeContractController::class)->group(function () {
            // route for count employee end contracts (employee have contract ended 14 days)
            Route::get('count-employee-end-contracts', 'countEmployeeEndContract')->name('count-employee-end-contracts');
        });
        // route for employee-contract-details
        Route::resource('employee-contract-details', EmployeeContractDetailController::class)->parameters(['employee-contract-details' => 'employee_contract_detail']);
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
        Route::controller(EmployeeLegalityController::class)->group(function () {
            // route for count employee legalities ended
            Route::get('count-employee-legalities-ended', 'countEmployeeLegalitiesEnded')->name('count-employee-legalities-ended');
            // route for employee legalities ended
            Route::get('employee-legalities-ended', 'employeeLegalitiesEnded')->name('employee-legalities-ended');
            // route for import employee legality
            Route::post('import-employee-legality', 'importEmployeeLegality')->name('import-employee-legality');
        });
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
            // route for shift schedules where employee today
            Route::post('shift-schedules-employee-today', 'shiftScheduleEmployeeToday')->name('shift-schedules-employee-today');
            // route for shift schedules where employee mobile (1 month)
            Route::post('shift-schedules-employee-mobile', 'shiftScheduleEmployeeMobile')->name('shift-schedules-employee-mobile');
            // route for shift schedules where employee and date
            Route::get('shift-schedules-employee-date', 'shiftScheduleEmployeeDate')->name('shift-schedules-employee-date');
            // route for shift schedules where employee
            Route::get('shift-schedules-where-employee', 'shiftScheduleEmployee')->name('shift-schedules-where-employee');
            // route for multiple shift schedule
            Route::post('multiple-shift-schedules', 'storeMultiple')->name('multiple-shift-schedules');
            // route for generate shift schedule non shift
            Route::post('generate-shift-schedule-non-shift', 'generateShiftScheduleNonShift')->name('generate-shift-schedule-non-shift');
            // route for import shift schedule
            Route::post('import-shift-schedule', 'importShiftSchedule')->name('import-shift-schedule');
            // route for shift schedules subordinate
            Route::get('shift-schedules-subordinate', 'shiftScheduleSubordinate')->name('shift-schedules-subordinate');
            // route for shift schedules kehadiran admin
            Route::get('shift-schedules-kehadiran-employee', 'shiftScheduleKehadiranEmployee')->name('shift-schedules-kehadiran-employee');
            // route for shift schedules kehadiran atasan login / subordinate
            Route::get('shift-schedules-kehadiran-subordinate', 'shiftScheduleKehadiranSubordinate')->name('shift-schedules-kehadiran-subordinate');
            // route for shift schedules kehadiran employee login
            Route::get('shift-schedules-kehadiran', 'shiftScheduleKehadiran')->name('shift-schedules-kehadiran');
            // route for export shift schedule kehadiran
            Route::get('shift-schedules-kehadiran-export', 'exportKehadiran')->name('shift-schedules-kehadiran-export');
            // route for export shift schedules
            Route::get('shift-schedules-export', 'exportshiftschedules')->name('shift-schedules-export');
        });
        // route for master shift schedules
        Route::resource('shift-schedules-exchanges', ShiftScheduleExchangeController::class)->parameters(['shift-schedules-exchanges' => 'shift_schedules_exchange']);
        Route::controller(ShiftScheduleExchangeController::class)->group(function () {
            // route for shift schedules exchange subordinate
            Route::get('shift-schedules-exchange-subordinate', 'indexSubordinate')->name('shift-schedules-exchange-subordinate');
            // route for shift schedules exchange mobile
            Route::post('shift-schedules-exchange-subordinate-mobile', 'indexSubordinateMobile')->name('shift-schedules-exchange-subordinate-mobile');
            // route for shift schedules exchange mobile
            Route::post('shift-schedule-exchange-create-mobile', 'createMobile')->name('shift-schedule-exchange-create-mobile');
        });
        // route for master log fingers
        Route::resource('log-fingers', LogFingerController::class)->parameters(['log-fingers' => 'log_finger']);
        Route::controller(LogFingerController::class)->group(function () {
            // route for import log finger
            Route::post('import-log-finger', 'importLogFinger')->name('import-log-finger');
            // route log finger where user login -> employee_id
            Route::get('log-finger-user', 'logFingerUser')->name('log-finger-user');
        });
        Route::resource('log-fingers-temps', LogFingerTempController::class)->parameters(['log-fingers-temps' => 'log_finger_temp']);
        Route::controller(LogFingerTempController::class)->group(function () {
            // route for import log finger
            Route::post('import-log-finger-temp', 'importLogFingerTemp')->name('import-log-finger-temp');
            // route log finger where user login -> employee_id
            Route::get('log-finger-temp-user', 'logFingerTempUser')->name('log-finger-temp-user');
        });
        // route for generate absen
        Route::resource('generate-absen', GenerateAbsenController::class)->parameters(['generate-absen' => 'generate_absen']);
        Route::controller(GenerateAbsenController::class)->group(function () {
            // route for generate absen employee_id
            Route::get('generate-absen-employee/{employee_id}', 'generateAbsenEmployee')->name('generate-absen-employee');
            // route for monitoring absen (absen yang tidak lengkap)
            Route::get('monitoring-absen', 'monitoringAbsen')->name('monitoring-absen');
            // route for generate absen subordinate (absen yang tidak lengkap)
            Route::get('generate-absen-subordinate', 'generateAbsenSubordinate')->name('generate-absen-subordinate');
            // route for generate absen subordinate mobile
            Route::get('generate-absen-subordinate-mobile', 'generateAbsenSubordinateMobile')->name('generate-absen-subordinate-mobile');
            // route for execute generate absen
            Route::post('execute-generate-absen', 'executeStoredProcedure')->name('execute-generate-absen');
            // route for absen from mobile
            Route::post('absen-from-mobile', 'absenFromMobile')->name('absen-from-mobile');
            // route for export monitoring absen (absen yang tidak lengkap)
            Route::get('monitoring-absen-export', 'exportMonitoringAbsen')->name('monitoring-absen-export');
            // route for rekap absensi
            Route::get('monitoring-absen-rekap', 'exportMonitoringAbsenRekap')->name('monitoring-absen-rekap');
        });
        // route for generate payroll
        Route::resource('generate-payroll', GeneratePayrollController::class)->parameters(['generate-payroll' => 'generate-payroll']);
        Route::controller(GeneratePayrollController::class)->group(function () {
            // route for generate payroll mobile
            Route::post('generate-payroll-mobile', 'indexMobile')->name('generate-payroll-mobile');
            // route for generate payroll employee
            Route::get('generate-payroll-employee', 'generatePayrollEmployee')->name('generate-payroll-employee');
            // route for execute generate payroll
            Route::post('execute-generate-payroll', 'executeStoredProcedure')->name('execute-generate-payroll');
            // route for print slip gaji
            Route::get('print-slip-gaji/{id}', 'printSlipGaji')->name('print-slip-gaji');
            // route for send slip gaji
            Route::post('send-slip-gaji/{id}', 'sendSlipGaji')->name('send-slip-gaji');
            // route for send slip gaji period
            Route::post('send-slip-gaji-period', 'sendSlipGajiPeriod')->name('send-slip-gaji-period');
            // route for slip gaji mobile
            Route::post('slip-gaji-mobile', 'slipGajiMobile')->name('slip-gaji-mobile');
            // route for export generate payroll
            Route::get('generate-payroll-export', 'generatePayrollExport')->name('generate-payroll-export');
        });
        // route for pph
        Route::resource('pph', PphController::class)->parameters(['pph' => 'pph']);
        Route::controller(PphController::class)->group(function () {
            // route for pph employee login
            Route::get('pph-employee', 'pphEmployee')->name('pph-employee');
            // route for import pph
            Route::post('import-pph', 'importPph')->name('import-pph');
        });
        // route for deduction
        Route::resource('deductions', DeductionController::class)->parameters(['deductions' => 'deduction']);
        Route::controller(DeductionController::class)->group(function () {
            // route for deductions employee login
            Route::get('deduction-employee', 'deductionEmployee')->name('deductions-employee');
            // route for import deductions
            Route::post('import-deductions', 'importDeduction')->name('import-deductions');
        });
        // route for ump
        Route::resource('umps', UmpController::class)->parameters(['umps' => 'ump']);
        // route for adjustment cuti
        Route::resource('adjustment-cuti', AdjustmentCutiController::class)->parameters(['adjustment-cuti' => 'adjustment_cuti']);
        Route::controller(AdjustmentCutiController::class)->group(function () {
            // route for adjustment cuti employee
            Route::get('adjustment-cuti-employee', 'adjustmentCutiEmployee')->name('adjustment-cuti-employee');
            // route for export adjustment cuti employee
            Route::get('adjustment-cuti-export', 'exportAdjustmentCuti')->name('adjustment-cuti-export');
        });
        // route for catatan cuti
        Route::resource('catatan-cuti', CatatanCutiController::class)->parameters(['catatan-cuti' => 'catatan_cuti']);
        Route::controller(CatatanCutiController::class)->group(function () {
            // route for catatan cuti employee
            Route::get('catatan-cuti-employee', 'catatanCutiEmployee')->name('catatan-cuti-employee');
            // route for catatan cuti
            Route::get('catatan-cuti-employee-latest', 'catatanCutiEmployeeLatest')->name('catatan-cuti-employee-latest');
            // route history pemakaian cuti
            Route::get('history-pemakaian-cuti', 'historyPemakaianCutiAll')->name('history-pemakaian-cuti');
            // route history pemakaian cuti subordinate
            Route::get('history-pemakaian-cuti-subordinate', 'historyPemakaianCutiSubordinate')->name('history-pemakaian-cuti-subordinate');
        });
        // route for timesheet overtimes
        Route::resource('timesheet-overtimes', TimesheetOvertimeController::class)->parameters(['timesheet-overtimes' => 'timesheet-overtime']);
        Route::controller(TimesheetOvertimeController::class)->group(function () {
            // route for deductions employee login
            Route::get('timesheet-overtime-employee', 'timesheetOvertimeEmployee')->name('timesheet-overtime-employee');
            Route::post('execute-generate-overtime', 'executeStoredProcedure')->name('execute-generate-overtime');
        });
        // route for surat peringatan
        Route::resource('surat-peringatan', SuratPeringatanController::class)->parameters(['surat-peringatan' => 'surat_peringatan']);
        Route::controller(SuratPeringatanController::class)->group(function () {
            // route for surat peringatan employee login
            Route::get('surat-peringatan-employee', 'suratPeringatanEmployee')->name('surat-peringatan-employee');
        });
        // route for mutations
        Route::resource('mutations', MutationController::class)->parameters(['mutations' => 'mutation']);
        Route::controller(MutationController::class)->group(function () {
            // route for mutation employee login
            Route::get('mutation-employee', 'mutationEmployee')->name('mutation-employee');
        });
        // route for deduction
        Route::resource('promotion-demotions', PromotionDemotionController::class)->parameters(['promotion-demotions' => 'promotion_demotion']);
        Route::controller(PromotionDemotionController::class)->group(function () {
            // route for promotion demotion employee login
            Route::get('promotion-demotion-employee', 'promotionDemotionEmployee')->name('promotion-demotion-employee');
        });
        // route for pengembalian
        Route::resource('pengembalian', PengembalianController::class)->parameters(['pengembalian' => 'pengembalian']);
        Route::controller(PengembalianController::class)->group(function () {
            // route for promotion demotion employee login
            Route::get('pengembalian-employee', 'pengembalianEmployee')->name('pengembalian-employee');
            // route for import pengembalian
            Route::post('import-pengembalian', 'importPengembalian')->name('import-pengembalian');
        });
        // route for pengembalian
        Route::resource('informations', InformationController::class)->parameters(['informations' => 'informations']);
        Route::controller(InformationController::class)->group(function () {
            // route for get informations mobile
            Route::get('informations-mobile', 'indexMobile')->name('informations-mobile');
        });
    });
});

Route::middleware('api')->prefix('v1/')->group(function () {
    // route for leaves
    Route::resource('leaves', LeaveController::class);
    Route::controller(LeaveController::class)->group(function () {
        // route for leave where employee login
        Route::get('leave-where-employee', 'leaveEmployee')->name('leave-where-employee');
        // route for leave where employee mobile
        Route::post('leave-employee-mobile', 'leaveEmployeeMobile')->name('leave-employee-mobile');
        // route for leave hrd mobile
        Route::get('leave-hrd-mobile', 'leaveHrdMobile')->name('leave-hrd-mobile');
        // route for leave where supervisor or manager logingenerate-payroll-mobile
        Route::get('leave-supervisor-manager', 'leaveSupervisorOrManager')->name('leave-supervisor-manager');
        // route for leave where supervisor or manager mobile
        Route::post('leave-supervisor-manager-mobile', 'leaveSupervisorOrManagerMobile')->name('leave-supervisor-manager-mobile');
        // route for leave where status
        Route::get('leave-where-statuses', 'leaveStatus')->name('leave-where-statuses');
        // route for leave update status(approval/rejected)
        Route::post('leave-update-statuses/{id}', 'updateStatus')->name('leave-update-statuses');
        // route for leave update status mobile
        Route::post('leave-update-statuses-mobile', 'updateStatusMobile')->name('leave-update-statuses-mobile');
        // route for get data sisa cuti, if select type cuti tahunan
        Route::post('leave-sisa', 'leaveSisa')->name('leave-sisa');
        // route for mobile
        Route::post('leave-create-mobile', 'leaveCreateMobile')->name('leave-create-mobile');
        // route for export leaves
        Route::get('leaves-export', 'exportLeave')->name('leaves-export');
        // route for export leaves status
        Route::get('leaves-status-export', 'exportLeaveWhereStatus')->name('leaves-status-export');
    });
    // route for leave histories
    Route::resource('leave-histories', LeaveHistoryController::class);
    // route for leave approvals
    Route::resource('leave-approvals', LeaveApprovalController::class);
    // route for overtimes
    Route::resource('overtimes', OvertimeController::class)->parameters(['overtimes' => 'overtime']);
    Route::controller(OvertimeController::class)->group(function () {
        // route for overtime overtime where employee login
        Route::get('overtime-where-employee', 'overtimeEmployee')->name('overtime-where-employee');
        // route for overtime overtime where employee mobile (1 month)
        Route::post('overtime-employee-mobile', 'overtimeEmployeeMobile')->name('overtime-employee-mobile');
        // route for overtime hrd mobile
        Route::get('overtime-hrd-mobile', 'overtimeHrdMobile')->name('overtime-hrd-mobile');
        // route for overtime where employee today
        Route::post('overtime-employee-today', 'overtimeEmployeeToday')->name('overtime-employee-today');
        // route for Overtime where supervisor or manager login
        Route::get('overtime-supervisor-manager', 'overtimeSupervisorOrManager')->name('overtime-supervisor-manager');
        // route for Overtime where supervisor or manager mobile
        Route::post('overtime-supervisor-manager-mobile', 'overtimeSupervisorOrManagerMobile')->name('overtime-supervisor-manager-mobile');
        // route for overtime where status
        Route::get('overtime-where-statuses', 'overtimeStatus')->name('overtime-where-statuses');
        // route for overtime update status(approval/rejected)
        Route::post('overtime-update-statuses/{id}', 'updateStatus')->name('overtime-update-statuses');
        // route for overtime update status mobile
        Route::post('overtime-update-statuses-mobile', 'updateStatusMobile')->name('overtime-update-statuses-mobile');
        // route for mobile
        Route::post('overtime-create-mobile', 'overtimeCreateMobile')->name('overtime-create-mobile');
        // route for export overtimes
        Route::get('overtimes-export', 'exportOvertime')->name('overtimes-export');
        // route for export leaves status
        Route::get('overtimes-status-export', 'exportOvertimeWhereStatus')->name('overtimes-status-export');
        // ================ info rekap ================
        // Route for rekap employee
        Route::get('overtimes-employee-rekap', 'overtimeEmployeeRekap')->name('overtimes-employee-rekap');
        // Route for rekap unit
        Route::get('overtimes-unit-rekap', 'overtimeUnitRekap')->name('overtimes-unit-rekap');
        // Route for rekap department
        Route::get('overtimes-department-rekap', 'overtimedepartmentRekap')->name('overtimes-department-rekap');

    });
    // route for overtime histories
    Route::resource('overtime-histories', OvertimeHistoryController::class);
    // route for order overtime
    Route::resource('order-overtimes', OrderOvertimeController::class)->parameters(['order-overtimes' => 'order_overtimes']);
    Route::controller(OrderOvertimeController::class)->group(function () {
        // route for store order overtime mobile
        Route::post('order-overtime-store-mobile', 'storeMobile')->name('order-overtime-store-mobile');
        // route for index subOrdinate order overtime
        Route::get('order-overtime-subordinate', 'indexSubOrdinate')->name('order-overtime-subordinate');
        // route for index subOrdinate order overtime mobile
        Route::post('order-overtime-subordinate-mobile', 'indexSubOrdinateMobile')->name('order-overtime-subordinate-mobile');
        // route for update status
        Route::post('order-overtime-update-status/{id}', 'updateStatus')->name('order-overtime-update-status');
        // route for update status mobile
        Route::post('order-overtime-update-status-mobile', 'updateStatusMobile')->name('order-overtime-update-status-mobile');
    });
});
