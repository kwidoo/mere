<?php

use Illuminate\Support\Facades\Route;
use Kwidoo\Mere\Http\Controllers\MenuController;

Route::middleware('auth:api')->group(function () {
    Route::get('api/menu', MenuController::class);
});
