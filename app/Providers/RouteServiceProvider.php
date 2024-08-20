<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();
    }

    /**
     * Define the routes for the application.
     *
     * @return void
     */
    public function map()
    {
        $this->mapApiRoutes();
        $this->mapWebRoutes();
    }

    /**
     * Define the "web" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @return void
     */
    protected function mapWebRoutes()
    {
        Route::middleware('web')
            ->namespace($this->namespace)
            ->group(base_path('routes/web.php'));
    }

    /**
     * Define the "api" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapApiRoutes()
    {
        Route::prefix('api')
            ->middleware('api')
            ->namespace($this->namespace)
            ->group(function () {
                Route::middleware('auth:sanctum')->group(function () {
                    $this->loadRoutesFrom(base_path('routes/api/property.php'));
                    $this->loadRoutesFrom(base_path('routes/api/image.php'));
                    $this->loadRoutesFrom(base_path('routes/api/agent.php'));
                    $this->loadRoutesFrom(base_path('routes/api/appointment.php'));
                    $this->loadRoutesFrom(base_path('routes/api/country.php'));
                    $this->loadRoutesFrom(base_path('routes/api/state.php'));
                    $this->loadRoutesFrom(base_path('routes/api/city.php'));
                });
            });
    }
}
