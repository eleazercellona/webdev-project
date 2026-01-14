<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View; 
use App\Models\Post; 

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
        View::share('globalTotalContent', Post::count());
        View::share('globalPublishedCount', Post::where('is_published', true)->count());
        View::share('dashboardPosts', Post::with('user')->latest()->take(10)->get());
        View::share('globalDraftCount', Post::where('is_published', false)->count());
    }
}