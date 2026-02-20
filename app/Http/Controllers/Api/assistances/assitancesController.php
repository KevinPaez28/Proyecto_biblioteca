<?php

namespace App\Http\Controllers\Api\assistances;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Http\Requests\assistances\createAssistances;
use App\Http\Requests\assistances\createEventAssistance;
use App\Http\Requests\assistances\updateAssistances;
use App\Services\assitances\assitanceServices;
use Illuminate\Http\Request;

class assitancesController extends Controller
{
    protected $assitancesServices;


    public function __construct(assitanceServices $assistancesservices)
    {
        $this->assitancesServices = $assistancesservices;
    }

    public function getAll(Request $request)
    {
        $filters = $request->only([
            'nombre',
            'apellido',
            'documento',
            'ficha',
            'fecha',
            'document_type_id',
            'motivo',
            'rol'
        ]);

        // Leer `page` y `per_page`
        $page     = $request->get('page', 1);
        $per_page = $request->get('per_page', 10);

        $response = $this->assitancesServices->getAssistances($filters, $page, $per_page);

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
    public function getexportreason(Request $request)
    {
        $filters = $request->only([
            'fecha_inicio',
            'fecha_fin',
        ]);

        $response = $this->assitancesServices->exportAssistancesByReason($filters);

        if ($response['error']) {
            return ResponseFormatter::error($response['message'], $response['code']);
        }

        return $response['data'];
    }
    public function getexport(Request $request)
    {
        $filters = $request->only([
            'fecha_inicio',
            'fecha_fin',
        ]);

        $response = $this->assitancesServices->exportAssistances($filters);

        if ($response['error']) {
            return ResponseFormatter::error($response['message'], $response['code']);
        }

        return $response['data'];
    }


    public function getEvents(Request $request)
    {
        $filters = $request->only([
            'nombre',
            'apellido',
            'documento',
            'ficha',
            'fecha',
            'motivo',
            'rol'
        ]);

        $response = $this->assitancesServices->getEventAttendances($filters);

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


    public function getByMonth()
    {
        $response = $this->assitancesServices->getAssistancesByMonth();


        if ($response['error'])
            return ResponseFormatter::error($response['message'], $response['code']);

        return ResponseFormatter::success($response['message'], $response['code'], $response['data'] ?? []);
    }

    public function getByEvent()
    {
        $response = $this->assitancesServices->getAssistancesByEvent();


        if ($response['error'])
            return ResponseFormatter::error($response['message'], $response['code']);

        return ResponseFormatter::success($response['message'], $response['code'], $response['data'] ?? []);
    }
    public function create(createAssistances $request)
    {
        $data = $request->validated();

        $response = $this->assitancesServices->CreateAssistances($data);


        if ($response['error'])
            return ResponseFormatter::error($response['message'], $response['code']);

        return ResponseFormatter::success($response['message'], $response['code'], $response['data'] ?? []);
    }
    public function createEventAssistance(createEventAssistance $request)
    {
        $data = $request->validated();

        $response = $this->assitancesServices->createByEventAndFicha($data);


        if ($response['error'])
            return ResponseFormatter::error($response['message'], $response['code']);

        return ResponseFormatter::success($response['message'], $response['code'], $response['data'] ?? []);
    }
    public function update(updateAssistances $request, string $id)
    {
        $data = $request->validated();

        return response()->json([
            'success' => true,
            'code'    => 200,
            'message' => 'Datos recibidos correctamente',
            'data'    => $data
        ]);
    }
    public function deleteAprendices(Request $request)
    {
        // Tomamos ficha y event_id desde query params
        $data = [
            'ficha' => $request->query('ficha'),
            'event_id' => $request->query('event_id'), // opcional
        ];

        if (empty($data['ficha'])) {
            return ResponseFormatter::error('La ficha es obligatoria', 422);
        }

        $response = $this->assitancesServices->deleteAllByFicha($data);

        if ($response['error']) {
            return ResponseFormatter::error($response['message'], $response['code']);
        }

        return ResponseFormatter::success(
            $response['message'],
            $response['code'],
            $response['data'] ?? []
        );
    }



    public function getTotalByDay()
    {
        $response = $this->assitancesServices->getTotalByDay();


        if ($response['error'])
            return ResponseFormatter::error($response['message'], $response['code']);

        return ResponseFormatter::success($response['message'], $response['code'], $response['data'] ?? []);
    }

    public function getTotalByWeek()
    {
        $response = $this->assitancesServices->getTotalByWeek();


        if ($response['error'])
            return ResponseFormatter::error($response['message'], $response['code']);

        return ResponseFormatter::success($response['message'], $response['code'], $response['data'] ?? []);
    }

    public function getTotalByMonth()
    {
        $response = $this->assitancesServices->getTotalByMonth();


        if ($response['error'])
            return ResponseFormatter::error($response['message'], $response['code']);

        return ResponseFormatter::success($response['message'], $response['code'], $response['data'] ?? []);
    }

    public function getTotalGraduates()
    {
        $response = $this->assitancesServices->getTotalGraduates();


        if ($response['error'])
            return ResponseFormatter::error($response['message'], $response['code']);

        return ResponseFormatter::success($response['message'], $response['code'], $response['data'] ?? []);
    }
}
