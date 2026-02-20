<?php

namespace App\Http\Controllers\Api\Auth;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Http\Requests\Reset\requestEmail;
use App\Http\Requests\Reset\Resetpassword;
use App\Services\Reset\resetPasswordServices;
use Illuminate\Http\Request;

/**
 * Controlador para la recuperación de contraseñas.
 * * Este controlador gestiona el flujo completo para restablecer el acceso a la cuenta,
 * desde la solicitud del token hasta la actualización final de la contraseña.
 */
class PasswordResetController extends Controller
{
    // Servicio que contiene la lógica para el envío de correos y validación de tokens
    protected $service;

    /**
     * Constructor que inyecta el servicio de restablecimiento.
     * Mantiene la separación de responsabilidades delegando la lógica al Service.
     */
    public function __construct(resetPasswordServices $service)
    {
        $this->service = $service;
    }

    /**
     * Solicitar el enlace de recuperación.
     * * Valida que el documento exista y genera un token único que se envía por correo.
     * @param requestEmail $request Contiene el documento del usuario.
     */
    public function forgotPassword(requestEmail $request)
    {
        // Se ejecutan las reglas de validación definidas en el FormRequest
        $request->validated();

        // Se delega al servicio la búsqueda del usuario y el envío del token
        $response = $this->service->sendResetToken($request->document);

        // Retorna error si el usuario no existe o falló el envío del email
        if ($response['error'])
            return ResponseFormatter::error($response['message'], $response['code']);

        return ResponseFormatter::success($response['message'], $response['code'], $response['data'] ?? []);
    }

    /**
     * Valida la vigencia de un token recibido.
     * * Este método es clave para que el frontend sepa si debe mostrar el formulario
     * de "nueva contraseña" o si el enlace ya expiró.
     */
    public function validateToken(Request $request)
    {
        $response = $this->service->validateToken($request->token);

        if ($response['error'])
            return ResponseFormatter::error($response['message'], $response['code']);

        return ResponseFormatter::success($response['message'], $response['code'], $response['data'] ?? []);
    }


    /**
     * Ejecuta el cambio físico de la contraseña.
     * * Actualiza la clave del usuario en la base de datos tras verificar el token.
     * @param Resetpassword $request Contiene el token, el nuevo password y su confirmación.
     */
    public function resetPassword(Resetpassword $request)
    {
        // Obtiene los datos ya validados (token y contraseñas coincidentes)
        $data = $request->validated();

        // Procesa la actualización de la clave mediante el servicio
        $response = $this->service->resetPassword($data);

        if ($response['error'])
            return ResponseFormatter::error($response['message'], $response['code']);

        return ResponseFormatter::success($response['message'], $response['code'], $response['data'] ?? []);
    }
}