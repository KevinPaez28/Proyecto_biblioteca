<?php

namespace App\Services\Reset;

use App\Models\User\User;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;

class resetPasswordServices
{
    /**
     * Enviar token de recuperación usando el documento del usuario
     */
    public function sendResetToken(string $document): array
    {
        // Buscamos al usuario en la base de datos por su documento
        $user = User::where('document', $document)->first();

        // Si no existe el usuario, retornamos error
        if (!$user) {
            return [
                'error' => true, // Indica que hubo un error
                'message' => 'No existe un usuario con ese documento', // Mensaje de error
                'code' => 422, // Código HTTP de error de validación
                'data' => ['document' => ['Documento no encontrado']] // Datos adicionales del error
            ];
        }

        // Generar token de recuperación usando Password Broker de Laravel
        $token = Password::createToken($user);

        // Enviar token por correo al usuario
        // Mail::raw envía un correo simple con texto plano
        Mail::raw("Tu token de recuperación es: $token", function ($message) use ($user) {
            $message->to($user->email) // Correo del usuario
                ->subject('Recuperación de contraseña'); // Asunto del correo
        });

        $hiddenEmail = $this->SecretEmail($user->email);


        // Retornamos respuesta de éxito
        return [
            'error' => false,
            'message' => 'Se ha enviado el token al correo del usuario.',
            'code' => 200,
            'data' => [
                'email' => $hiddenEmail
            ]
        ];
    }

    public function validateToken(string $token): array
    {
        // Busco el registro del token (el más reciente)
        $record = DB::table('password_reset_tokens')->orderBy('created_at', 'desc')->first();

        // Si no hay nada, pues no hay token
        if (!$record) {
            return [
                'error' => true,
                'message' => 'No hay token guardado.',
                'code' => 404,
                'data' => []
            ];
        }

        // Comparo el token que mandó el usuario con el token guardado (que está en hash)
        if (!Hash::check($token, $record->token)) {
            return [
                'error' => true,
                'message' => 'El token no coincide.',
                'code' => 422,
                'data' => ['token' => ['Token incorrecto']]
            ];
        }

        // Tiempo que dura el token (normalmente 60 minutos)
        $tiempo = config('auth.passwords.users.expire', 60);

        // Veo si ya pasó el tiempo
        if (now()->diffInMinutes($record->created_at) > $tiempo) {
            return [
                'error' => true,
                'message' => 'El token ya expiró.',
                'code' => 422,
                'data' => ['token' => ['Token vencido']]
            ];
        }

        // Si todo está bien
        return [
            'error' => false,
            'message' => 'Token válido.',
            'code' => 200,
            'data' => [
                'email' => $record->email // para saber quién es
            ]
        ];
    }


    /**
     * Resetear contraseña usando token
     */
    public function resetPassword(array $data): array
    {
        // Buscamos el token en la tabla password_reset_tokens
        $record = DB::table('password_reset_tokens')->orderBy('created_at', 'desc')->first();

        if (!$record) {
            return [
                'error' => true,
                'message' => 'Token no encontrado.',
                'code' => 422
            ];
        }

        // Verificamos que el token coincida
        if (!Hash::check($data['token'], $record->token)) {
            return [
                'error' => true,
                'message' => 'Token incorrecto.',
                'code' => 422
            ];
        }

        // Validamos expiración (60 min)
        $tiempo = 60;
        if (now()->diffInMinutes($record->created_at) > $tiempo) {
            return [
                'error' => true,
                'message' => 'Token vencido.',
                'code' => 422
            ];
        }

        // Buscamos el usuario por email
        $user = User::where('email', $record->email)->first();
        if (!$user) {
            return [
                'error' => true,
                'message' => 'Usuario no encontrado.',
                'code' => 422
            ];
        }

        // Cambiamos la contraseña
        $user->password = Hash::make($data['password']);
        $user->save();

        return [
            'error' => false,
            'message' => 'Contraseña cambiada correctamente.',
            'code' => 200
        ];
    }


    public function SecretEmail($email)
    {
        // Separamos el correo en 2 partes: antes del @ y después del @
        $parst = explode('@', $email);

        // La parte del nombre (antes del @)
        $name = $parst[0];

        // La parte del dominio (después del @)
        $domain = $parst[1];

        // Dejo solo la primera letra y lo demás lo cambio por *
        $hiddenName = substr($name, 0, 1) . str_repeat('*', strlen($name) - 1);

        // Junto el nombre oculto + @ + el dominio
        return $hiddenName . '@' . $domain;
    }
}
