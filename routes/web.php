<?php

use App\Http\Controllers\EmployeeController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
}); 
Route::get('employees',[EmployeeController::class, 'index']);
Route::post('employees',[EmployeeController::class, 'store']);
Route::put('/employees/{id}', [EmployeeController::class, 'update']);
Route::get('employees/{id}/edit',[EmployeeController::class,'edit']);
Route::delete('/employees/{id}', [EmployeeController::class, 'destroy']);