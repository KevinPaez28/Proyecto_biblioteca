<?php

namespace App\Http\Controllers\Api\Program;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Http\Requests\Programa\createProgram;
use App\Http\Requests\Programa\updateProgram;
use App\Services\Program\ProgramServices;
use Illuminate\Http\Request;

class ProgramController extends Controller
{
    protected $programaServices;


    public function __construct(ProgramServices $programaServices)
    {
        $this->programaServices = $programaServices;
    }

    public function getAll()
    {
        $response = $this->programaServices->getProgramas();


        if ($response['error'])
            return ResponseFormatter::error($response['message'], $response['code']);

        return ResponseFormatter::success($response['message'], $response['code'], $response['data'] ?? []);
    }
    public function create(createProgram $request)
    {
        $data = $request->validated();

        $response = $this->programaServices->createPrograma($data);


        if ($response['error'])
            return ResponseFormatter::error($response['message'], $response['code']);

        return ResponseFormatter::success($response['message'], $response['code'], $response['data'] ?? []);
    }
    public function update(updateProgram $request, string $id)
    {
      $data = $request->validated();
  
      $response = $this->programaServices->updateProgram($data, $id);
  
      if ($response['error'])
        return ResponseFormatter::error($response['message'], $response['code']);
  
      return ResponseFormatter::success($response['message'], $response['code'], $response['data'] ?? []);
    }
    public function delete(string $id)
    {
        $response = $this->programaServices->deleteProgram($id);

        if ($response['error'])
            return ResponseFormatter::error($response['message'], $response['code']);

        return ResponseFormatter::success($response['message'], $response['code'], $response['data'] ?? []);
    }
}
