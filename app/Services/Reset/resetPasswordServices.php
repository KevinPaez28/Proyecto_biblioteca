<?php

namespace App\Services\Reset;

use App\Models\User\User;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\PasswordReset;
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

        // Retornamos respuesta de éxito
        return [
            'error' => false,
            'message' => 'Se ha enviado el token al correo del usuario.',
            'code' => 200
        ];
    }

    /**
     * Resetear contraseña usando token
     */
    public function resetPassword(array $data): array
    {
        // Se llama al método reset del Password 
        // Este método valida:
        // - Token
        // - Email
        // - Nueva contraseña y confirmación
        // Si todo es válido, ejecuta el callback
        $status = Password::reset(
            [
                'email' => $data['email'], // Email del usuario
                'password' => $data['password'], // Nueva contraseña
                'password_confirmation' => $data['password_confirmation'], // Confirmación
                'token' => $data['token'], // Token recibido por correo
            ],
            // Callback que se ejecuta si el token y datos son válidos
            function (User $user, string $password) {
                // Actualizamos la contraseña del usuario de forma segura
                $user->forceFill([
                    'password' => Hash::make($password) // Hash seguro de la contraseña
                ])
                // Generamos un nuevo remember token para invalidar sesiones anteriores
                ->setRememberToken(Str::random(60));

                // Guardamos los cambios en la base de datos
                $user->save();

                // Disparamos el evento PasswordReset de Laravel
                // Permite ejecutar acciones adicionales como notificaciones o logs
                event(new PasswordReset($user));
            }
        );

        // Verificamos si el reset fue exitoso
        if ($status === Password::PASSWORD_RESET) {
            // Retornamos respuesta de éxito
            return [
                'error' => false,
                'message' => 'Contraseña restablecida correctamente.',
                'code' => 200
            ];
        }

        // Si hubo un error (token inválido, expirado o datos incorrectos)
        return [
            'error' => true,
            'message' => __($status), // Mensaje de error traducido por Laravel
            'code' => 422, // Código HTTP de error de validación
            'data' => ['token' => ['Token inválido o expirado']] // Información adicional sobre el error
        ];
    }
}
