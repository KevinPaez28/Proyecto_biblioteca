<?php

namespace App\Services\Document;

use App\Models\Document\Documents;
use Exception;
use Illuminate\Support\Facades\DB;

class DocumentServices
{
    public function getDocument()
    {
        $document = Documents::all();

        if (count($document) == 0)
            return [
                "error" => false,
                "code" => 200,
                "message" => "No hay documentos registrados",
                "data" => $document
            ];


        return [
            "error" => false,
            "code" => 200,
            "message" => "Documentos obtenidos con éxito",
            "data" => $document
        ];
    }
    public function CreateDocument(array $data)
    {

        $document = Documents::Create([
            'users_id' => $data['users_id'],
            'title' => $data['titulo'],
            'path' => $data['ruta'],
            'extension' => $data['extension'],
            'type' => $data['tipo'],
            'size' => $data['tamano'],
        ]);
        if ($request->hasFile('document')) {
            $file = $request->file('document');
            $path = \Storage::disk('public')->putFile('Documents', $document);
        }

        return [
            'error' => false,
            'code' => 201,
            'message' => 'Documento creado con éxito',
            'data' => $document,
        ];
    }

    public function updateDocument(array $data, $id)
    {
        try {
            DB::beginTransaction();

            // Buscar el programa
            $document = Documents::find($id);

            if (!$document) {
                return [
                    "error" => true,
                    "code" => 404,
                    "message" => "El documento no existe",
                ];
            }

            $document->update([
                'users_id' => $data['users_id'],
                'title' => $data['title'],
                'path' => $data['path'],
                'extension' => $data['extension'],
                'type' => $data['type'],
                'size' => $data['size'],
            ]);
            //hace commit
            DB::commit();

            return [
                "error" => false,
                "code" => 200,
                "message" => "documentos actualizado con éxito",
                "data" => $document
            ];
        } catch (Exception $e) {
            //si genero error devuelve a la ultimo cambio de base de datos
            DB::rollback();
            return [
                "error" => true,
                "code" => 500,
                "message" => "Ocurrió un error al actualizar el documento",
            ];
        }
    }

    public function deleteDocument($id)
    {
        $document = Documents::find($id);


        if (!$document)
            return [
                "error" => true,
                "code" => 404,
                "message" => "El documento no existe",
            ];

        $document->delete();

        return [
            "error" => false,
            "code" => 200,
            "message" => "Documentos eliminado con éxito",
        ];
    }
}
