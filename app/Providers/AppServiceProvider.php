<?php

namespace App\Providers;

use App\Interfaces\Repositories\CourseGroupRepositoryInterface;
use App\Interfaces\Repositories\CourseRepositoryInterface;
use App\Interfaces\Repositories\EnrollmentRepositoryInterface;
use App\Interfaces\Repositories\UserRepositoryInterface;
use App\Interfaces\Repositories\WishlistRepositoryInterface;
use App\Repositories\EloquentCourseGroupRepository;
use App\Repositories\EloquentCourseRepository;
use App\Repositories\EloquentEnrollmentRepository;
use App\Repositories\EloquentUserRepository;
use App\Repositories\EloquentWishlistRepository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(UserRepositoryInterface::class, EloquentUserRepository::class);
        $this->app->bind(CourseRepositoryInterface::class, EloquentCourseRepository::class);
        $this->app->bind(WishlistRepositoryInterface::class, EloquentWishlistRepository::class);
        $this->app->bind(CourseGroupRepositoryInterface::class, EloquentCourseGroupRepository::class);
        $this->app->bind(EnrollmentRepositoryInterface::class, EloquentEnrollmentRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
