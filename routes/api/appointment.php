<?php

use App\Constants\RoutePermissions;
use App\Http\Controllers\Api\AppointmentController;
use Illuminate\Support\Facades\Route;

Route::get('appointments', [AppointmentController::class, 'index'])->middleware(RoutePermissions::READ);
Route::post('appointments', [AppointmentController::class, 'store'])->middleware(RoutePermissions::CREATE);
Route::get('appointments/{id}', [AppointmentController::class, 'show'])->middleware(RoutePermissions::READ);
Route::put('appointments/{id}', [AppointmentController::class, 'update'])->middleware(RoutePermissions::UPDATE);
Route::delete('appointments/{id}', [AppointmentController::class, 'destroy'])->middleware(RoutePermissions::DELETE);
