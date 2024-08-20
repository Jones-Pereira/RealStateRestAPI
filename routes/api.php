<?php

use App\Http\Controllers\Api\RegisterController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;

Route::middleware([
    'api',
    InitializeTenancyByDomain::class,
    PreventAccessFromCentralDomains::class,
])->group(function () {

    Route::get('/tenant-endpoint', function () {
        return response()->json(['message' => 'Tenant '.tenant('id').' is working!']);
    });

    Route::controller(RegisterController::class)->group(function () {
        Route::post('register', 'register');
        Route::post('login', 'login');
    });

    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::middleware('auth:sanctum')->group(function () {
        require base_path('routes/api/property.php');
        require base_path('routes/api/image.php');
        require base_path('routes/api/agent.php');
        require base_path('routes/api/appointment.php');
        require base_path('routes/api/country.php');
        require base_path('routes/api/state.php');
        require base_path('routes/api/city.php');
    });
});
