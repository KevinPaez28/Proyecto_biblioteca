<?php

namespace App\Services\TypedocumentServices;

use App\Models\TypeDocuments\TypeDocument;
use Exception;
use Illuminate\Support\Facades\DB;

class TypeDocumentServices
{
    public function getTypeDocuments()
    {
        $documents = TypeDocument::all();

        return [
            "error" => false,
            "code" => 200,
            "message" => count($documents) == 0
                ? "No hay tipos de documento registrados"
                : "Tipos de documento obtenidos con éxito",
            "data" => $documents
        ];
    }

    public function createTypeDocument(array $data)
    {
        $document = TypeDocument::create([
            'name' => $data['nombre'],
            'acronym' => $data['acronimo'],
        ]);

        return [
            'error' => false,
            'code' => 201,
            'message' => 'Tipo de documento creado con éxito',
            'data' => $document,
        ];
    }

    public function updateTypeDocument(array $data, $id)
    {
        try {
            DB::beginTransaction();

            $document = TypeDocument::find($id);

            if (!$document) {
                return [
                    "error" => true,
                    "code" => 404,
                    "message" => "El tipo de documento no existe",
                ];
            }

            $document->update([
                'name' => $data['nombre'],
                'acronym' => $data['acronimo'],
            ]);

            DB::commit();

            return [
                "error" => false,
                "code" => 200,
                "message" => "Tipo de documento actualizado con éxito",
                "data" => $document
            ];
        } catch (Exception $e) {
            DB::rollBack();
            return [
                "error" => true,
                "code" => 500,
                "message" => "Ocurrió un error al actualizar el tipo de documento",
            ];
        }
    }

    public function deleteTypeDocument($id)
    {
        $document = TypeDocument::find($id);

        if (!$document)
            return [
                "error" => true,
                "code" => 404,
                "message" => "El tipo de documento no existe",
            ];

        $document->delete();

        return [
            "error" => false,
            "code" => 200,
            "message" => "Tipo de documento eliminado con éxito",
        ];
    }
}
