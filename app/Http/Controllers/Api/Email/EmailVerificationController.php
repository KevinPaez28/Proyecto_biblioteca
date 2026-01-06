<?php

namespace App\Http\Controllers\Api\Email;

use App\Helpers\ResponseFormatter;
use App\Http\Requests\email\ResendVerificationRequest;
use App\Services\Email\EmailVerificationServices;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class EmailVerificationController extends Controller
{
    public function __construct(protected EmailVerificationServices $service) {}

    public function notice()
    {
        return ResponseFormatter::error('Debes verificar tu correo.', 403, [], 'email_not_verified');
    }

    public function verify(Request $request, string $id, string $hash)
    {
        $response = $this->service->verify($id, $hash);

        if ($response['error']) return ResponseFormatter::error($response['message'], $response['code']);

        return ResponseFormatter::success($response['message'], $response['code'], $response['data'] ?? null);
    }

    public function resend(ResendVerificationRequest $request)
    {

        $data = $request->validated();

        $response = $this->service->resend($data);

        if ($response['error']) return ResponseFormatter::error($response['message'], $response['code']);

        return ResponseFormatter::success($response['message'], $response['code'], $response['data'] ?? null);
    }
}