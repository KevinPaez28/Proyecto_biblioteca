<?php

namespace App\Http\Controllers\Api\Auth;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\loginRequest;
use App\Services\Auth\AuthServices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;

/**
 * Controlador de Autenticación.
 * * Gestiona el acceso, cierre de sesión y refresco de tokens, 
 * implementando una capa de seguridad mediante cookies HttpOnly.
 */
class AuthController extends Controller
{
    // Servicio que centraliza la lógica de autenticación
    protected $authService;

    /**
     * Constructor con inyección de dependencia.
     * Mantiene el desacoplamiento entre el controlador y la lógica de negocio.
     */
    public function __construct(AuthServices $authService)
    {
        $this->authService = $authService;
    }

    /**
     * Inicia sesión en el sistema.
     * * Valida credenciales, genera tokens y los adjunta a la respuesta mediante cookies.
     * @param loginRequest $request Valida campos requeridos.
     */
    public function login(loginRequest $request)
    {
        // Obtiene datos validados por el FormRequest
        $credentials = $request->validated();

        // Delega la validación y creación de tokens al servicio
        $result = $this->authService->login($credentials);

        if ($result['error']) {
            return ResponseFormatter::error($result['message'], $result['code']);
        }

        // Extracción de cookies preparadas por el servicio
        $cookieToken = $result['data']['cookieToken'];
        $cookieRefresh = $result['data']['cookieRefreshToken'];

        /**
         * Se construye la respuesta JSON directamente para adjuntar las cookies.
         * Se utiliza array_diff_key para evitar que los objetos de las cookies
         * se filtren en el cuerpo de la respuesta JSON (mantiene la privacidad).
         */
        return response()->json([
            "success" => true,
            "code" => $result['code'],
            "message" => $result['message'],
            "data" => array_diff_key($result['data'], array_flip(['cookieToken', 'cookieRefreshToken']))
        ])
            ->cookie($cookieToken)
            ->cookie($cookieRefresh);
    }

    /**
     * Renueva los tokens de acceso expirados.
     * * Utiliza el Refresh Token para emitir un nuevo juego de credenciales sin pedir login.
     */
    public function refreshToken(Request $request)
    {
        $user = Auth::user();

        $currentRefreshToken = $request->cookie('refresh_token');

        $result = $this->authService->refreshToken($currentRefreshToken, $user);

        return response()->json([
            'success' => true,
            'message' => 'Token refrescado exitosamente',
            'data' => []
        ])
            ->cookie($result['cookieToken'])
            ->cookie($result['cookieRefreshToken']);
    }
    /**
     * Cierra la sesión activa.
     * * Revoca los tokens en el servidor y expira las cookies en el cliente para mayor seguridad.
     */
    public function logOut(Request $request)
    {
        $user = Auth::user();

        // El servicio genera cookies con tiempo de expiración pasado para eliminarlas
        $expiredCookies = $this->authService->createExpiredCookies();

        $result = $this->authService->logOut($user);

        return response()->json([
            "success" => true,
            "code" => $result['code'],
            "message" => $result['message'],
            "data" => $result['data']
        ])
            ->cookie($expiredCookies['expiredAccessToken'])
            ->cookie($expiredCookies['expiredRefreshToken']);
    }
}
