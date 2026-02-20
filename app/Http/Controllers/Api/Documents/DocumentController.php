<?php

namespace App\Http\Controllers\Api\Documents;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Http\Requests\Document\createDocument;
use App\Http\Requests\Document\updateDocument;
use App\Services\Document\DocumentServices;
use Illuminate\Http\Request;

/**
 * Controlador para la gestión de Documentos.
 * * Este controlador maneja las operaciones CRUD de los tipos de documentos 
 * o archivos del sistema, delegando la lógica al servicio DocumentServices.
 */
class DocumentController extends Controller
{
    // Propiedad protegida para el servicio de documentos
    protected $DocumentServices;

    /**
     * Constructor para inyectar el servicio de documentos.
     * Mantiene el desacoplamiento siguiendo la arquitectura del proyecto.
     */
    public function __construct(DocumentServices $documentservices)
    {
        $this->DocumentServices = $documentservices;
    }

    /**
     * Obtiene el listado completo de documentos.
     * * @return \Illuminate\Http\JsonResponse Respuesta con la colección de documentos.
     */
    public function getAll()
    {
        $response = $this->DocumentServices->getDocument();

        // Si el servicio retorna un error, se formatea la respuesta de falla
        if ($response['error'])
            return ResponseFormatter::error($response['message'], $response['code']);

        // Respuesta exitosa con la data obtenida
        return ResponseFormatter::success($response['message'], $response['code'], $response['data'] ?? []);
    }

    /**
     * Registro de un nuevo documento.
     * (Nota: Actualmente este método se encuentra comentado en la arquitectura original).
     */
    // public function create(createDocument $request)
    // {
    //     $data = $request->validated();
    //     $response = $this->DocumentServices->createdocument($data);
    //     if ($response['error'])
    //         return ResponseFormatter::error($response['message'], $response['code']);
    //     return ResponseFormatter::success($response['message'], $response['code'], $response['data'] ?? []);
    // }

    /**
     * Actualiza un documento existente.
     * * @param updateDocument $request FormRequest con las reglas de validación.
     * @param string $id Identificador único del documento.
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(updateDocument $request, string $id)
    {
        // Obtención de datos validados
        $data = $request->validated();

        $response = $this->DocumentServices->updateDocument($data, $id);

        if ($response['error'])
            return ResponseFormatter::error($response['message'], $response['code']);

        return ResponseFormatter::success($response['message'], $response['code'], $response['data'] ?? []);
    }

    /**
     * Elimina un documento del sistema.
     * * @param string $id ID del documento a eliminar.
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete(string $id)
    {
        $response = $this->DocumentServices->deleteDocument($id);

        if ($response['error'])
            return ResponseFormatter::error($response['message'], $response['code']);

        return ResponseFormatter::success($response['message'], $response['code'], $response['data'] ?? []);
    }
}