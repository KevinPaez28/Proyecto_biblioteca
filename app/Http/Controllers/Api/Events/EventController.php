<?php

namespace App\Http\Controllers\Api\Events;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Http\Requests\Events\createEvent;
use App\Http\Requests\Events\updateEvent;
use App\Services\Events\EventServices;
use Illuminate\Http\Request;

class EventController extends Controller
{
    protected $EventServices;


    public function __construct(EventServices $Eventservices)
    {
        $this->EventServices = $Eventservices;
    }

    public function getAll(Request $request)
    {
        $filters = $request->only([
            'nombre',
            'encargado',
            'estado',
            'fecha',
            'sala'
        ]);

        $response = $this->EventServices->getEvents($filters);

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

    public function gettoday()
    {
        $response = $this->EventServices->gettoday();

        if ($response['error'])
            return ResponseFormatter::error($response['message'], $response['code']);

        return ResponseFormatter::success($response['message'], $response['code'], $response['data'] ?? []);
    }
    public function create(createEvent $request)
    {
        $data = $request->validated();

        $response = $this->EventServices->CreateEvents($data);


        if ($response['error'])
            return ResponseFormatter::error($response['message'], $response['code']);

        return ResponseFormatter::success($response['message'], $response['code'], $response['data'] ?? []);
    }
    public function update(updateEvent $request, string $id)
    {
        $data = $request->validated();

        $response = $this->EventServices->updateEvents($data, $id);

        if ($response['error'])
            return ResponseFormatter::error($response['message'], $response['code']);

        return ResponseFormatter::success($response['message'], $response['code'], $response['data'] ?? []);
    }
    public function delete(string $id)
    {
        $response = $this->EventServices->deleteEvents($id);

        if ($response['error'])
            return ResponseFormatter::error($response['message'], $response['code']);

        return ResponseFormatter::success($response['message'], $response['code'], $response['data'] ?? []);
    }
}
