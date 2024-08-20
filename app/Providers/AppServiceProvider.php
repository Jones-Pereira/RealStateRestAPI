<?php

namespace App\Providers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
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
        if (app()->environment('local')) {
            DB::listen(function ($query) {
                Log::info('--------------------------------');
                Log::info('Query: '.$query->sql);
                Log::info('Binds: '.json_encode($query->bindings));
                Log::info('Time: '.$query->time);
                Log::info('Query: '.vsprintf(str_replace('?', "'%s'", $query->sql), $query->bindings));
                Log::info('--------------------------------');
            });
        }
    }
}
