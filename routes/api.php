<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/**
 *  Employeedata Route
 */
Route::prefix('employeedata')->group(function () {
    Route::post('/sync', [\App\Http\Controllers\EmployeeDataController::class, 'process']);
});

