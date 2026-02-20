<?php

namespace App\Http\Controllers\Api\Profile;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Http\Requests\Profile\createProfile;
use App\Http\Requests\Profile\updateProfile;
use App\Services\Profile\ProfileServices;
use Illuminate\Http\Request;
/**
 * Class ProfileController
 * @package App\Http\Controllers\Api\Profile
 *
 * Controlador para gestionar los perfiles de usuario.
 */
class ProfileController extends Controller
{
    /**
     * @var ProfileServices
     * Servicio para la lógica de negocio de los perfiles.
     */
    protected $ProfileServices;

    /**
     * ProfileController constructor.
     * @param ProfileServices $profileServices
     */
    public function __construct(ProfileServices $profileServices)
    {
        $this->ProfileServices = $profileServices;
    }

    /**
     * Obtiene todos los perfiles.
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAll()
    {
        $response = $this->ProfileServices->getProfiles();

        if ($response['error'])
            // Si hay un error, devuelve una respuesta de error.
            return ResponseFormatter::error($response['message'], $response['code']);

        // Si no hay error, devuelve una respuesta de éxito con los datos.
        return ResponseFormatter::success($response['message'], $response['code'], $response['data'] ?? []);
    }

    /**
     * Crea un nuevo perfil.
     * @param createProfile $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(createProfile $request)
    {
        // Valida los datos de la petición.
        $data = $request->validated();

        // Crea el perfil utilizando el servicio.
        $response = $this->ProfileServices->createprofiles($data);

        if ($response['error'])
            // Si hay un error, devuelve una respuesta de error.
            return ResponseFormatter::error($response['message'], $response['code']);

        // Si no hay error, devuelve una respuesta de éxito con los datos del nuevo perfil.
        return ResponseFormatter::success($response['message'], $response['code'], $response['data'] ?? []);
    }

    /**
     * Actualiza un perfil existente.
     * @param updateProfile $request
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(updateProfile $request, string $id)
    {
        // Valida los datos de la petición.
        $data = $request->validated();

        // Actualiza el perfil utilizando el servicio.
        $response = $this->ProfileServices->updateProfiles($data, $id);

        if ($response['error'])
            // Si hay un error, devuelve una respuesta de error.
            return ResponseFormatter::error($response['message'], $response['code']);

        // Si no hay error, devuelve una respuesta de éxito con los datos del perfil actualizado.
        return ResponseFormatter::success($response['message'], $response['code'], $response['data'] ?? []);
    }
    public function delete(string $id)
    {
        $response = $this->ProfileServices->deleteprofile($id);

        if ($response['error'])
        // Si hay un error, devuelve una respuesta de error.
            return ResponseFormatter::error($response['message'], $response['code']);

        // Si no hay error, devuelve una respuesta de éxito.
        return ResponseFormatter::success($response['message'], $response['code'], $response['data'] ?? []);
    }
}
