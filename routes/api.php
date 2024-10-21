<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PacientesController;
use App\Http\Controllers\MedicosController;
use App\Http\Controllers\CitasController;
use App\Http\Controllers\AuthController;


/*Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');*/
Route::post('auth/register', [AuthController::class, 'create']);
Route::post('auth/login', [AuthController::class, 'login']);



Route::middleware(['auth:sanctum'])->group(function(){

Route::resource('pacientes', PacientesController::class);
Route::resource('medicos', MedicosController::class);
Route::resource('citas', CitasController::class);

//rutas adicionales para funcionalidades especificas
Route::get('citasAll', [CitasController::class, 'all']);
Route::get('pacientesAll', [PacientesController::class, 'all']);
Route::get('medicosAll', [MedicosController::class, 'all']);

Route::get('citasporpacientes', [CitasController::class, 'citasPorPacientes']);
Route::get('citaspormedicos', [CitasController::class, 'citasPorMedicos']);

Route::post('auth/logout', [AuthController::class, 'logout']);
}); 


