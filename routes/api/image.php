<?php

use App\Constants\RoutePermissions;
use App\Http\Controllers\Api\ImageController;
use Illuminate\Support\Facades\Route;

Route::get('images', [ImageController::class, 'index'])->middleware(RoutePermissions::READ);
Route::post('images', [ImageController::class, 'store'])->middleware(RoutePermissions::CREATE);
Route::get('images/{id}', [ImageController::class, 'show'])->middleware(RoutePermissions::READ);
Route::put('images/{id}', [ImageController::class, 'update'])->middleware(RoutePermissions::UPDATE);
Route::delete('images/{id}', [ImageController::class, 'destroy'])->middleware(RoutePermissions::DELETE);
