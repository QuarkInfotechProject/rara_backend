<?php

namespace Modules\Newsletter\App\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The module namespace to assume when generating URLs to actions.
     */
    protected string $moduleNamespace = 'Modules\Newsletter\App\Http\Controllers';

    /**
     * Called before routes are registered.
     *
     * Register any model bindings or pattern based filters.
     */
    public function boot(): void
    {
        parent::boot();
    }

    /**
     * Define the "web" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     */
    protected function mapWebRoutes(): void
    {
        Route::middleware('web')
            ->namespace($this->moduleNamespace)
            ->group(module_path('Newsletter', '/routes/web.php'));
    }

    public function map(): void
    {
        $this->mapApiRoutes();
        $this->mapUserRoutes();
    }

    /**
     * Define the "api" routes for the application.
     *
     * These routes are typically stateless.
     */
    protected function mapApiRoutes(): void
    {
        Route::prefix('api/admin')
            ->middleware('admin')
            ->namespace($this->moduleNamespace)
            ->group(module_path('Newsletter', 'routes/admin.php'));
    }

     protected function mapUserRoutes(): void
    {
        Route::prefix('api/user')
            ->middleware('user')
            ->namespace($this->moduleNamespace)
            ->group(module_path('Newsletter', 'routes/user.php'));
    }
}
