<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\Education\{EducationService, EducationServiceInterface};
use App\Services\Department\{DepartmentService, DepartmentServiceInterface};
use App\Services\Position\{PositionService, PositionServiceInterface};
use App\Services\Religion\{ReligionService, ReligionServiceInterface};
use App\Services\Sex\{SexService, SexServiceInterface};
use App\Services\Tax\{TaxService, TaxServiceInterface};
use App\Repositories\Department\{DepartmentRepository, DepartmentRepositoryInterface};
use App\Repositories\Education\{EducationRepository, EducationRepositoryInterface};
use App\Repositories\Position\{PositionRepository, PositionRepositoryInterface};
use App\Repositories\Religion\{ReligionRepository, ReligionRepositoryInterface};
use App\Repositories\Sex\{SexRepository, SexRepositoryInterface};
use App\Repositories\Tax\{TaxRepository, TaxRepositoryInterface};

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
