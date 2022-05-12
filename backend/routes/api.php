<?php

header('Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token, X-CSRF-Token, Authorization, Accept,charset,boundary, Content-Length');
header('Access-Control-Allow-Origin: http://localhost:8080');
header('Access-Control-Allow-Credentials : true');

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\NewPasswordController;

use App\Http\Controllers\ManagePhysician;
use App\Http\Controllers\ManageAppointment;
use App\Http\Controllers\ManagePatient;
use App\Http\Controllers\ManageVaccinationRecord;
use App\Http\Controllers\ManageVaccine;

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


Route::post('login', [AuthController::class, 'login']);
Route::post('verify-phone', [NewPasswordController::class, 'verifyPhone']);
Route::post('reset-password', [NewPasswordController::class, 'resetPassword']);

// manage vaccination record
Route::get('vaccination-record/{patient_id}', [ManageVaccinationRecord::class, 'getById']);
Route::post('add-vaccination-record/{patient_id}', [ManageVaccinationRecord::class, 'addRecord']);
Route::put('update-vaccination-record/{id}', [ManageVaccinationRecord::class, 'updateRecord']);

Route::get('vaccine', [ManageVaccine::class, 'getAll']);
Route::get('vaccine/{id}', [ManageVaccine::class, 'getById']);
Route::post('add-vaccine', [ManageVaccine::class, 'addVaccine']);
Route::put('update-vaccine/{id}', [ManageVaccine::class, 'updateVaccine']);
Route::delete('delete-vaccine/{id}', [ManageVaccine::class, 'deleteVaccine']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('profile', [AuthController::class, 'me']);
    Route::post('change-password', [AuthController::class, 'changePassword']);
    Route::post('logout', [AuthController::class, 'logout']);

    // manage appointment
    Route::get('appointment', [ManageAppointment::class, 'getAll']);
    Route::get('appointment/{id}', [ManageAppointment::class, 'getById']);
    Route::get('create-appointment', [ManageAppointment::class, 'create']);
    Route::post('add-appointment', [ManageAppointment::class, 'addAppointment']);
    Route::put('update-appointment/{id}', [ManageAppointment::class, 'updateAppointment']);
    Route::delete('delete-appointment/{id}', [ManageAppointment::class, 'deleteAppointment']);

    // manage patient
    Route::get('patient', [ManagePatient::class, 'getAll']);
    Route::get('patient/{id}', [ManagePatient::class, 'getById']);
    Route::put('update-patient/{id}', [ManagePatient::class, 'updatePatient']);

    // manage user
    Route::get('user/{id}', [ManagePhysician::class, 'user'])->middleware(['can:admin']);
    Route::get('listUser', [ManagePhysician::class, 'listUser'])->middleware(['can:admin']);
    Route::post('addUser', [ManagePhysician::class, 'addUser'])->middleware(['can:admin']);
    Route::post('updateUser/{id}', [ManagePhysician::class, 'updateUser'])->middleware(['can:admin']);
    Route::delete('deleteUser/{id}', [ManagePhysician::class, 'deleteUser'])->middleware(['can:admin']);
});
