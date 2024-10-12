<?php

namespace App\Providers;

use App\Models\Course;
use App\Models\Document;
use App\Models\Lecture;
use App\Observers\DocumentObserver;
use App\Policies\CoursePolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Document::observe(DocumentObserver::class);
        Gate::policy(Course::class,CoursePolicy::class);
    }
}
