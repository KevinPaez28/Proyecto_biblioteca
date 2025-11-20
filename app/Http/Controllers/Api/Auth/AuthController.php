<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Models\User\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        // Validar datos
        $request->validate([
            'document' => 'required|string',
            'password' => 'required|string'
        ]);

        // Intentar autenticación
        if (!Auth::attempt($request->only('document', 'password'))) {
            return response()->json([
                'error' => true,
                'message' => 'Credenciales incorrectas'
            ], 401);
        }

        // Obtener usuario autenticado
        $user = User::where('document', $request->document)->first();

        // Generar token
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'error' => false,
            'message' => 'Login exitoso',
            'access_token' => $token,
            'token_type' => 'Bearer'
        ]);
    }
}
