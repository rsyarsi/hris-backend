<?php

namespace App\Providers;

// use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use App\Services\Job\{JobService, JobServiceInterface};
use App\Services\Pph\{PphService, PphServiceInterface};
use App\Services\Sex\{SexService, SexServiceInterface};
use App\Services\Tax\{TaxService, TaxServiceInterface};
use App\Services\Ump\{UmpService, UmpServiceInterface};
use App\Services\City\{CityService, CityServiceInterface};
use App\Services\Role\{RoleService, RoleServiceInterface};
use App\Services\Unit\{UnitService, UnitServiceInterface};
use App\Services\User\{UserService, UserServiceInterface};
use App\Services\Leave\{LeaveService, LeaveServiceInterface};
use App\Services\Shift\{ShiftService, ShiftServiceInterface};
use App\Services\Helper\{HelperService, HelperServiceInterface};
use App\Services\Village\{VillageService, VillageServiceInterface};
use App\Services\District\{DistrictService, DistrictServiceInterface};
use App\Services\Employee\{EmployeeService, EmployeeServiceInterface};
use App\Services\Firebase\{FirebaseService, FirebaseServiceInterface};
use App\Services\Mutation\{MutationService, MutationServiceInterface};
use App\Services\Overtime\{OvertimeService, OvertimeServiceInterface};
use App\Services\Position\{PositionService, PositionServiceInterface};
use App\Services\Province\{ProvinceService, ProvinceServiceInterface};
use App\Services\Religion\{ReligionService, ReligionServiceInterface};
use App\Services\Deduction\{DeductionService, DeductionServiceInterface};
use App\Services\Education\{EducationService, EducationServiceInterface};
use App\Services\LeaveType\{LeaveTypeService, LeaveTypeServiceInterface};
use App\Services\LogFinger\{LogFingerService, LogFingerServiceInterface};
use App\Services\SkillType\{SkillTypeService, SkillTypeServiceInterface};
use App\Services\Department\{DepartmentService, DepartmentServiceInterface};
use App\Services\Permission\{PermissionService, PermissionServiceInterface};
use App\Services\ShiftGroup\{ShiftGroupService, ShiftGroupServiceInterface};
use App\Services\CatatanCuti\{CatatanCutiService, CatatanCutiServiceInterface};
use App\Services\Information\{InformationService, InformationServiceInterface};
use App\Services\LeaveStatus\{LeaveStatusService, LeaveStatusServiceInterface};
use App\Services\ContractType\{ContractTypeService, ContractTypeServiceInterface};
use App\Services\IdentityType\{IdentityTypeService, IdentityTypeServiceInterface};
use App\Services\LeaveHistory\{LeaveHistoryService, LeaveHistoryServiceInterface};
use App\Services\LegalityType\{LegalityTypeService, LegalityTypeServiceInterface};
use App\Services\Pengembalian\{PengembalianService, PengembalianServiceInterface};
use App\Services\Relationship\{RelationshipService, RelationshipServiceInterface};
use App\Services\EmployeeSkill\{EmployeeSkillService, EmployeeSkillServiceInterface};
use App\Services\GenerateAbsen\{GenerateAbsenService, GenerateAbsenServiceInterface};
use App\Services\LeaveApproval\{LeaveApprovalService, LeaveApprovalServiceInterface};
use App\Services\LogFingerTemp\{LogFingerTempService, LogFingerTempServiceInterface};
use App\Services\MaritalStatus\{MaritalStatusService, MaritalStatusServiceInterface};
use App\Services\OrderOvertime\{OrderOvertimeService, OrderOvertimeServiceInterface};
use App\Services\ShiftSchedule\{ShiftScheduleService, ShiftScheduleServiceInterface};
use App\Services\AdjustmentCuti\{AdjustmentCutiService, AdjustmentCutiServiceInterface};
use App\Services\EmployeeFamily\{EmployeeFamilyService, EmployeeFamilyServiceInterface};
use App\Services\OvertimeStatus\{OvertimeStatusService, OvertimeStatusServiceInterface};
use App\Services\GeneratePayroll\{GeneratePayrollService, GeneratePayrollServiceInterface};
use App\Services\OvertimeHistory\{OvertimeHistoryService, OvertimeHistoryServiceInterface};
use App\Services\SuratPeringatan\{SuratPeringatanService, SuratPeringatanServiceInterface};
use App\Services\EmployeeContract\{EmployeeContractService, EmployeeContractServiceInterface};
use App\Services\EmployeeLegality\{EmployeeLegalityService, EmployeeLegalityServiceInterface};
use App\Services\PayrollComponent\{PayrollComponentService, PayrollComponentServiceInterface};
use App\Services\StatusEmployment\{StatusEmploymentService, StatusEmploymentServiceInterface};
use App\Services\EmployeeEducation\{EmployeeEducationService, EmployeeEducationServiceInterface};
use App\Services\PromotionDemotion\{PromotionDemotionService, PromotionDemotionServiceInterface};
use App\Services\TimesheetOvertime\{TimesheetOvertimeService, TimesheetOvertimeServiceInterface};
use App\Services\EmployeeExperience\{EmployeeExperienceService, EmployeeExperienceServiceInterface};
use App\Services\EmployeeCertificate\{EmployeeCertificateService, EmployeeCertificateServiceInterface};
use App\Services\EmployeeOrganization\{EmployeeOrganizationService, EmployeeOrganizationServiceInterface};
use App\Services\ShiftScheduleExchange\{ShiftScheduleExchangeService, ShiftScheduleExchangeServiceInterface};
use App\Services\EmployeeContractDetail\{EmployeeContractDetailService, EmployeeContractDetailServiceInterface};
use App\Services\EmployeePositionHistory\{EmployeePositionHistoryService, EmployeePositionHistoryServiceInterface};
use App\Services\JobVacancy\{JobVacancyService, JobVacancyServiceInterface};
use App\Services\Ethnic\{EthnicService, EthnicServiceInterface};
use App\Services\CandidateAccount\{CandidateAccountService, CandidateAccountServiceInterface};
use App\Services\Candidate\{CandidateService, CandidateServiceInterface};
use App\Services\EmergencyContactCandidate\{EmergencyContactCandidateService, EmergencyContactCandidateServiceInterface};
use App\Services\FamilyInformationCandidate\{FamilyInformationCandidateService, FamilyInformationCandidateServiceInterface};
use App\Services\FamilyMemberCandidate\{FamilyMemberCandidateService, FamilyMemberCandidateServiceInterface};
use App\Services\EducationBackgroundCandidate\{EducationBackgroundCandidateService, EducationBackgroundCandidateServiceInterface};

use App\Repositories\Job\{JobRepository, JobRepositoryInterface};
use App\Repositories\Pph\{PphRepository, PphRepositoryInterface};
use App\Repositories\Sex\{SexRepository, SexRepositoryInterface};
use App\Repositories\Tax\{TaxRepository, TaxRepositoryInterface};
use App\Repositories\Ump\{UmpRepository, UmpRepositoryInterface};
use App\Repositories\City\{CityRepository, CityRepositoryInterface};
use App\Repositories\Role\{RoleRepository, RoleRepositoryInterface};
use App\Repositories\Unit\{UnitRepository, UnitRepositoryInterface};
use App\Repositories\User\{UserRepository, UserRepositoryInterface};
use App\Repositories\Leave\{LeaveRepository, LeaveRepositoryInterface};
use App\Repositories\Shift\{ShiftRepository, ShiftRepositoryInterface};
use App\Repositories\Helper\{HelperRepository, HelperRepositoryInterface};
use App\Repositories\Village\{VillageRepository, VillageRepositoryInterface};
use App\Repositories\District\{DistrictRepository, DistrictRepositoryInterface};
use App\Repositories\Employee\{EmployeeRepository, EmployeeRepositoryInterface};
use App\Repositories\Mutation\{MutationRepository, MutationRepositoryInterface};
use App\Repositories\Overtime\{OvertimeRepository, OvertimeRepositoryInterface};
use App\Repositories\Position\{PositionRepository, PositionRepositoryInterface};
use App\Repositories\Province\{ProvinceRepository, ProvinceRepositoryInterface};
use App\Repositories\Religion\{ReligionRepository, ReligionRepositoryInterface};
use App\Repositories\Deduction\{DeductionRepository, DeductionRepositoryInterface};
use App\Repositories\Education\{EducationRepository, EducationRepositoryInterface};
use App\Repositories\LeaveType\{LeaveTypeRepository, LeaveTypeRepositoryInterface};
use App\Repositories\LogFinger\{LogFingerRepository, LogFingerRepositoryInterface};
use App\Repositories\SkillType\{SkillTypeRepository, SkillTypeRepositoryInterface};
use App\Repositories\Department\{DepartmentRepository, DepartmentRepositoryInterface};
use App\Repositories\Permission\{PermissionRepository, PermissionRepositoryInterface};
use App\Repositories\ShiftGroup\{ShiftGroupRepository, ShiftGroupRepositoryInterface};
use App\Repositories\CatatanCuti\{CatatanCutiRepository, CatatanCutiRepositoryInterface};
use App\Repositories\Information\{InformationRepository, InformationRepositoryInterface};
use App\Repositories\LeaveStatus\{LeaveStatusRepository, LeaveStatusRepositoryInterface};
use App\Repositories\ContractType\{ContractTypeRepository, ContractTypeRepositoryInterface};
use App\Repositories\IdentityType\{IdentityTypeRepository, IdentityTypeRepositoryInterface};
use App\Repositories\LeaveHistory\{LeaveHistoryRepository, LeaveHistoryRepositoryInterface};
use App\Repositories\LegalityType\{LegalityTypeRepository, LegalityTypeRepositoryInterface};
use App\Repositories\Pengembalian\{PengembalianRepository, PengembalianRepositoryInterface};
use App\Repositories\Relationship\{RelationshipRepository, RelationshipRepositoryInterface};
use App\Repositories\EmployeeSkill\{EmployeeSkillRepository, EmployeeSkillRepositoryInterface};
use App\Repositories\GenerateAbsen\{GenerateAbsenRepository, GenerateAbsenRepositoryInterface};
use App\Repositories\LeaveApproval\{LeaveApprovalRepository, LeaveApprovalRepositoryInterface};
use App\Repositories\LogFingerTemp\{LogFingerTempRepository, LogFingerTempRepositoryInterface};
use App\Repositories\MaritalStatus\{MaritalStatusRepository, MaritalStatusRepositoryInterface};
use App\Repositories\OrderOvertime\{OrderOvertimeRepository, OrderOvertimeRepositoryInterface};
use App\Repositories\ShiftSchedule\{ShiftScheduleRepository, ShiftScheduleRepositoryInterface};
use App\Repositories\AdjustmentCuti\{AdjustmentCutiRepository, AdjustmentCutiRepositoryInterface};
use App\Repositories\EmployeeFamily\{EmployeeFamilyRepository, EmployeeFamilyRepositoryInterface};
use App\Repositories\OvertimeStatus\{OvertimeStatusRepository, OvertimeStatusRepositoryInterface};
use App\Repositories\GeneratePayroll\{GeneratePayrollRepository, GeneratePayrollRepositoryInterface};
use App\Repositories\OvertimeHistory\{OvertimeHistoryRepository, OvertimeHistoryRepositoryInterface};
use App\Repositories\SuratPeringatan\{SuratPeringatanRepository, SuratPeringatanRepositoryInterface};
use App\Repositories\EmployeeContract\{EmployeeContractRepository, EmployeeContractRepositoryInterface};
use App\Repositories\EmployeeLegality\{EmployeeLegalityRepository, EmployeeLegalityRepositoryInterface};
use App\Repositories\PayrollComponent\{PayrollComponentRepository, PayrollComponentRepositoryInterface};
use App\Repositories\StatusEmployment\{StatusEmploymentRepository, StatusEmploymentRepositoryInterface};
use App\Repositories\EmployeeEducation\{EmployeeEducationRepository, EmployeeEducationRepositoryInterface};
use App\Repositories\PromotionDemotion\{PromotionDemotionRepository, PromotionDemotionRepositoryInterface};
use App\Repositories\TimesheetOvertime\{TimesheetOvertimeRepository, TimesheetOvertimeRepositoryInterface};
use App\Repositories\EmployeeExperience\{EmployeeExperienceRepository, EmployeeExperienceRepositoryInterface};
use App\Repositories\EmployeeCertificate\{EmployeeCertificateRepository, EmployeeCertificateRepositoryInterface};
use App\Repositories\EmployeeOrganization\{EmployeeOrganizationRepository, EmployeeOrganizationRepositoryInterface};
use App\Repositories\ShiftScheduleExchange\{ShiftScheduleExchangeRepository, ShiftScheduleExchangeRepositoryInterface};
use App\Repositories\EmployeeContractDetail\{EmployeeContractDetailRepository, EmployeeContractDetailRepositoryInterface};
use App\Repositories\EmployeePositionHistory\{EmployeePositionHistoryRepository, EmployeePositionHistoryRepositoryInterface};
use App\Repositories\JobVacancy\{JobVacancyRepository, JobVacancyRepositoryInterface};
use App\Repositories\Ethnic\{EthnicRepository, EthnicRepositoryInterface};
use App\Repositories\CandidateAccount\{CandidateAccountRepository, CandidateAccountRepositoryInterface};
use App\Repositories\Candidate\{CandidateRepository, CandidateRepositoryInterface};
use App\Repositories\EmergencyContactCandidate\{EmergencyContactCandidateRepository, EmergencyContactCandidateRepositoryInterface};
use App\Repositories\FamilyInformationCandidate\{FamilyInformationCandidateRepository, FamilyInformationCandidateRepositoryInterface};
use App\Repositories\FamilyMemberCandidate\{FamilyMemberCandidateRepository, FamilyMemberCandidateRepositoryInterface};
use App\Repositories\EducationBackgroundCandidate\{EducationBackgroundCandidateRepository, EducationBackgroundCandidateRepositoryInterface};

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

        // Shift Schedule Exchange
        $this->app->bind(ShiftScheduleExchangeRepositoryInterface::class, ShiftScheduleExchangeRepository::class);
        $this->app->bind(ShiftScheduleExchangeServiceInterface::class, ShiftScheduleExchangeService::class);

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

        // OrderOvertime
        $this->app->bind(OrderOvertimeRepositoryInterface::class, OrderOvertimeRepository::class);
        $this->app->bind(OrderOvertimeServiceInterface::class, OrderOvertimeService::class);

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

        // Generate Payroll
        $this->app->bind(GeneratePayrollRepositoryInterface::class, GeneratePayrollRepository::class);
        $this->app->bind(GeneratePayrollServiceInterface::class, GeneratePayrollService::class);

        // PPH
        $this->app->bind(PphRepositoryInterface::class, PphRepository::class);
        $this->app->bind(PphServiceInterface::class, PphService::class);

        // Deduction
        $this->app->bind(DeductionRepositoryInterface::class, DeductionRepository::class);
        $this->app->bind(DeductionServiceInterface::class, DeductionService::class);

        // Deduction
        $this->app->bind(UmpRepositoryInterface::class, UmpRepository::class);
        $this->app->bind(UmpServiceInterface::class, UmpService::class);

        // Adjustment Cuti
        $this->app->bind(AdjustmentCutiRepositoryInterface::class, AdjustmentCutiRepository::class);
        $this->app->bind(AdjustmentCutiServiceInterface::class, AdjustmentCutiService::class);

        // Catatan Cuti
        $this->app->bind(CatatanCutiRepositoryInterface::class, CatatanCutiRepository::class);
        $this->app->bind(CatatanCutiServiceInterface::class, CatatanCutiService::class);

        // Timesheet Overtime
        $this->app->bind(TimesheetOvertimeRepositoryInterface::class, TimesheetOvertimeRepository::class);
        $this->app->bind(TimesheetOvertimeServiceInterface::class, TimesheetOvertimeService::class);

        // Overtime History
        $this->app->bind(OvertimeHistoryRepositoryInterface::class, OvertimeHistoryRepository::class);
        $this->app->bind(OvertimeHistoryServiceInterface::class, OvertimeHistoryService::class);

        // Surat Peringatan
        $this->app->bind(SuratPeringatanRepositoryInterface::class, SuratPeringatanRepository::class);
        $this->app->bind(SuratPeringatanServiceInterface::class, SuratPeringatanService::class);

        // Promotion Demotion
        $this->app->bind(PromotionDemotionRepositoryInterface::class, PromotionDemotionRepository::class);
        $this->app->bind(PromotionDemotionServiceInterface::class, PromotionDemotionService::class);

        // Mutation
        $this->app->bind(MutationRepositoryInterface::class, MutationRepository::class);
        $this->app->bind(MutationServiceInterface::class, MutationService::class);

        // Pengembalian
        $this->app->bind(PengembalianRepositoryInterface::class, PengembalianRepository::class);
        $this->app->bind(PengembalianServiceInterface::class, PengembalianService::class);

        // Information
        $this->app->bind(InformationRepositoryInterface::class, InformationRepository::class);
        $this->app->bind(InformationServiceInterface::class, InformationService::class);

        // Job Vacancy / Lowongan Kerja
        $this->app->bind(JobVacancyRepositoryInterface::class, JobVacancyRepository::class);
        $this->app->bind(JobVacancyServiceInterface::class, JobVacancyService::class);

        // Ethnic
        $this->app->bind(EthnicRepositoryInterface::class, EthnicRepository::class);
        $this->app->bind(EthnicServiceInterface::class, EthnicService::class);

        // Candidate Account
        $this->app->bind(CandidateAccountRepositoryInterface::class, CandidateAccountRepository::class);
        $this->app->bind(CandidateAccountServiceInterface::class, CandidateAccountService::class);

        // Candidate
        $this->app->bind(CandidateRepositoryInterface::class, CandidateRepository::class);
        $this->app->bind(CandidateServiceInterface::class, CandidateService::class);

        // Emergency Contact Candidate
        $this->app->bind(EmergencyContactCandidateRepositoryInterface::class, EmergencyContactCandidateRepository::class);
        $this->app->bind(EmergencyContactCandidateServiceInterface::class, EmergencyContactCandidateService::class);

        // Family Information Candidate
        $this->app->bind(FamilyInformationCandidateRepositoryInterface::class, FamilyInformationCandidateRepository::class);
        $this->app->bind(FamilyInformationCandidateServiceInterface::class, FamilyInformationCandidateService::class);

        // Family Member Candidate
        $this->app->bind(FamilyMemberCandidateRepositoryInterface::class, FamilyMemberCandidateRepository::class);
        $this->app->bind(FamilyMemberCandidateServiceInterface::class, FamilyMemberCandidateService::class);

        // Education Background Candidate
        $this->app->bind(EducationBackgroundCandidateRepositoryInterface::class, EducationBackgroundCandidateRepository::class);
        $this->app->bind(EducationBackgroundCandidateServiceInterface::class, EducationBackgroundCandidateService::class);

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
        // if($this->app->environment('production')) {
        //     URL::forceScheme('https');
        // }
    }
}
