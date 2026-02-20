<?php

namespace App\Http\Controllers\Api\Documents;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Http\Requests\Document\createDocument;
use App\Http\Requests\Document\updateDocument;
use App\Services\Document\DocumentServices;
use Illuminate\Http\Request;

class DocumentController extends Controller
{
    protected $DocumentServices;


    public function __construct(DocumentServices $documentservices)
    {
        $this->DocumentServices = $documentservices;
    }

    public function getAll()
    {
        $response = $this->DocumentServices->getDocument();


        if ($response['error'])
            return ResponseFormatter::error($response['message'], $response['code']);

        return ResponseFormatter::success($response['message'], $response['code'], $response['data'] ?? []);
    }

    // public function create(createDocument $request)
    // {
    //     $data = $request->validated();

    //     $response = $this->DocumentServices->createdocument($data);


    //     if ($response['error'])
    //         return ResponseFormatter::error($response['message'], $response['code']);

    //     return ResponseFormatter::success($response['message'], $response['code'], $response['data'] ?? []);
    // }
    public function update(updateDocument $request, string $id)
    {
        $data = $request->validated();

        $response = $this->DocumentServices->updateDocument($data, $id);

        if ($response['error'])
            return ResponseFormatter::error($response['message'], $response['code']);

        return ResponseFormatter::success($response['message'], $response['code'], $response['data'] ?? []);
    }
    public function delete(string $id)
    {
        $response = $this->DocumentServices->deleteDocument($id);

        if ($response['error'])
            return ResponseFormatter::error($response['message'], $response['code']);

        return ResponseFormatter::success($response['message'], $response['code'], $response['data'] ?? []);
    }
}
