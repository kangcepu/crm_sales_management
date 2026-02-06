<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\DealsController;
use App\Http\Controllers\Api\RolesController;
use App\Http\Controllers\Api\SettingsController;
use App\Http\Controllers\Api\AreasController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\StoreAddressesController;
use App\Http\Controllers\Api\StoreAssignmentsController;
use App\Http\Controllers\Api\StoreConditionsController;
use App\Http\Controllers\Api\StoreMediaController;
use App\Http\Controllers\Api\StoreStatusHistoryController;
use App\Http\Controllers\Api\StoreVisitReportsController;
use App\Http\Controllers\Api\StoreVisitsController;
use App\Http\Controllers\Api\StoresController;
use App\Http\Controllers\Api\UsersController;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login']);
Route::get('/settings', [SettingsController::class, 'index']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [ProfileController::class, 'show']);
    Route::post('/me', [ProfileController::class, 'update']);

    Route::apiResource('users', UsersController::class)->middleware('permission:users.manage');
    Route::apiResource('roles', RolesController::class)->middleware('permission:roles.manage');
    Route::apiResource('areas', AreasController::class)->middleware('permission:areas.manage');

    Route::apiResource('stores', StoresController::class)->middleware('permission:stores.manage');
    Route::apiResource('store-addresses', StoreAddressesController::class)->middleware('permission:stores.manage');
    Route::get('store-assignments', [StoreAssignmentsController::class, 'index'])->middleware('permission:assignments.manage');
    Route::post('store-assignments', [StoreAssignmentsController::class, 'store'])->middleware('permission:assignments.manage');
    Route::get('store-assignments/{storeId}/{userId}/{assignedFrom}', [StoreAssignmentsController::class, 'show'])->middleware('permission:assignments.manage');
    Route::put('store-assignments/{storeId}/{userId}/{assignedFrom}', [StoreAssignmentsController::class, 'update'])->middleware('permission:assignments.manage');
    Route::delete('store-assignments/{storeId}/{userId}/{assignedFrom}', [StoreAssignmentsController::class, 'destroy'])->middleware('permission:assignments.manage');
    Route::apiResource('store-status-history', StoreStatusHistoryController::class)->middleware('permission:status_history.manage');

    Route::apiResource('store-visits', StoreVisitsController::class)->middleware('permission:visits.manage');
    Route::apiResource('store-visit-reports', StoreVisitReportsController::class)->middleware('permission:visit_reports.manage');
    Route::apiResource('store-conditions', StoreConditionsController::class)->middleware('permission:conditions.manage');
    Route::apiResource('store-media', StoreMediaController::class)->middleware('permission:media.manage');
    Route::apiResource('deals', DealsController::class)->middleware('permission:deals.manage');
});
