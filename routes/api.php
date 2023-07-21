<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});



Route::post('/auth/register', [\App\Http\Controllers\Auth\LoginController::class, 'createUser']);
Route::post('/auth/login', [\App\Http\Controllers\Auth\LoginController::class, 'loginUser']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();

    });
    Route::post('/logout', [\App\Http\Controllers\Auth\LoginController::class, 'logout'])->middleware('auth:sanctum');

    Route::get('employees', [\App\Http\Controllers\EmployeesController::class, 'index']);
    Route::post('employees', [\App\Http\Controllers\EmployeesController::class, 'store']);
    Route::put('employees/{employee}', [\App\Http\Controllers\EmployeesController::class, 'update']);
    Route::delete('employees/{employee}', [\App\Http\Controllers\EmployeesController::class, 'destroy']);


    Route::get('/employees/{id}', [\App\Http\Controllers\EmployeesController::class , 'getEmployee']);
    Route::get('/employees/{id}/managers', [\App\Http\Controllers\EmployeesController::class , 'getManagers']);
    Route::get('/employees/{id}/managers-salary', [\App\Http\Controllers\EmployeesController::class, 'getManagersWithSalary']);
    Route::get('/employee/search', [\App\Http\Controllers\EmployeesController::class , 'searchEmployees']);
    Route::get('/employe/export', [\App\Http\Controllers\EmployeesController::class, 'exportToCSV'])->name('employe.export');
    Route::get('/employ/import', [\App\Http\Controllers\EmployeesController::class, 'importEmployees'])->name('employ.import');
    Route::get('/logs/{date}', [\App\Http\Controllers\LogController::class, 'getLogsByDate']);
});
