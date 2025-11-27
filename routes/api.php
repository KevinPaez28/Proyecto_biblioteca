<?php

use App\Http\Controllers\Api\Actions_Historys\ActionsController;
use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\Documents\DocumentController;
use App\Http\Controllers\Api\Events\EventController;
use App\Http\Controllers\Api\Ficha\FichaController;
use App\Http\Controllers\Api\History\HistoryController;
use App\Http\Controllers\Api\Profile\ProfileController;
use App\Http\Controllers\Api\Program\ProgramController;
use App\Http\Controllers\Api\Reason\ReasonController;
use App\Http\Controllers\Api\Reason_estates\estatesController;
use App\Http\Controllers\Api\Rooms\RoomsController;
use App\Http\Controllers\Api\Schedules\SchedulesController;
use App\Http\Controllers\Api\Shifts\ShiftsController;
use App\Http\Controllers\Api\state_events\stateEventsController;
use App\Http\Controllers\Api\State_rooms\state_roomsController;
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

Route::prefix('estadosMotivos')->group(function () {
    Route::get('/', [estatesController::class, 'getAll']);
    Route::post('/create', [estatesController::class, 'create']);
    Route::patch('/{id}', [estatesController::class, 'update']);
    Route::delete('/delete/{id}', [estatesController::class, 'delete']);
});

Route::prefix('motivos')->group(function () {
    Route::get('/', [ReasonController::class, 'getAll']);
    Route::post('/create', [ReasonController::class, 'create']);
    Route::patch('/{id}', [ReasonController::class, 'update']);
    Route::delete('/delete/{id}', [ReasonController::class, 'delete']);
});   

Route::prefix('EstadosEventos')->group(function () {
    Route::get('/', [stateEventsController::class, 'getAll']);
    Route::post('/create', [stateEventsController::class, 'create']);
    Route::patch('/{id}', [stateEventsController::class, 'update']);
    Route::delete('/delete/{id}', [stateEventsController::class, 'delete']);
}); 

Route::prefix('EstadosSalas')->group(function () {
    Route::get('/', [state_roomsController::class, 'getAll']);
    Route::post('/create', [state_roomsController::class, 'create']);
    Route::patch('/{id}', [state_roomsController::class, 'update']);
    Route::delete('/delete/{id}', [state_roomsController::class, 'delete']);
});

Route::prefix('salas')->group(function () {
    Route::get('/', [RoomsController::class, 'getAll']);
    Route::post('/create', [RoomsController::class, 'create']);
    Route::patch('/{id}', action: [RoomsController::class, 'update']);
    Route::delete('/delete/{id}', [RoomsController::class, 'delete']);
});


Route::prefix('eventos')->group(function () {
    Route::get('/', [EventController::class, 'getAll']);
    Route::post('/create', [EventController::class, 'create']);
    Route::patch('/{id}', action: [EventController::class, 'update']);
    Route::delete('/delete/{id}', [RoomsController::class, 'delete']);
});   




