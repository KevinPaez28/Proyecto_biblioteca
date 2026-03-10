<?php

namespace App\Http\Controllers\Api\User;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Http\Requests\Imports\ImportApprenticeRequest;
use App\Http\Requests\Reset\ChangePasswordRequest;
use App\Http\Requests\User\updateRequest;
use App\Http\Requests\User\UserRequest;
use App\Services\ImportExcel\ImportExcelService;
use App\Services\User\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
/**
 * Class UserController
 * @package App\Http\Controllers\Api\User
 *
 * Controlador para la gestión de usuarios.
 *  * Este controlador permite gestionar los usuarios, aprendices, importar usuarios,
 *  * actualizar información, cambiar contraseñas, eliminar usuarios, entre otras funciones.
 */
class UserController extends Controller
{
    protected $userService;

    protected $importService;

    public function __construct(UserService $userService,ImportExcelService $importService) {
        $this->userService = $userService;
        $this->importService = $importService;
    }
    

    /**
     * Obtiene todos los usuarios.
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAll()
    {
        $response = $this->userService->getUser();


        if ($response['error'])
            return ResponseFormatter::error($response['message'], $response['code']);

        return ResponseFormatter::success($response['message'], $response['code'], $response['data'] ?? []);
    }

    /**
     * Obtiene el perfil de un usuario por su ID.
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function profiles(string $id)
    {
        $response = $this->userService->getUserById($id);

        if ($response['error']) {
            return ResponseFormatter::error($response['message'], $response['code']);
        }

        return ResponseFormatter::success($response['message'], $response['code'], $response['data'] ?? []);
    }

    /**
     * Obtiene usuarios filtrados por información específica.
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getByinformation(Request $request)
    {
        $filters = $request->only([
            'nombre',
            'apellido',
            'documento',
            'rol',
            'estado',
            'document_type_id'
        ]);

        $response = $this->userService->getAllInformation($filters);

        if ($response['error']) {
            return ResponseFormatter::error(
                $response['message'],
                $response['code']
            );
        }

        return ResponseFormatter::success(
            $response['message'],
            $response['code'],
            $response['data'] ?? []
        );
    }
    /**
     * Obtiene todos los aprendices con filtros.
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function apprentice(Request $request)
    {
        $filters = $request->only([
            'nombre',
            'apellido',
            'documento',
            'rol',
            'estados',
            'ficha',
            'document_type_id',
            'programa'
        ]);

        $response = $this->userService->getAllApprentices($filters);

        if ($response['error']) {
            return ResponseFormatter::error(
                $response['message'],
                $response['code']
            );
        }

        return ResponseFormatter::success(
            $response['message'],
            $response['code'],
            $response['data'] ?? [],
        );
    }

    /**
     * Crea un nuevo usuario.
     * @param UserRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(UserRequest $request)
    {
        $data = $request->validated();
      // ================= CONTINUAR REGISTRO =================
        $response = $this->userService->CreateUser($data);

        if ($response['error']) {
            return ResponseFormatter::error(
                $response['message'],
                $response['code']
            );
        }

        return ResponseFormatter::success(
            $response['message'],
            $response['code'],
            $response['data'] ?? []
        );
    }

    /**
     * Actualiza un usuario existente.
     * @param updateRequest $request
     * @param string $id
     */
    public function update(updateRequest $request, string $id)
    {
        $data = $request->validated();

        $response = $this->userService->updateUser($data, $id);

        if ($response['error'])
            return ResponseFormatter::error($response['message'], $response['code']);

        return ResponseFormatter::success($response['message'], $response['code'], $response['data'] ?? []);
    }
    /**
     * Cambia la contraseña de un usuario.
     * @param ChangePasswordRequest $request
     * @param string $id
     */
    public function newpassword(ChangePasswordRequest $request, string $id)
    {
        $data = $request->validated();

        $response = $this->userService->changePassword($data, $id);

        if ($response['error'])
            return ResponseFormatter::error($response['message'], $response['code']);

        return ResponseFormatter::success($response['message'], $response['code'], $response['data'] ?? []);
    }

    /**
     * Elimina un usuario existente.
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete(string $id)
    {
        $response = $this->userService->deleteUser($id);

        if ($response['error'])
            return ResponseFormatter::error($response['message'], $response['code']);

        return ResponseFormatter::success($response['message'], $response['code'], $response['data'] ?? []);
    }

    /**
     * Importa aprendices desde un archivo Excel.
     * @param ImportApprenticeRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function import(ImportApprenticeRequest $request)
    {
        $file = $request->file('file');

        $response = $this->importService->importFile($file);

        if ($response['error']) {
            return ResponseFormatter::error(
                $response['message'],
                $response['code'],
                $response['errors'] ?? []
            );
        }

        return ResponseFormatter::success(
            $response['message'],
            $response['code'],
            $response['data'] ?? []
        );
    }
}
