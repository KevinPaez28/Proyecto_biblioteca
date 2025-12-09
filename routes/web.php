<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Mail;
use App\Mail\VerifyEmail;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return view('welcome');
});

// ---------------------------------------------
// Enviar correo de verificación
// ---------------------------------------------
Route::post('/email/verification-notification', function () {
    $user = Auth::user();

    if (!$user) {
        return redirect('/login')->with('error', 'Debes iniciar sesión.');
    }

    Mail::to($user->email)->send(new VerifyEmail());

    return back()->with('message', 'Correo de verificación enviado.');
})->name('verification.send');

// ---------------------------------------------
// Verificar el correo
// ---------------------------------------------
Route::get('/email/verify', function () {
    $user = Auth::user();

    if (!$user) {
        return redirect('/login')->with('error', 'Debes iniciar sesión.');
    }

    $user->email_verified_at = now();
    $user->save();

    return redirect('/')->with('message', 'Correo verificado correctamente.');
})->name('verification.verify');
