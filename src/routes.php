<?php

use Illuminate\Support\Facades\Route;
use Kwidoo\Mere\Http\Controllers\MenuController;
use Kwidoo\Mere\Http\Controllers\ResourceController;

Route::middleware('auth:api')->group(function () {
    Route::get('api/menu', MenuController::class);

    Route::middleware('bind.resource')->group(function () {
        Route::get('{resource}', [ResourceController::class, 'index']);
        Route::get('{resource}/{id}', [ResourceController::class, 'show']);
        Route::post('{resource}', [ResourceController::class, 'store']);
        Route::put('{resource}/{id}', [ResourceController::class, 'update']);
        Route::delete('{resource}/{id}', [ResourceController::class, 'destroy']);
    });
});
