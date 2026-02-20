<?php

namespace App\Http\Controllers\Api\Email;

use App\Helpers\ResponseFormatter;
use App\Http\Requests\email\ResendVerificationRequest;
use App\Services\Email\EmailVerificationServices;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

/**
 * Controlador para la Verificación de Email.
 * * Gestiona el flujo de confirmación de cuentas de usuario, asegurando que 
 * las direcciones de correo proporcionadas sean válidas y pertenezcan al usuario.
 */
class EmailVerificationController extends Controller
{
    /**
     * Constructor con inyección de dependencias promocionada.
     * Mantiene la arquitectura limpia delegando la lógica al EmailVerificationServices.
     */
    public function __construct(protected EmailVerificationServices $service) {}

    /**
     * Respuesta informativa para usuarios no verificados.
     * * Se utiliza normalmente como callback de middlewares cuando se detecta 
     * que el usuario intenta acceder a rutas protegidas sin haber validado su correo.
     * * @return \Illuminate\Http\JsonResponse Error 403 con código 'email_not_verified'.
     */
    public function notice()
    {
        return ResponseFormatter::error('Debes verificar tu correo.', 403, [], 'email_not_verified');
    }

    /**
     * Procesa la confirmación del correo electrónico.
     * * Valida el ID del usuario y el hash único enviado por correo para marcar 
     * la cuenta como verificada.
     * * @param Request $request
     * @param string $id Identificador del usuario.
     * @param string $hash Hash de seguridad de la URL firmada.
     * @return \Illuminate\Http\JsonResponse
     */
    public function verify(Request $request, string $id, string $hash)
    {
        // Se delega la validación de la firma y el cambio de estado al servicio
        $response = $this->service->verify($id, $hash);

        if ($response['error']) {
            return ResponseFormatter::error($response['message'], $response['code']);
        }

        return ResponseFormatter::success($response['message'], $response['code'], $response['data'] ?? null);
    }

    /**
     * Reenvía el enlace de verificación al usuario.
     * * Útil en casos donde el enlace original expiró o el correo nunca llegó.
     * * @param ResendVerificationRequest $request Valida que el email sea correcto.
     * @return \Illuminate\Http\JsonResponse
     */
    public function resend(ResendVerificationRequest $request)
    {
        // Se obtienen los datos validados del FormRequest
        $data = $request->validated();

        // El servicio se encarga de generar y enviar el nuevo correo
        $response = $this->service->resend($data);

        if ($response['error']) {
            return ResponseFormatter::error($response['message'], $response['code']);
        }

        return ResponseFormatter::success($response['message'], $response['code'], $response['data'] ?? null);
    }
}