<?php

use App\Http\Controllers\Api\StudentController;
use App\Http\Controllers\Api\ClassController;
use App\Http\Controllers\Api\SectionController;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::apiResource('students', StudentController::class);
Route::apiResource('classes', ClassController::class);
Route::apiResource('sections', SectionController::class);
