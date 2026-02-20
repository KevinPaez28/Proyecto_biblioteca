<?php

namespace App\Http\Controllers\Api\Auth;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Http\Requests\Reset\requestEmail;
use App\Http\Requests\Reset\Resetpassword;
use App\Services\Reset\resetPasswordServices;
use Illuminate\Http\Request;

class PasswordResetController extends Controller
{
    protected $service;

    public function __construct(resetPasswordServices $service)
    {
        $this->service = $service;
    }

    /**
     * Solicitar link de recuperación
     */
    public function forgotPassword(requestEmail $request)
    {
        $request->validated();

        $response = $this->service->sendResetToken($request->document);

        if ($response['error'])
            return ResponseFormatter::error($response['message'], $response['code']);

        return ResponseFormatter::success($response['message'], $response['code'], $response['data'] ?? []);
    }

    public function validateToken(Request $request)
    {
        $response = $this->service->validateToken($request->token);

        if ($response['error'])
            return ResponseFormatter::error($response['message'], $response['code']);

        return ResponseFormatter::success($response['message'], $response['code'], $response['data'] ?? []);
    }


    /**
     * Resetear contraseña
     */
    public function resetPassword(Resetpassword $request)
    {
        $data = $request->validated();

        $response = $this->service->resetPassword($data);

        if ($response['error'])
            return ResponseFormatter::error($response['message'], $response['code']);

        return ResponseFormatter::success($response['message'], $response['code'], $response['data'] ?? []);
    }
}
