<?php

use App\Http\Controllers\Api\Program\ProgramController;
use App\Http\Controllers\Api\User\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/prueba', function (Request $request) {
    return response()->json(['message' => 'La API está funcionando correctamente.'], 200);
})->middleware('throttle:2,1');

Route::prefix('User')->group(function () {
    Route::get('/', [UserController::class,'getAll']);
    Route::get('/search', [UserController::class,'getByinformation']);
    Route::post('/create', [UserController::class,'create']);
    Route::post('{id}', [UserController::class,'update']);
    Route::delete('/delete/{id}', [UserController::class,'delete']);
});


Route::prefix('Programa')->group(function () {
    Route::get('/', [ProgramController::class,'getAll']);
    Route::post('/create', [ProgramController::class,'create']);
    Route::patch('/{id}', [ProgramController::class,'update']);
    Route::delete('/delete/{id}', [ProgramController::class,'delete']);
});


Route::prefix('ficha')->group(function () {
    // Route::post('/create', [FichaController::class,'store']);
});