<?php

use App\Http\Controllers\Api\Actions_Historys\ActionsController;
use App\Http\Controllers\Api\assistances\assitancesController;
use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\Auth\PasswordResetController;
use App\Http\Controllers\Api\Documents\DocumentController;
use App\Http\Controllers\Api\Events\EventController;
use App\Http\Controllers\Api\Ficha\FichaController;
use App\Http\Controllers\Api\History\HistoryController;
use App\Http\Controllers\Api\Profile\ProfileController;
use App\Http\Controllers\Api\Program\ProgramController;
use App\Http\Controllers\Api\Reason\ReasonController;
use App\Http\Controllers\Api\Reason_estates\estatesController;
use App\Http\Controllers\Api\Roles\rolesController;
use App\Http\Controllers\Api\Rooms\RoomsController;
use App\Http\Controllers\Api\Schedules\SchedulesController;
use App\Http\Controllers\Api\Shifts\ShiftsController;
use App\Http\Controllers\Api\state_events\stateEventsController;
use App\Http\Controllers\Api\states_rooms\StatesRoomsController;
use App\Http\Controllers\Api\User\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/prueba', function () {
    return response()->json(['message' => 'La API está funcionando correctamente.'], 200);
})->middleware('throttle:2,1');

// LOGIN
Route::post('/login', [AuthController::class, 'login']);
Route::post('/validate', [PasswordResetController::class, 'validateToken']);
Route::post('/Reset-password', [PasswordResetController::class, 'forgotPassword']);
Route::post('/Reset-password/change', [PasswordResetController::class, 'resetPassword']);

// CRUD Usuarios
Route::prefix('user')->group(function () {
    Route::get('/', [UserController::class, 'getAll']);
    Route::get('/search', [UserController::class, 'getByinformation']);
    Route::post('/create', [UserController::class, 'create']);
    Route::patch('/{id}', [UserController::class, 'update']);
    Route::delete('/delete/{id}', [UserController::class, 'delete']);
});
// CRUD roles
Route::prefix('roles')->group(function () {
    Route::get('/', [rolesController::class, 'getAll']);
    Route::post('/create', [rolesController::class, 'create']);
    Route::patch('/{id}', [rolesController::class, 'update']);
    Route::delete('/delete/{id}', [rolesController::class, 'delete']);
});


// CRUD Programas
Route::prefix('programa')->group(function () {
    Route::get('/', [ProgramController::class, 'getAll']);
    Route::post('/create', [ProgramController::class, 'create']);
    Route::patch('/{id}', [ProgramController::class, 'update']);
    Route::delete('/delete/{id}', [ProgramController::class, 'delete']);
});

// CRUD Perfiles
Route::prefix('perfil')->group(function () {
    Route::get('/', [ProfileController::class, 'getAll']);
    Route::post('/create', [ProfileController::class, 'create']);
    Route::patch('/{id}', [ProfileController::class, 'update']);
    Route::delete('/delete/{id}', [ProfileController::class, 'delete']);
});

// CRUD Fichas
Route::prefix('ficha')->group(function () {
    Route::get('/', [FichaController::class, 'getAll']);
    Route::post('/create', [FichaController::class, 'create']);
    Route::patch('/{id}', [FichaController::class, 'update']);
    Route::delete('/delete/{id}', [FichaController::class, 'delete']);
});

// CRUD Documentos
Route::prefix('documento')->group(function () {
    Route::get('/', [DocumentController::class, 'getAll']);
    Route::post('/create', [DocumentController::class, 'create']);
    Route::patch('/{id}', [DocumentController::class, 'update']);
    Route::delete('/delete/{id}', [DocumentController::class, 'delete']);
});

// CRUD Acciones
Route::prefix('actions')->group(function () {
    Route::get('/', [ActionsController::class, 'getAll']);
    Route::post('/create', [ActionsController::class, 'create']);
    Route::patch('/{id}', [ActionsController::class, 'update']);
    Route::delete('/delete/{id}', [ActionsController::class, 'delete']);
});

// CRUD Historial
Route::prefix('historial')->group(function () {
    Route::get('/', [HistoryController::class, 'getAll']);
    Route::post('/create', [HistoryController::class, 'create']);
    Route::patch('/{id}', [HistoryController::class, 'update']);
    Route::delete('/delete/{id}', [HistoryController::class, 'delete']);
});

// CRUD Horarios
Route::prefix('horarios')->group(function () {
    Route::get('/', [SchedulesController::class, 'getAll']);
    Route::get('/jornadas', [SchedulesController::class, 'jornadasandhorarios']);
    Route::post('/create', [SchedulesController::class, 'create']);
    Route::patch('/{id}', [SchedulesController::class, 'update']);
    Route::delete('/delete/{id}', [SchedulesController::class, 'delete']);
});

// CRUD Jornadas
Route::prefix('jornadas')->group(function () {
    Route::get('/', [ShiftsController::class, 'getAll']);
    Route::get('/complete', [ShiftsController::class, 'getjornadas']);
    Route::post('/create', [ShiftsController::class, 'create']);
    Route::patch('edit/{id}', [ShiftsController::class, 'update']);
    Route::delete('/delete/{id}', [ShiftsController::class, 'delete']);
});

// CRUD Estados Motivos
Route::prefix('estadoMotivos')->group(function () {
    Route::get('/', [estatesController::class, 'getAll']);
    Route::post('/create', [estatesController::class, 'create']);
    Route::patch('/{id}', [estatesController::class, 'update']);
    Route::delete('/delete/{id}', [estatesController::class, 'delete']);
});

// CRUD Motivos
Route::prefix('motivos')->group(function () {
    Route::get('/', [ReasonController::class, 'getAll']);
    Route::post('/create', [ReasonController::class, 'create']);
    Route::patch('/{id}', [ReasonController::class, 'update']);
    Route::delete('/delete/{id}', [ReasonController::class, 'delete']);
});

// CRUD Estados Eventos
Route::prefix('estadoEventos')->group(function () {
    Route::get('/', [stateEventsController::class, 'getAll']);
    Route::post('/create', [stateEventsController::class, 'create']);
    Route::patch('/{id}', [stateEventsController::class, 'update']);
    Route::delete('/delete/{id}', [stateEventsController::class, 'delete']);
});

// CRUD Estados Salas (solo uno!)
Route::prefix('estadosalas')->group(function () {
    Route::get('/', [StatesRoomsController::class, 'getAll']);
    Route::post('/create', [StatesRoomsController::class, 'create']);
    Route::patch('/{id}', [StatesRoomsController::class, 'update']);
    Route::delete('/delete/{id}', [StatesRoomsController::class, 'delete']);
});

// CRUD Salas
Route::prefix('salas')->group(function () {
    Route::get('/', [RoomsController::class, 'getAll']);
    Route::post('/create', [RoomsController::class, 'create']);
    Route::patch('/{id}', [RoomsController::class, 'update']);
    Route::delete('/delete/{id}', [RoomsController::class, 'delete']);
});

// CRUD Eventos
Route::prefix('eventos')->group(function () {
    Route::get('/', [EventController::class, 'getAll']);
    Route::get('/today', [EventController::class, 'gettoday']);
    Route::post('/create', [EventController::class, 'create']);
    Route::patch('/{id}', [EventController::class, 'update']);
    Route::delete('/delete/{id}', [EventController::class, 'delete']);
});

Route::prefix('asistencia')->group(function () {
    Route::get('/', [assitancesController::class, 'getAll']);
    Route::post('/create', [assitancesController::class, 'create']);
    Route::patch('/{id}', [assitancesController::class, 'update']);
    Route::delete('/delete/{id}', [assitancesController::class, 'delete']);
    Route::get('/total-dia', [assitancesController::class, 'getTotalByDay']);
    Route::get('/total-semana', [assitancesController::class, 'getTotalByWeek']);
    Route::get('/total-mes', [assitancesController::class, 'getTotalByMonth']);
    Route::get('/total-egresados', [assitancesController::class, 'getTotalGraduates']);
    Route::get('/estadisticas/mes', [assitancesController::class, 'getByMonth']);
    Route::get('/estadisticas/eventos', [assitancesController::class, 'getByEvent']);
});
