<?php

namespace App\Providers;

use App\Interfaces\Repositories\CourseGroupRepositoryInterface;
use App\Interfaces\Repositories\CourseRepositoryInterface;
use App\Interfaces\Repositories\EnrollmentRepositoryInterface;
use App\Interfaces\Repositories\PaymentRepositoryInterface;
use App\Interfaces\Repositories\UserRepositoryInterface;
use App\Interfaces\Repositories\WishlistRepositoryInterface;
use App\Repositories\CourseGroupRepository;
use App\Repositories\CourseRepository;
use App\Repositories\EnrollmentRepository;
use App\Repositories\PaymentRepository;
use App\Repositories\UserRepository;
use App\Repositories\WishlistRepository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(CourseRepositoryInterface::class, CourseRepository::class);
        $this->app->bind(WishlistRepositoryInterface::class, WishlistRepository::class);
        $this->app->bind(CourseGroupRepositoryInterface::class, CourseGroupRepository::class);
        $this->app->bind(EnrollmentRepositoryInterface::class, EnrollmentRepository::class);
        $this->app->bind(PaymentRepositoryInterface::class, PaymentRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
