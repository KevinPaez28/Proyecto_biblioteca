<?php

use App\Http\Controllers\Api\Actions_Historys\ActionsController;
use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\Documents\DocumentController;
use App\Http\Controllers\Api\Ficha\FichaController;
use App\Http\Controllers\Api\History\HistoryController;
use App\Http\Controllers\Api\Profile\ProfileController;
use App\Http\Controllers\Api\Program\ProgramController;
use App\Http\Controllers\Api\Schedules\SchedulesController;
use App\Http\Controllers\Api\Shifts\ShiftsController;
use App\Http\Controllers\Api\User\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/prueba', function (Request $request) {
    return response()->json(['message' => 'La API está funcionando correctamente.'], 200);
})->middleware('throttle:2,1');
//ruta para el login
Route::post('/login', [AuthController::class, 'login']);

//crud de usuarios
Route::prefix('user')->group(function () {
    Route::get('/', [UserController::class, 'getAll']);
    Route::get('/search', [UserController::class, 'getByinformation']);
    Route::post('/create', [UserController::class, 'create']);
    Route::patch('/{id}', [UserController::class, 'update']);
    Route::delete('/delete/{id}', [UserController::class, 'delete']);
});

//crud de programas
Route::prefix('programa')->group(function () {
    Route::get('/', [ProgramController::class, 'getAll']);
    Route::post('/create', [ProgramController::class, 'create']);
    Route::patch('/{id}', [ProgramController::class, 'update']);
    Route::delete('/delete/{id}', [ProgramController::class, 'delete']);
});

//crud de perfiles
Route::prefix('perfil')->group(function () {
    Route::get('/', [ProfileController::class,'getAll']);
    Route::post('/create', [ProfileController::class,'create']);
    Route::patch('/{id}', [ProfileController::class,'update']);
    Route::delete('/delete/{id}', [ProfileController::class,'delete']);
});


//crud de fichas
Route::prefix('ficha')->group(function () {
    Route::get('/', [FichaController::class, 'getAll']);
    Route::post('/create', [FichaController::class, 'create']);
    Route::patch('/{id}', [FichaController::class, 'update']);
    Route::delete('/delete/{id}', [FichaController::class, 'delete']);
});

//Crud de documentos
Route::prefix('documento')->group(function () {
    Route::get('/', [DocumentController::class, 'getAll']);
    Route::post('/create', [DocumentController::class, 'create']);
    Route::patch('/{id}', [DocumentController::class, 'update']);
    Route::delete('/delete/{id}', [DocumentController::class, 'delete']);
});

//crud de 
Route::prefix('actions')->group(function () {
    Route::get('/', [ActionsController::class, 'getAll']);
    Route::post('/create', [ActionsController::class, 'create']);
    Route::patch('/{id}', [ActionsController::class, 'update']);
    Route::delete('/delete/{id}', [ActionsController::class, 'delete']);
});

Route::prefix('history')->group(function () {
    Route::get('/', [HistoryController::class, 'getAll']);
    Route::post('/create', [HistoryController::class, 'create']);
    Route::patch('/{id}', [HistoryController::class, 'update']);
    Route::delete('/delete/{id}', [HistoryController::class, 'delete']);
});

Route::prefix('horarios')->group(function () {
    Route::get('/', [SchedulesController::class, 'getAll']);
    Route::post('/create', [SchedulesController::class, 'create']);
    Route::patch('/{id}', [SchedulesController::class, 'update']);
    Route::delete('/delete/{id}', [SchedulesController::class, 'delete']);
});

Route::prefix('jornadas')->group(function () {
    Route::get('/', [ShiftsController::class, 'getAll']);
    Route::post('/create', [ShiftsController::class, 'create']);
    Route::patch('/{id}', [ShiftsController::class, 'update']);
    Route::delete('/delete/{id}', [ShiftsController::class, 'delete']);
});