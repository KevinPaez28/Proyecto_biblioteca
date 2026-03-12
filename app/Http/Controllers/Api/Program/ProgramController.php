<?php

namespace App\Http\Controllers\Api\Program;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Http\Requests\Programa\createProgram;
use App\Http\Requests\Programa\updateProgram;
use App\Services\Program\ProgramServices;
use Illuminate\Http\Request;

/**
 * Class ProgramController
 * @package App\Http\Controllers\Api\Program
 *
 * Controlador para la gestión de programas.
 */
class ProgramController extends Controller
{
    /**
     * @var ProgramServices
     * Servicio para la lógica de negocio de los programas.
     */
    protected $programaServices;

    /**
     * ProgramController constructor.
     * @param ProgramServices $programaServices
     */
    public function __construct(ProgramServices $programaServices)
    {
        $this->programaServices = $programaServices;
    }

    /**
     * Obtiene todos los programas.
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAll()
    {
        $response = $this->programaServices->getProgramas();

        if ($response['error'])
            // Si hay un error, devuelve una respuesta de error.
            return ResponseFormatter::error($response['message'], $response['code']);

        // Si no hay error, devuelve una respuesta de éxito con los datos.
        return ResponseFormatter::success($response['message'], $response['code'], $response['data'] ?? []);
    }

    /**
     * Crea un nuevo programa.
     * @param createProgram $request
     * @return \Illuminate\Http\JsonResponse
     */

    public function getByInformation(Request $request)
    {
        $filters = $request->only([
            'nombre'
        ]);

        $response = $this->programaServices->getProgramByInformation($filters);

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
    public function create(createProgram $request)
    {
        // Valida los datos de la petición.
        $data = $request->validated();

        // Crea el programa utilizando el servicio.
        $response = $this->programaServices->createPrograma($data);

        if ($response['error'])
            // Si hay un error, devuelve una respuesta de error.
            return ResponseFormatter::error($response['message'], $response['code']);

        // Si no hay error, devuelve una respuesta de éxito con los datos del nuevo programa.
        return ResponseFormatter::success($response['message'], $response['code'], $response['data'] ?? []);
    }
    public function update(updateProgram $request, string $id)
    {
        $data = $request->validated();

        // Actualiza el programa utilizando el servicio.
        $response = $this->programaServices->updateProgram($data, $id);

        if ($response['error'])
            return ResponseFormatter::error($response['message'], $response['code']);

        return ResponseFormatter::success($response['message'], $response['code'], $response['data'] ?? []);
    }

    /**
     * Elimina un programa existente.
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete(string $id)
    {
        $response = $this->programaServices->deleteProgram($id);

        if ($response['error'])
            // Si hay un error, devuelve una respuesta de error.
            return ResponseFormatter::error($response['message'], $response['code']);

        // Si no hay error, devuelve una respuesta de éxito.
        return ResponseFormatter::success($response['message'], $response['code'], $response['data'] ?? []);
    }
}
