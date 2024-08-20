<?php

use App\Constants\RoutePermissions;
use App\Http\Controllers\Api\PropertyController;
use Illuminate\Support\Facades\Route;

Route::get('properties', [PropertyController::class, 'index'])->middleware(RoutePermissions::READ);
Route::post('properties', [PropertyController::class, 'store'])->middleware(RoutePermissions::CREATE);
Route::get('properties/{id}', [PropertyController::class, 'show'])->middleware(RoutePermissions::READ);
Route::put('properties/{id}', [PropertyController::class, 'update'])->middleware(RoutePermissions::UPDATE);
Route::delete('properties/{id}', [PropertyController::class, 'destroy'])->middleware(RoutePermissions::DELETE);
