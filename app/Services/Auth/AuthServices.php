<?php

namespace App\Services\Auth;

use App\Enums\TokenAbility;
use App\Models\Profiles\Profiles;
use App\Models\User\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\PersonalAccessToken;

class AuthServices
{
    public function login(array $credentials)
    {
        $user = User::where('document', $credentials['document'])->first();

        if (!$user) {
            return [
                "error" => true,
                "code" => 404,
                "message" => "Usuario no encontrado",
            ];
        }

        if (!Auth::attempt($credentials)) {
            return [
                "error" => true,
                "code" => 401,
                "message" => "Credenciales incorrectas.",
            ];
        }

        // VALIDAR QUE SEA ADMIN
        if (!$user->hasRole('administrador')) {
            return [
                "error" => true,
                "code" => 403,
                "message" => "No tienes permisos para acceder.",
            ];
        }

        if (!$user->perfil) {
            return [
                "error" => true,
                "code" => 404,
                "message" => "Perfil no encontrado",
            ];
        }

        $perfil = $user->perfil->name;

        $roleUser = $user->roles->first();

        $permissions = $roleUser -> permissions;

        $accessToken = $this->generateAccessToken($user);
        $refreshToken = $this->generateRefreshToken($user);

        $cookieToken = cookie(
            'access_token',
            $accessToken,
            60 * 24 * 365 * 100,
            '/',
            null,
            false,
            false,
            false,
            'lax'
        );

        $cookieRefreshToken = cookie(
            'refresh_token',
            $refreshToken,
            60 * 24 * 365 * 100,
            '/',
            null,
            false,
            false,
            false,
            'lax'
        );

        return [
            "error" => false,
            "code" => 200,
            "message" => "Logueo exitoso",
            "data" => [
                'id' => $user->id,
                'names' => $perfil,
                'role_id' => $roleUser->id,
                'permissions' => $permissions->pluck('name'),
                'cookieToken' => $cookieToken,
                'cookieRefreshToken' => $cookieRefreshToken,
                'token' => $accessToken,
                'refreshToken' => $refreshToken
            ]
        ];
    }

    private function generateAccessToken($user)
    {

        return $user->createToken(
            'accessToken',
            [TokenAbility::ACCESS_API->value],
            Carbon::now()->addMinutes(config('sanctum.access_token_expiration'))
        )->plainTextToken;
    }

    private function generateRefreshToken($user)
    {

        return $user->createToken(
            'refreshToken',
            [TokenAbility::ISSUE_ACCESS_TOKEN->value],
            Carbon::now()->addMinutes(config('sanctum.refresh_token_expiration'))
        )->plainTextToken;
    }

    public function refreshToken(string $currentRefreshToken, User $user)
    {

        $refreshToken = PersonalAccessToken::findToken($currentRefreshToken);

        $accessToken = $this->generateAccessToken($user);

        $refreshToken = $this->renewRefreshToken($refreshToken, $user) ?: $currentRefreshToken;

        $cookieToken = cookie(
            'access_token',
            $accessToken,
            60 * 24 * 365 * 100,
            '/',
            null,
            false,
            false,
            false,
            'lax'
        );

        $cookieRefreshToken = cookie(
            'refresh_token',
            $refreshToken,
            60 * 24 * 365 * 100,
            '/',
            null,
            false,
            false,
            false,
            'lax'
        );

        return [
            "error" => false,
            "code" => 200,
            "message" => "Logueo exitoso",
            "data" => [
                'cookieToken' => $cookieToken,
                'cookieRefreshToken' => $cookieRefreshToken,
                'permissions' => $permissions->pluck('name'),
            ]
        ];
    }

    private function renewRefreshToken(PersonalAccessToken $refreshToken, User $user)
    {

        $expiresToken = Carbon::parse($refreshToken->expires_at);

        $remainingTime = $expiresToken->diffInSeconds(Carbon::now(), false);

        if ($remainingTime < 60 * 60 * 24) {

            $refreshToken->delete();

            return $user->createToken(
                'refreshToken',
                [TokenAbility::ISSUE_ACCESS_TOKEN->value],
                Carbon::now()->addMinutes(config('sanctum.refresh_token_expiration'))
            )->plainTextToken;
        }

        return null;
    }

    public function createExpiredCookies()
    {
        $expiredAccessToken = cookie(
            'access_token',
            '',
            -1,
            '/',
            null,
            false,
            false,
            false,
            'lax'
        );

        $expiredRefreshToken = cookie(
            'refresh_token',
            '',
            -1,
            '/',
            null,
            false,
            false,
            false,
            'lax'
        );

        return [
            'expiredAccessToken' => $expiredAccessToken,
            'expiredRefreshToken' => $expiredRefreshToken,
        ];
    }


    public function logOut(User $user)
    {
        $user->tokens()->delete();

        return [
            "error" => false,
            "code" => 200,
            "message" => "Sesión Cerrada con éxito",
            "data" => []
        ];
    }
}
