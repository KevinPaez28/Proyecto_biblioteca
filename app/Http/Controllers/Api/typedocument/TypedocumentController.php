<?php

namespace App\Http\Controllers\Api\TypeDocument;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Http\Requests\Createtypedocument\Createtypedocument;
use App\Http\Requests\TypeDocument\updateTypeDocument;
use App\Services\TypedocumentServices\TypeDocumentServices;

class TypeDocumentController extends Controller
{
    protected $typeDocumentServices;

    public function __construct(TypeDocumentServices $typeDocumentServices)
    {
        $this->typeDocumentServices = $typeDocumentServices;
    }

    public function getAll()
    {
        $response = $this->typeDocumentServices->getTypeDocuments();

        if ($response['error'])
            return ResponseFormatter::error($response['message'], $response['code']);

        return ResponseFormatter::success($response['message'], $response['code'], $response['data'] ?? []);
    }

    public function create(Createtypedocument $request)
    {
        $data = $request->validated();
        $response = $this->typeDocumentServices->createTypeDocument($data);

        if ($response['error'])
            return ResponseFormatter::error($response['message'], $response['code']);

        return ResponseFormatter::success($response['message'], $response['code'], $response['data'] ?? []);
    }

    public function update(updateTypeDocument $request, string $id)
    {
        $data = $request->validated();
        $response = $this->typeDocumentServices->updateTypeDocument($data, $id);

        if ($response['error'])
            return ResponseFormatter::error($response['message'], $response['code']);

        return ResponseFormatter::success($response['message'], $response['code'], $response['data'] ?? []);
    }

    public function delete(string $id)
    {
        $response = $this->typeDocumentServices->deleteTypeDocument($id);

        if ($response['error'])
            return ResponseFormatter::error($response['message'], $response['code']);

        return ResponseFormatter::success($response['message'], $response['code'], $response['data'] ?? []);
    }
}
