<?php

use App\Constants\RoutePermissions;
use App\Http\Controllers\Api\StateController;
use Illuminate\Support\Facades\Route;

Route::get('states', [StateController::class, 'index'])->middleware(RoutePermissions::READ);
Route::get('states/{id}', [StateController::class, 'show'])->middleware(RoutePermissions::READ);
Route::post('states', [StateController::class, 'store'])->middleware(RoutePermissions::CREATE);
Route::put('states/{id}', [StateController::class, 'update'])->middleware(RoutePermissions::UPDATE);
Route::delete('states/{id}', [StateController::class, 'destroy'])->middleware(RoutePermissions::DELETE);
