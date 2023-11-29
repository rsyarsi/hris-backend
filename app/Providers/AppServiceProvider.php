<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\Role\{RoleService, RoleServiceInterface};
use App\Services\Permission\{PermissionService, PermissionServiceInterface};
use App\Services\Department\{DepartmentService, DepartmentServiceInterface};
use App\Services\Education\{EducationService, EducationServiceInterface};
use App\Services\Position\{PositionService, PositionServiceInterface};
use App\Services\Religion\{ReligionService, ReligionServiceInterface};
use App\Services\Sex\{SexService, SexServiceInterface};
use App\Services\Tax\{TaxService, TaxServiceInterface};
use App\Services\User\{UserService, UserServiceInterface};
use App\Services\Unit\{UnitService, UnitServiceInterface};
use App\Services\StatusEmployment\{StatusEmploymentService, StatusEmploymentServiceInterface};
use App\Services\ContractType\{ContractTypeService, ContractTypeServiceInterface};
use App\Services\PayrollComponent\{PayrollComponentService, PayrollComponentServiceInterface};
use App\Services\Job\{JobService, JobServiceInterface};
use App\Services\SkillType\{SkillTypeService, SkillTypeServiceInterface};
use App\Services\Relationship\{RelationshipService, RelationshipServiceInterface};
use App\Services\IdentityType\{IdentityTypeService, IdentityTypeServiceInterface};
use App\Services\MaritalStatus\{MaritalStatusService, MaritalStatusServiceInterface};
use App\Services\LegalityType\{LegalityTypeService, LegalityTypeServiceInterface};
use App\Services\Province\{ProvinceService, ProvinceServiceInterface};
use App\Services\City\{CityService, CityServiceInterface};
use App\Services\District\{DistrictService, DistrictServiceInterface};
use App\Services\Village\{VillageService, VillageServiceInterface};
use App\Services\Employee\{EmployeeService, EmployeeServiceInterface};
use App\Services\EmployeeOrganization\{EmployeeOrganizationService, EmployeeOrganizationServiceInterface};
use App\Services\EmployeeExperience\{EmployeeExperienceService, EmployeeExperienceServiceInterface};
use App\Services\EmployeeEducation\{EmployeeEducationService, EmployeeEducationServiceInterface};
use App\Services\EmployeePositionHistory\{EmployeePositionHistoryService, EmployeePositionHistoryServiceInterface};
use App\Services\EmployeeLegality\{EmployeeLegalityService, EmployeeLegalityServiceInterface};
use App\Services\EmployeeFamily\{EmployeeFamilyService, EmployeeFamilyServiceInterface};
use App\Services\EmployeeCertificate\{EmployeeCertificateService, EmployeeCertificateServiceInterface};
use App\Services\EmployeeSkill\{EmployeeSkillService, EmployeeSkillServiceInterface};
use App\Services\LeaveType\{LeaveTypeService, LeaveTypeServiceInterface};
use App\Services\LeaveStatus\{LeaveStatusService, LeaveStatusServiceInterface};
use App\Services\Leave\{LeaveService, LeaveServiceInterface};
use App\Services\LeaveHistory\{LeaveHistoryService, LeaveHistoryServiceInterface};
use App\Services\LeaveApproval\{LeaveApprovalService, LeaveApprovalServiceInterface};
use App\Services\ShiftGroup\{ShiftGroupService, ShiftGroupServiceInterface};
use App\Services\Shift\{ShiftService, ShiftServiceInterface};
use App\Services\ShiftSchedule\{ShiftScheduleService, ShiftScheduleServiceInterface};
use App\Services\LogFinger\{LogFingerService, LogFingerServiceInterface};
use App\Services\LogFingerTemp\{LogFingerTempService, LogFingerTempServiceInterface};
use App\Services\OvertimeStatus\{OvertimeStatusService, OvertimeStatusServiceInterface};
use App\Services\Overtime\{OvertimeService, OvertimeServiceInterface};
use App\Services\EmployeeContract\{EmployeeContractService, EmployeeContractServiceInterface};
use App\Services\EmployeeContractDetail\{EmployeeContractDetailService, EmployeeContractDetailServiceInterface};
use App\Services\Helper\{HelperService, HelperServiceInterface};
use App\Services\GenerateAbsen\{GenerateAbsenService, GenerateAbsenServiceInterface};
use App\Services\Pph\{PphService, PphServiceInterface};
use App\Services\Firebase\{FirebaseService, FirebaseServiceInterface};

use App\Repositories\Role\{RoleRepository, RoleRepositoryInterface};
use App\Repositories\Permission\{PermissionRepository, PermissionRepositoryInterface};
use App\Repositories\Department\{DepartmentRepository, DepartmentRepositoryInterface};
use App\Repositories\Education\{EducationRepository, EducationRepositoryInterface};
use App\Repositories\Position\{PositionRepository, PositionRepositoryInterface};
use App\Repositories\Religion\{ReligionRepository, ReligionRepositoryInterface};
use App\Repositories\Sex\{SexRepository, SexRepositoryInterface};
use App\Repositories\Tax\{TaxRepository, TaxRepositoryInterface};
use App\Repositories\User\{UserRepository, UserRepositoryInterface};
use App\Repositories\Unit\{UnitRepository, UnitRepositoryInterface};
use App\Repositories\StatusEmployment\{StatusEmploymentRepository, StatusEmploymentRepositoryInterface};
use App\Repositories\ContractType\{ContractTypeRepository, ContractTypeRepositoryInterface};
use App\Repositories\PayrollComponent\{PayrollComponentRepository, PayrollComponentRepositoryInterface};
use App\Repositories\Job\{JobRepository, JobRepositoryInterface};
use App\Repositories\SkillType\{SkillTypeRepository, SkillTypeRepositoryInterface};
use App\Repositories\Relationship\{RelationshipRepository, RelationshipRepositoryInterface};
use App\Repositories\IdentityType\{IdentityTypeRepository, IdentityTypeRepositoryInterface};
use App\Repositories\MaritalStatus\{MaritalStatusRepository, MaritalStatusRepositoryInterface};
use App\Repositories\LegalityType\{LegalityTypeRepository, LegalityTypeRepositoryInterface};
use App\Repositories\Province\{ProvinceRepository, ProvinceRepositoryInterface};
use App\Repositories\City\{CityRepository, CityRepositoryInterface};
use App\Repositories\District\{DistrictRepository, DistrictRepositoryInterface};
use App\Repositories\Village\{VillageRepository, VillageRepositoryInterface};
use App\Repositories\Employee\{EmployeeRepository, EmployeeRepositoryInterface};
use App\Repositories\EmployeeOrganization\{EmployeeOrganizationRepository, EmployeeOrganizationRepositoryInterface};
use App\Repositories\EmployeeExperience\{EmployeeExperienceRepository, EmployeeExperienceRepositoryInterface};
use App\Repositories\EmployeeEducation\{EmployeeEducationRepository, EmployeeEducationRepositoryInterface};
use App\Repositories\EmployeePositionHistory\{EmployeePositionHistoryRepository, EmployeePositionHistoryRepositoryInterface};
use App\Repositories\EmployeeLegality\{EmployeeLegalityRepository, EmployeeLegalityRepositoryInterface};
use App\Repositories\EmployeeFamily\{EmployeeFamilyRepository, EmployeeFamilyRepositoryInterface};
use App\Repositories\EmployeeCertificate\{EmployeeCertificateRepository, EmployeeCertificateRepositoryInterface};
use App\Repositories\EmployeeSkill\{EmployeeSkillRepository, EmployeeSkillRepositoryInterface};
use App\Repositories\LeaveType\{LeaveTypeRepository, LeaveTypeRepositoryInterface};
use App\Repositories\LeaveStatus\{LeaveStatusRepository, LeaveStatusRepositoryInterface};
use App\Repositories\Leave\{LeaveRepository, LeaveRepositoryInterface};
use App\Repositories\LeaveHistory\{LeaveHistoryRepository, LeaveHistoryRepositoryInterface};
use App\Repositories\LeaveApproval\{LeaveApprovalRepository, LeaveApprovalRepositoryInterface};
use App\Repositories\ShiftGroup\{ShiftGroupRepository, ShiftGroupRepositoryInterface};
use App\Repositories\Shift\{ShiftRepository, ShiftRepositoryInterface};
use App\Repositories\ShiftSchedule\{ShiftScheduleRepository, ShiftScheduleRepositoryInterface};
use App\Repositories\LogFinger\{LogFingerRepository, LogFingerRepositoryInterface};
use App\Repositories\LogFingerTemp\{LogFingerTempRepository, LogFingerTempRepositoryInterface};
use App\Repositories\OvertimeStatus\{OvertimeStatusRepository, OvertimeStatusRepositoryInterface};
use App\Repositories\Overtime\{OvertimeRepository, OvertimeRepositoryInterface};
use App\Repositories\EmployeeContract\{EmployeeContractRepository, EmployeeContractRepositoryInterface};
use App\Repositories\EmployeeContractDetail\{EmployeeContractDetailRepository, EmployeeContractDetailRepositoryInterface};
use App\Repositories\Helper\{HelperRepository, HelperRepositoryInterface};
use App\Repositories\GenerateAbsen\{GenerateAbsenRepository, GenerateAbsenRepositoryInterface};
use App\Repositories\Pph\{PphRepository, PphRepositoryInterface};

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // Role
        $this->app->bind(RoleRepositoryInterface::class, RoleRepository::class);
        $this->app->bind(RoleServiceInterface::class, RoleService::class);

        // Permission
        $this->app->bind(PermissionRepositoryInterface::class, PermissionRepository::class);
        $this->app->bind(PermissionServiceInterface::class, PermissionService::class);

        // Department
        $this->app->bind(DepartmentRepositoryInterface::class, DepartmentRepository::class);
        $this->app->bind(DepartmentServiceInterface::class, DepartmentService::class);

        // Education
        $this->app->bind(EducationRepositoryInterface::class, EducationRepository::class);
        $this->app->bind(EducationServiceInterface::class, EducationService::class);

        // Position
        $this->app->bind(PositionRepositoryInterface::class, PositionRepository::class);
        $this->app->bind(PositionServiceInterface::class, PositionService::class);

        // Religion
        $this->app->bind(ReligionRepositoryInterface::class, ReligionRepository::class);
        $this->app->bind(ReligionServiceInterface::class, ReligionService::class);

        // Sex
        $this->app->bind(SexRepositoryInterface::class, SexRepository::class);
        $this->app->bind(SexServiceInterface::class, SexService::class);

        // Tax
        $this->app->bind(TaxRepositoryInterface::class, TaxRepository::class);
        $this->app->bind(TaxServiceInterface::class, TaxService::class);

        // User
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(UserServiceInterface::class, UserService::class);

        // Unit
        $this->app->bind(UnitRepositoryInterface::class, UnitRepository::class);
        $this->app->bind(UnitServiceInterface::class, UnitService::class);

        // Status Employment
        $this->app->bind(StatusEmploymentRepositoryInterface::class, StatusEmploymentRepository::class);
        $this->app->bind(StatusEmploymentServiceInterface::class, StatusEmploymentService::class);

        // Contract Type
        $this->app->bind(ContractTypeRepositoryInterface::class, ContractTypeRepository::class);
        $this->app->bind(ContractTypeServiceInterface::class, ContractTypeService::class);

        // Payroll Component
        $this->app->bind(PayrollComponentRepositoryInterface::class, PayrollComponentRepository::class);
        $this->app->bind(PayrollComponentServiceInterface::class, PayrollComponentService::class);

        // Job
        $this->app->bind(JobRepositoryInterface::class, JobRepository::class);
        $this->app->bind(JobServiceInterface::class, JobService::class);

        // Skill Type
        $this->app->bind(SkillTypeRepositoryInterface::class, SkillTypeRepository::class);
        $this->app->bind(SkillTypeServiceInterface::class, SkillTypeService::class);

        // Relationship
        $this->app->bind(RelationshipRepositoryInterface::class, RelationshipRepository::class);
        $this->app->bind(RelationshipServiceInterface::class, RelationshipService::class);

        // Identity Type
        $this->app->bind(IdentityTypeRepositoryInterface::class, IdentityTypeRepository::class);
        $this->app->bind(IdentityTypeServiceInterface::class, IdentityTypeService::class);

        // Marital Status
        $this->app->bind(MaritalStatusRepositoryInterface::class, MaritalStatusRepository::class);
        $this->app->bind(MaritalStatusServiceInterface::class, MaritalStatusService::class);

        // Legality Status
        $this->app->bind(LegalityTypeRepositoryInterface::class, LegalityTypeRepository::class);
        $this->app->bind(LegalityTypeServiceInterface::class, LegalityTypeService::class);

        // Province
        $this->app->bind(ProvinceRepositoryInterface::class, ProvinceRepository::class);
        $this->app->bind(ProvinceServiceInterface::class, ProvinceService::class);

        // City
        $this->app->bind(CityRepositoryInterface::class, CityRepository::class);
        $this->app->bind(CityServiceInterface::class, CityService::class);

        // District
        $this->app->bind(DistrictRepositoryInterface::class, DistrictRepository::class);
        $this->app->bind(DistrictServiceInterface::class, DistrictService::class);

        // Village
        $this->app->bind(VillageRepositoryInterface::class, VillageRepository::class);
        $this->app->bind(VillageServiceInterface::class, VillageService::class);

        // Employee
        $this->app->bind(EmployeeRepositoryInterface::class, EmployeeRepository::class);
        $this->app->bind(EmployeeServiceInterface::class, EmployeeService::class);

        // Employee Organization
        $this->app->bind(EmployeeOrganizationRepositoryInterface::class, EmployeeOrganizationRepository::class);
        $this->app->bind(EmployeeOrganizationServiceInterface::class, EmployeeOrganizationService::class);

        // Employee Experience
        $this->app->bind(EmployeeExperienceRepositoryInterface::class, EmployeeExperienceRepository::class);
        $this->app->bind(EmployeeExperienceServiceInterface::class, EmployeeExperienceService::class);

        // Employee Education
        $this->app->bind(EmployeeEducationRepositoryInterface::class, EmployeeEducationRepository::class);
        $this->app->bind(EmployeeEducationServiceInterface::class, EmployeeEducationService::class);

        // Employee Position History
        $this->app->bind(EmployeePositionHistoryRepositoryInterface::class, EmployeePositionHistoryRepository::class);
        $this->app->bind(EmployeePositionHistoryServiceInterface::class, EmployeePositionHistoryService::class);

        // Employee Legality
        $this->app->bind(EmployeeLegalityRepositoryInterface::class, EmployeeLegalityRepository::class);
        $this->app->bind(EmployeeLegalityServiceInterface::class, EmployeeLegalityService::class);

        // Employee Family
        $this->app->bind(EmployeeFamilyRepositoryInterface::class, EmployeeFamilyRepository::class);
        $this->app->bind(EmployeeFamilyServiceInterface::class, EmployeeFamilyService::class);

        // Employee Certificate
        $this->app->bind(EmployeeCertificateRepositoryInterface::class, EmployeeCertificateRepository::class);
        $this->app->bind(EmployeeCertificateServiceInterface::class, EmployeeCertificateService::class);

        // Employee Skill
        $this->app->bind(EmployeeSkillRepositoryInterface::class, EmployeeSkillRepository::class);
        $this->app->bind(EmployeeSkillServiceInterface::class, EmployeeSkillService::class);

        // Leave Type
        $this->app->bind(LeaveTypeRepositoryInterface::class, LeaveTypeRepository::class);
        $this->app->bind(LeaveTypeServiceInterface::class, LeaveTypeService::class);

        // Leave Status
        $this->app->bind(LeaveStatusRepositoryInterface::class, LeaveStatusRepository::class);
        $this->app->bind(LeaveStatusServiceInterface::class, LeaveStatusService::class);

        // Leave
        $this->app->bind(LeaveRepositoryInterface::class, LeaveRepository::class);
        $this->app->bind(LeaveServiceInterface::class, LeaveService::class);

        // Leave History
        $this->app->bind(LeaveHistoryRepositoryInterface::class, LeaveHistoryRepository::class);
        $this->app->bind(LeaveHistoryServiceInterface::class, LeaveHistoryService::class);

        // Leave Approval
        $this->app->bind(LeaveApprovalRepositoryInterface::class, LeaveApprovalRepository::class);
        $this->app->bind(LeaveApprovalServiceInterface::class, LeaveApprovalService::class);

        // Shift Group
        $this->app->bind(ShiftGroupRepositoryInterface::class, ShiftGroupRepository::class);
        $this->app->bind(ShiftGroupServiceInterface::class, ShiftGroupService::class);

        // Shift
        $this->app->bind(ShiftRepositoryInterface::class, ShiftRepository::class);
        $this->app->bind(ShiftServiceInterface::class, ShiftService::class);

        // Shift Schedule
        $this->app->bind(ShiftScheduleRepositoryInterface::class, ShiftScheduleRepository::class);
        $this->app->bind(ShiftScheduleServiceInterface::class, ShiftScheduleService::class);

        // Log Finger
        $this->app->bind(LogFingerRepositoryInterface::class, LogFingerRepository::class);
        $this->app->bind(LogFingerServiceInterface::class, LogFingerService::class);

        // Log Finger Temp
        $this->app->bind(LogFingerTempRepositoryInterface::class, LogFingerTempRepository::class);
        $this->app->bind(LogFingerTempServiceInterface::class, LogFingerTempService::class);

        // Overtime Status
        $this->app->bind(OvertimeStatusRepositoryInterface::class, OvertimeStatusRepository::class);
        $this->app->bind(OvertimeStatusServiceInterface::class, OvertimeStatusService::class);

        // Overtime
        $this->app->bind(OvertimeRepositoryInterface::class, OvertimeRepository::class);
        $this->app->bind(OvertimeServiceInterface::class, OvertimeService::class);

        // Employee Contract
        $this->app->bind(EmployeeContractRepositoryInterface::class, EmployeeContractRepository::class);
        $this->app->bind(EmployeeContractServiceInterface::class, EmployeeContractService::class);

        // Employee Contract Detail
        $this->app->bind(EmployeeContractDetailRepositoryInterface::class, EmployeeContractDetailRepository::class);
        $this->app->bind(EmployeeContractDetailServiceInterface::class, EmployeeContractDetailService::class);

        // Helper
        $this->app->bind(HelperRepositoryInterface::class, HelperRepository::class);
        $this->app->bind(HelperServiceInterface::class, HelperService::class);

        // Generate Absen
        $this->app->bind(GenerateAbsenRepositoryInterface::class, GenerateAbsenRepository::class);
        $this->app->bind(GenerateAbsenServiceInterface::class, GenerateAbsenService::class);

        // PPH
        $this->app->bind(PphRepositoryInterface::class, PphRepository::class);
        $this->app->bind(PphServiceInterface::class, PphService::class);

        // Firebase
        $this->app->bind(FirebaseServiceInterface::class, FirebaseService::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
