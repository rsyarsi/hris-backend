<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\Education\{EducationService, EducationServiceInterface};
use App\Services\Department\{DepartmentService, DepartmentServiceInterface};
use App\Services\Position\{PositionService, PositionServiceInterface};
use App\Repositories\Department\{DepartmentRepository, DepartmentRepositoryInterface};
use App\Repositories\Education\{EducationRepository, EducationRepositoryInterface};
use App\Repositories\Position\{PositionRepository, PositionRepositoryInterface};

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
