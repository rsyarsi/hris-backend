<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\Education\{EducationService, EducationServiceInterface};
use App\Services\Department\{DepartmentService, DepartmentServiceInterface};
use App\Services\Position\{PositionService, PositionServiceInterface};
use App\Services\Religion\{ReligionService, ReligionServiceInterface};
use App\Services\Sex\{SexService, SexServiceInterface};
use App\Services\Tax\{TaxService, TaxServiceInterface};
use App\Services\Unit\{UnitService, UnitServiceInterface};
use App\Services\StatusEmployment\{StatusEmploymentService, StatusEmploymentServiceInterface};
use App\Services\Job\{JobService, JobServiceInterface};
use App\Services\IdentityType\{IdentityTypeService, IdentityTypeServiceInterface};
use App\Services\MaritalStatus\{MaritalStatusService, MaritalStatusServiceInterface};
use App\Services\LegalityType\{LegalityTypeService, LegalityTypeServiceInterface};
use App\Services\Province\{ProvinceService, ProvinceServiceInterface};
use App\Services\City\{CityService, CityServiceInterface};
use App\Services\District\{DistrictService, DistrictServiceInterface};
use App\Services\Village\{VillageService, VillageServiceInterface};
use App\Services\Employee\{EmployeeService, EmployeeServiceInterface};
use App\Services\EmployeeOrganization\{EmployeeOrganizationService, EmployeeOrganizationServiceInterface};
use App\Repositories\Department\{DepartmentRepository, DepartmentRepositoryInterface};
use App\Repositories\Education\{EducationRepository, EducationRepositoryInterface};
use App\Repositories\Position\{PositionRepository, PositionRepositoryInterface};
use App\Repositories\Religion\{ReligionRepository, ReligionRepositoryInterface};
use App\Repositories\Sex\{SexRepository, SexRepositoryInterface};
use App\Repositories\Tax\{TaxRepository, TaxRepositoryInterface};
use App\Repositories\Unit\{UnitRepository, UnitRepositoryInterface};
use App\Repositories\StatusEmployment\{StatusEmploymentRepository, StatusEmploymentRepositoryInterface};
use App\Repositories\Job\{JobRepository, JobRepositoryInterface};
use App\Repositories\IdentityType\{IdentityTypeRepository, IdentityTypeRepositoryInterface};
use App\Repositories\MaritalStatus\{MaritalStatusRepository, MaritalStatusRepositoryInterface};
use App\Repositories\LegalityType\{LegalityTypeRepository, LegalityTypeRepositoryInterface};
use App\Repositories\Province\{ProvinceRepository, ProvinceRepositoryInterface};
use App\Repositories\City\{CityRepository, CityRepositoryInterface};
use App\Repositories\District\{DistrictRepository, DistrictRepositoryInterface};
use App\Repositories\Village\{VillageRepository, VillageRepositoryInterface};
use App\Repositories\Employee\{EmployeeRepository, EmployeeRepositoryInterface};
use App\Repositories\EmployeeOrganization\{EmployeeOrganizationRepository, EmployeeOrganizationRepositoryInterface};

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
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

        // Unit
        $this->app->bind(UnitRepositoryInterface::class, UnitRepository::class);
        $this->app->bind(UnitServiceInterface::class, UnitService::class);

        // Status Employment
        $this->app->bind(StatusEmploymentRepositoryInterface::class, StatusEmploymentRepository::class);
        $this->app->bind(StatusEmploymentServiceInterface::class, StatusEmploymentService::class);

        // Job
        $this->app->bind(JobRepositoryInterface::class, JobRepository::class);
        $this->app->bind(JobServiceInterface::class, JobService::class);

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
