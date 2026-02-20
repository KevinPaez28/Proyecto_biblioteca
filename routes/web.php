<?php
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// Ruta principal
Route::get('/', function () {
    return view('welcome');
});

// // Reenviar correo de verificación
// Route::post('/email/verification-notification', function () {
//     $user = Auth::ser();
//     if (!$user) {
//         return redirect('/login')->with('error', 'Debes iniciar sesión.');
//     }
//     $user->sendEmailVerificationNotification();
//     return back()->with('message', 'Correo de verificación enviado.');
// })->middleware(['auth', 'throttle:6,1'])->name('verification.send');

// // Verificar correo desde link del email
// Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
//     $request->fulfill();
//     return redirect('/')->with('message', 'Correo verificado correctamente.');
// })->middleware(['auth', 'signed'])->name('verification.verify');
