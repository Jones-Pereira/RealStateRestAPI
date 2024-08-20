<?php

use App\Constants\RoutePermissions;
use App\Http\Controllers\Api\CityController;
use Illuminate\Support\Facades\Route;

Route::get('cities', [CityController::class, 'index'])->middleware(RoutePermissions::READ);
Route::post('cities', [CityController::class, 'store'])->middleware(RoutePermissions::CREATE);
Route::get('cities/{id}', [CityController::class, 'show'])->middleware(RoutePermissions::READ);
Route::put('cities/{id}', [CityController::class, 'update'])->middleware(RoutePermissions::UPDATE);
Route::delete('cities/{id}', [CityController::class, 'destroy'])->middleware(RoutePermissions::DELETE);
