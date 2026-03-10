<?php

namespace App\Http\Controllers\Api\Auth;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\loginRequest;
use App\Services\Auth\AuthServices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\PersonalAccessToken;

class AuthController extends Controller
{

    protected $authService;

    public function __construct(AuthServices $authService)
    {
        $this->authService = $authService;
    }

    /* =================================
       LOGIN
    ================================= */
    public function login(loginRequest $request)
    {
        $credentials = $request->validated();

        $result = $this->authService->login($credentials);

        if ($result['error']) {
            return ResponseFormatter::error(
                $result['message'],
                $result['code']
            );
        }

        $cookieToken = $result['data']['cookieToken'];
        $cookieRefresh = $result['data']['cookieRefreshToken'];

        return response()->json([
            "success" => true,
            "code" => $result['code'],
            "message" => $result['message'],
            "data" => array_diff_key(
                $result['data'],
                array_flip(['cookieToken','cookieRefreshToken'])
            )
        ])
        ->cookie($cookieToken)
        ->cookie($cookieRefresh);
    }

    /* =================================
       REFRESH TOKEN
    ================================= */
    public function refreshToken(Request $request)
    {

        $currentRefreshToken = $request->cookie('refresh_token');

        if (!$currentRefreshToken) {
            return response()->json([
                "success" => false,
                "message" => "Refresh token no encontrado"
            ],401);
        }

        $token = PersonalAccessToken::findToken($currentRefreshToken);

        if (!$token) {
            return response()->json([
                "success" => false,
                "message" => "Refresh token inválido"
            ],401);
        }

        $user = $token->tokenable;

        $result = $this->authService->refreshToken($currentRefreshToken,$user);

        if ($result['error']) {
            return response()->json([
                "success"=>false,
                "message"=>$result['message']
            ],$result['code']);
        }

        return response()->json([
            "success"=>true,
            "message"=>"Token refrescado exitosamente",
            "data"=>[]
        ])
        ->cookie($result['data']['cookieToken'])
        ->cookie($result['data']['cookieRefreshToken']);
    }

    /* =================================
       LOGOUT
    ================================= */
    public function logOut(Request $request)
    {

        $user = Auth::user();

        if (!$user) {
            return response()->json([
                "success"=>false,
                "message"=>"Usuario no autenticado"
            ],401);
        }

        $expiredCookies = $this->authService->createExpiredCookies();

        $result = $this->authService->logOut($user);

        return response()->json([
            "success"=>true,
            "code"=>$result['code'],
            "message"=>$result['message'],
            "data"=>$result['data']
        ])
        ->cookie($expiredCookies['expiredAccessToken'])
        ->cookie($expiredCookies['expiredRefreshToken']);
    }
}