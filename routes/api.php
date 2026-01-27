<?php

use App\Http\Controllers\Api\Actions_Historys\ActionsController;
use App\Http\Controllers\Api\assistances\assitancesController;
use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\Auth\PasswordResetController;
use App\Http\Controllers\Api\Documents\DocumentController;
use App\Http\Controllers\Api\Email\EmailVerificationController;
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
use App\Http\Controllers\Api\UserStatus\UserStatusController;
use Illuminate\Support\Facades\Route;

// ================= PRUEBA =================
Route::get('/prueba', function () {
    return response()->json(['message' => 'La API está funcionando correctamente.'], 200);
})->middleware('throttle:2,1');

// ================= AUTH =================
Route::post('/login', [AuthController::class, 'login']);
Route::post('/validate', [PasswordResetController::class, 'validateToken']);
Route::post('/Reset-password', [PasswordResetController::class, 'forgotPassword']);
Route::post('/Reset-password/change', [PasswordResetController::class, 'resetPassword']);

// ================= EMAIL =================
Route::get('/email/verify', [EmailVerificationController::class, 'notice'])->name('verification.notice');
Route::get('/email/verify/{id}/{hash}', [EmailVerificationController::class, 'verify'])->middleware('signed')->name('verification.verify');
Route::post('/email/verification-notification', [EmailVerificationController::class, 'resend'])
->middleware('throttle:verification');
// ================= Sin tokens =================
Route::get('user/', [UserController::class, 'getAll']);
Route::post('user/create', [UserController::class, 'create']);
Route::get('roles/', [rolesController::class, 'getAll']);
Route::get('programa/', [ProgramController::class, 'getAll']);
Route::post('perfil/create', [ProfileController::class, 'create']);
Route::get('ficha/', [FichaController::class, 'getAll']);
Route::get('motivos/', [ReasonController::class, 'getAll']);
Route::get('estadoMotivos/', [estatesController::class, 'getAll']);
Route::get('eventos/', [EventController::class, 'getAll']);
Route::get('eventos/today', [EventController::class, 'gettoday']);
Route::post('asistencia/create', [assitancesController::class, 'create']);
Route::get('asistencia/export', [assitancesController::class, 'getexport']);   


// ================= RUTAS PROTEGIDAS =================
Route::middleware(['auth:sanctum'])->group(function () {

    Route::prefix('EstadoUsuarios')->group(function () {
        Route::get('/', [UserStatusController::class, 'getAll'])->middleware('permission:user-status.index');
        Route::post('/create', [UserStatusController::class, 'create'])->middleware('permission:user-status.store');
        Route::patch('/{id}', [UserStatusController::class, 'update'])->middleware('permission:user-status.update');
        Route::delete('/delete/{id}', [UserStatusController::class, 'delete'])->middleware('permission:user-status.destroy');
    });

    // --------- USUARIOS ----------
    Route::prefix('user')->group(function () {
        Route::get('/aprendices', [UserController::class, 'apprentice'])->middleware('permission:users.index');
        Route::get('profile/{id}', [UserController::class, 'profiles'])->middleware('permission:users.store');
        Route::get('/search', [UserController::class, 'getByinformation'])->middleware('permission:users.search');
        Route::post('/import', [UserController::class, 'import'])->middleware('permission:users.store');
        Route::patch('/{id}', [UserController::class, 'update'])->middleware('permission:users.update');
        Route::delete('/delete/{id}', [UserController::class, 'delete'])->middleware('permission:users.destroy');
    });

    // --------- ROLES ----------
    Route::prefix('roles')->group(function () {
        Route::get('/permisos', [rolesController::class, 'permissions']);
        Route::post('/create', [rolesController::class, 'create'])->middleware('permission:roles.store');
        Route::patch('/{id}', [rolesController::class, 'update'])->middleware('permission:roles.update');
        Route::patch('/edit/{id}', [rolesController::class, 'editRoles'])->middleware('permission:roles.update');
        Route::delete('/delete/{id}', [rolesController::class, 'delete'])->middleware('permission:roles.destroy');
    });

    // --------- PROGRAMAS ----------
    Route::prefix('programa')->group(function () {
        Route::post('/create', [ProgramController::class, 'create'])->middleware('permission:programs.store');
        Route::patch('/{id}', [ProgramController::class, 'update'])->middleware('permission:programs.update');
        Route::delete('/delete/{id}', [ProgramController::class, 'delete'])->middleware('permission:programs.destroy');
    });

    // --------- PERFILES ----------
    Route::prefix('perfil')->group(function () {
        Route::get('/', [ProfileController::class, 'getAll'])->middleware('permission:profiles.index');
        Route::patch('/{id}', [ProfileController::class, 'update'])->middleware('permission:profiles.update');
        Route::delete('/delete/{id}', [ProfileController::class, 'delete'])->middleware('permission:profiles.destroy');
    });

    // --------- FICHAS ----------
    Route::prefix('ficha')->group(function () {
        Route::post('/create', [FichaController::class, 'create'])->middleware('permission:fichas.store');
        Route::patch('/{id}', [FichaController::class, 'update'])->middleware('permission:fichas.update');
        Route::delete('/delete/{id}', [FichaController::class, 'delete'])->middleware('permission:fichas.destroy');
    });

    // --------- DOCUMENTOS ----------
    Route::prefix('documento')->group(function () {
        Route::get('/', [DocumentController::class, 'getAll'])->middleware('permission:documents.index');
        Route::post('/create', [DocumentController::class, 'create'])->middleware('permission:documents.store');
        Route::patch('/{id}', [DocumentController::class, 'update'])->middleware('permission:documents.update');
        Route::delete('/delete/{id}', [DocumentController::class, 'delete'])->middleware('permission:documents.destroy');
    });

    // --------- ACCIONES ----------
    Route::prefix('actions')->group(function () {
        Route::get('/', [ActionsController::class, 'getAll'])->middleware('permission:actions.index');
        Route::post('/create', [ActionsController::class, 'create'])->middleware('permission:actions.store');
        Route::patch('/{id}', [ActionsController::class, 'update'])->middleware('permission:actions.update');
        Route::delete('/delete/{id}', [ActionsController::class, 'delete'])->middleware('permission:actions.destroy');
    });

    // --------- HISTORIAL ----------
    Route::prefix('historial')->group(function () {
        Route::get('/', [HistoryController::class, 'getAll'])->middleware('permission:history.index');
        Route::post('/create', [HistoryController::class, 'create'])->middleware('permission:history.store');
        Route::patch('/{id}', [HistoryController::class, 'update'])->middleware('permission:history.update');
        Route::delete('/delete/{id}', [HistoryController::class, 'delete'])->middleware('permission:history.destroy');
    });

    // --------- HORARIOS ----------
    Route::prefix('horarios')->group(function () {
        Route::get('/', [SchedulesController::class, 'getAll'])->middleware('permission:schedules.index');
        Route::get('/jornadas', [SchedulesController::class, 'jornadasandhorarios'])->middleware('permission:schedules.index');
        Route::post('/create', [SchedulesController::class, 'create'])->middleware('permission:schedules.store');
        Route::patch('/{id}', [SchedulesController::class, 'update'])->middleware('permission:schedules.update');
        Route::delete('/delete/{id}', [SchedulesController::class, 'delete'])->middleware('permission:schedules.destroy');
    });

    // --------- JORNADAS ----------
    Route::prefix('jornadas')->group(function () {
        Route::get('/', [ShiftsController::class, 'getAll'])->middleware('permission:shifts.index');
        Route::get('/complete', [ShiftsController::class, 'getjornadas'])->middleware('permission:shifts.index');
        Route::post('/create', [ShiftsController::class, 'create'])->middleware('permission:shifts.store');
        Route::patch('edit/{id}', [ShiftsController::class, 'update'])->middleware('permission:shifts.update');
        Route::delete('/delete/{id}', [ShiftsController::class, 'delete'])->middleware('permission:shifts.destroy');
    });

    // --------- MOTIVOS ----------
    Route::prefix('motivos')->group(function () {
        Route::post('/create', [ReasonController::class, 'create'])->middleware('permission:reasons.store');
        Route::patch('/{id}', [ReasonController::class, 'update'])->middleware('permission:reasons.update');
        Route::delete('/delete/{id}', [ReasonController::class, 'delete'])->middleware('permission:reasons.destroy');
    });

    // --------- ESTADOS MOTIVOS ----------
    Route::prefix('estadoMotivos')->group(function () {
        Route::post('/create', [estatesController::class, 'create'])->middleware('permission:reasons.store');
        Route::patch('/{id}', [estatesController::class, 'update'])->middleware('permission:reasons.update');
        Route::delete('/delete/{id}', [estatesController::class, 'delete'])->middleware('permission:reasons.destroy');
    });

    // --------- ESTADOS EVENTOS ----------
    Route::prefix('estadoEventos')->group(function () {
        Route::get('/', [stateEventsController::class, 'getAll'])->middleware('permission:events.index');
        Route::post('/create', [stateEventsController::class, 'create'])->middleware('permission:events.store');
        Route::patch('/{id}', [stateEventsController::class, 'update'])->middleware('permission:events.update');
        Route::delete('/delete/{id}', [stateEventsController::class, 'delete'])->middleware('permission:events.destroy');
    });

    // --------- SALAS ----------
    Route::prefix('salas')->group(function () {
        Route::get('/', [RoomsController::class, 'getAll'])->middleware('permission:rooms.index');
        Route::post('/create', [RoomsController::class, 'create'])->middleware('permission:rooms.store');
        Route::patch('/{id}', [RoomsController::class, 'update'])->middleware('permission:rooms.update');
        Route::delete('/delete/{id}', [RoomsController::class, 'delete'])->middleware('permission:rooms.destroy');
    });

    // --------- ESTADOS SALAS ----------
    Route::prefix('estadosalas')->group(function () {
        Route::get('/', [StatesRoomsController::class, 'getAll'])->middleware('permission:rooms.index');
        Route::post('/create', [StatesRoomsController::class, 'create'])->middleware('permission:rooms.store');
        Route::patch('/{id}', [StatesRoomsController::class, 'update'])->middleware('permission:rooms.update');
        Route::delete('/delete/{id}', [StatesRoomsController::class, 'delete'])->middleware('permission:rooms.destroy');
    });

    // --------- EVENTOS ----------
    Route::prefix('eventos')->group(function () {
        Route::post('/create', [EventController::class, 'create'])->middleware('permission:events.store');
        Route::patch('/{id}', [EventController::class, 'update'])->middleware('permission:events.update');
        Route::delete('/delete/{id}', [EventController::class, 'delete'])->middleware('permission:events.destroy');
    });

    // --------- ASISTENCIAS ----------
    Route::prefix('asistencia')->group(function () {
        Route::get('/', [assitancesController::class, 'getAll'])->middleware('permission:assistances.index');
        Route::get('/events', [assitancesController::class, 'getEvents'])->middleware('permission:assistances.index');
        Route::post('/events/create', [assitancesController::class, 'createEventAssistance'])->middleware('permission:assistances.store');
        Route::patch('/{id}', [assitancesController::class, 'update'])->middleware('permission:assistances.update');
        Route::delete('/delete/ficha', [assitancesController::class, 'deleteAprendices'])->middleware('permission:assistances.destroy');
        Route::get('/total-dia', [assitancesController::class, 'getTotalByDay'])->middleware('permission:assistances.index');
        Route::get('/total-semana', [assitancesController::class, 'getTotalByWeek'])->middleware('permission:assistances.index');
        Route::get('/total-mes', [assitancesController::class, 'getTotalByMonth'])->middleware('permission:assistances.index');
        Route::get('/total-egresados', [assitancesController::class, 'getTotalGraduates'])->middleware('permission:assistances.index');
        Route::get('/estadisticas/mes', [assitancesController::class, 'getByMonth'])->middleware('permission:assistances.index');
        Route::get('/estadisticas/eventos', [assitancesController::class, 'getByEvent'])->middleware('permission:assistances.index');
    });
});
