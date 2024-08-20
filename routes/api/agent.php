<?php

use App\Constants\RoutePermissions;
use App\Http\Controllers\Api\AgentController;
use Illuminate\Support\Facades\Route;

Route::get('agents', [AgentController::class, 'index'])->middleware(RoutePermissions::READ);
Route::get('agents/{id}', [AgentController::class, 'show'])->middleware(RoutePermissions::READ);
Route::post('agents', [AgentController::class, 'store'])->middleware(RoutePermissions::CREATE);
Route::put('agents/{id}', [AgentController::class, 'update'])->middleware(RoutePermissions::UPDATE);
Route::delete('agents/{id}', [AgentController::class, 'destroy'])->middleware(RoutePermissions::DELETE);
