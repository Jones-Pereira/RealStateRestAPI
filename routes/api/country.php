<?php

use App\Constants\RoutePermissions;
use App\Http\Controllers\Api\CountryController;
use Illuminate\Support\Facades\Route;

Route::get('countries', [CountryController::class, 'index'])->middleware(RoutePermissions::READ);
Route::post('countries', [CountryController::class, 'store'])->middleware(RoutePermissions::CREATE);
Route::get('countries/{id}', [CountryController::class, 'show'])->middleware(RoutePermissions::READ);
Route::put('countries/{id}', [CountryController::class, 'update'])->middleware(RoutePermissions::UPDATE);
Route::delete('countries/{id}', [CountryController::class, 'destroy'])->middleware(RoutePermissions::DELETE);
