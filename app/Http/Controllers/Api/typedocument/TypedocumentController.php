<?php

namespace App\Http\Controllers\Api\typeDocument;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Http\Requests\TypeDocument\Createtypedocument;
use App\Http\Requests\TypeDocument\updateTypeDocument;
use App\Services\TypedocumentServices\typeDocumentServices;
/**
 * Class TypeDocumentController
 * @package App\Http\Controllers\Api\TypeDocument
 *
 * Controlador para la gestión de tipos de documentos.
 */
class TypeDocumentController extends Controller
{
    /**
     * @var TypeDocumentServices
     * Servicio para la lógica de negocio de los tipos de documentos.
     */
    protected $typeDocumentServices;

    /**
     * TypeDocumentController constructor.
     * @param TypeDocumentServices $typeDocumentServices
     */
    public function __construct(TypeDocumentServices $typeDocumentServices)
    {
        $this->typeDocumentServices = $typeDocumentServices;
    }

    /**
     * Obtiene todos los tipos de documentos.
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAll()
    {
        $response = $this->typeDocumentServices->getTypeDocuments();

        if ($response['error'])
            // Si hay un error, devuelve una respuesta de error.
            return ResponseFormatter::error($response['message'], $response['code']);

        // Si no hay error, devuelve una respuesta de éxito con los datos.
        return ResponseFormatter::success($response['message'], $response['code'], $response['data'] ?? []);
    }

    /**
     * Crea un nuevo tipo de documento.
     * @param Createtypedocument $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(Createtypedocument $request)
    {
        // Valida los datos de la petición.
        $data = $request->validated();

        // Crea el tipo de documento utilizando el servicio.
        $response = $this->typeDocumentServices->createTypeDocument($data);

        if ($response['error'])
            // Si hay un error, devuelve una respuesta de error.
            return ResponseFormatter::error($response['message'], $response['code']);

        // Si no hay error, devuelve una respuesta de éxito con los datos del nuevo tipo de documento.
        return ResponseFormatter::success($response['message'], $response['code'], $response['data'] ?? []);
    }

    /**
     * Actualiza un tipo de documento existente.
     * @param updateTypeDocument $request
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(updateTypeDocument $request, string $id)
    {
        // Valida los datos de la petición.
        $data = $request->validated();

        // Actualiza el tipo de documento utilizando el servicio.
        $response = $this->typeDocumentServices->updateTypeDocument($data, $id);

        if ($response['error'])
            // Si hay un error, devuelve una respuesta de error.
            return ResponseFormatter::error($response['message'], $response['code']);

        // Si no hay error, devuelve una respuesta de éxito con los datos del tipo de documento actualizado.
        return ResponseFormatter::success($response['message'], $response['code'], $response['data'] ?? []);
    }

    public function delete(string $id)
    {
        $response = $this->typeDocumentServices->deleteTypeDocument($id);
        // Elimina el tipo de documento utilizando el servicio.
        if ($response['error'])
            // Si hay un error, devuelve una respuesta de error.
            return ResponseFormatter::error($response['message'], $response['code']);

        // Si no hay error, devuelve una respuesta de éxito.
        return ResponseFormatter::success($response['message'], $response['code'], $response['data'] ?? []);
    }
}
