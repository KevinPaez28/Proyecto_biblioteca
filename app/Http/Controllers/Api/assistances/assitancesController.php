<?php

namespace App\Http\Controllers\Api\assistances;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Http\Requests\assistances\createAssistances;
use App\Http\Requests\assistances\createEventAssistance;
use App\Http\Requests\assistances\updateAssistances;
use App\Services\assitances\assitanceServices;
use Illuminate\Http\Request;

/**
 * Controlador para la Gestión de Asistencias.
 * * Se encarga de coordinar el registro de ingresos/salidas generales, 
 * asistencia a eventos específicos y la generación de reportes métricos.
 */
class assitancesController extends Controller
{
    // Servicio que contiene la lógica de negocio de asistencias
    protected $assitancesServices;

    /**
     * Constructor que inyecta el servicio siguiendo la arquitectura del proyecto.
     */
    public function __construct(assitanceServices $assistancesservices)
    {
        $this->assitancesServices = $assistancesservices;
    }

    /**
     * Obtiene todas las asistencias con filtros aplicados y paginación.
     */
    public function getAll(Request $request)
    {
        // Filtros permitidos para la consulta
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

        // Manejo de paginación desde la request
        $page     = $request->get('page', 1);
        $per_page = $request->get('per_page', 10);

        $response = $this->assitancesServices->getAssistances($filters, $page, $per_page);

        if ($response['error']) {
            return ResponseFormatter::error($response['message'], $response['code']);
        }

        return ResponseFormatter::success($response['message'], $response['code'], $response['data'] ?? []);
    }

    /**
     * Exporta los datos de asistencia agrupados por el motivo de ingreso.
     */
    public function getexportreason(Request $request)
    {
        $filters = $request->only(['fecha_inicio', 'fecha_fin']);

        $response = $this->assitancesServices->exportAssistancesByReason($filters);

        if ($response['error']) {
            return ResponseFormatter::error($response['message'], $response['code']);
        }

        return $response['data'];
    }

    /**
     * Exportación general de registros de asistencia.
     */
    public function getexport(Request $request)
    {
        $filters = $request->only(['fecha_inicio', 'fecha_fin']);

        $response = $this->assitancesServices->exportAssistances($filters);

        if ($response['error']) {
            return ResponseFormatter::error($response['message'], $response['code']);
        }

        return $response['data'];
    }

    /**
     * Obtiene registros de asistencia vinculados a eventos programados.
     */
    public function getEvents(Request $request)
    {
        $filters = $request->only(['nombre', 'apellido', 'documento', 'ficha', 'fecha', 'motivo', 'rol']);

        $response = $this->assitancesServices->getEventAttendances($filters);

        if ($response['error']) {
            return ResponseFormatter::error($response['message'], $response['code']);
        }

        return ResponseFormatter::success($response['message'], $response['code'], $response['data'] ?? []);
    }

    /**
     * Métricas: Obtiene el conteo de asistencias por mes.
     */
    public function getByMonth()
    {
        $response = $this->assitancesServices->getAssistancesByMonth();

        if ($response['error'])
            return ResponseFormatter::error($response['message'], $response['code']);

        return ResponseFormatter::success($response['message'], $response['code'], $response['data'] ?? []);
    }

    /**
     * Métricas: Obtiene el conteo de asistencias por tipo de evento.
     */
    public function getByEvent()
    {
        $response = $this->assitancesServices->getAssistancesByEvent();

        if ($response['error'])
            return ResponseFormatter::error($response['message'], $response['code']);

        return ResponseFormatter::success($response['message'], $response['code'], $response['data'] ?? []);
    }

    /**
     * Crea un registro de asistencia estándar.
     */
    public function create(createAssistances $request)
    {
        $data = $request->validated();
        $response = $this->assitancesServices->CreateAssistances($data);

        if ($response['error'])
            return ResponseFormatter::error($response['message'], $response['code']);

        return ResponseFormatter::success($response['message'], $response['code'], $response['data'] ?? []);
    }

    /**
     * Registra asistencia masiva basada en una ficha y un evento.
     */
    public function createEventAssistance(createEventAssistance $request)
    {
        $data = $request->validated();
        $response = $this->assitancesServices->createByEventAndFicha($data);

        if ($response['error'])
            return ResponseFormatter::error($response['message'], $response['code']);

        return ResponseFormatter::success($response['message'], $response['code'], $response['data'] ?? []);
    }

    /**
     * Actualiza un registro de asistencia.
     */
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

    /**
     * Elimina registros de aprendices filtrados por ficha y/o evento.
     */
    public function deleteAprendices(Request $request)
    {
        $data = [
            'ficha' => $request->query('ficha'),
            'event_id' => $request->query('event_id'),
        ];

        if (empty($data['ficha'])) {
            return ResponseFormatter::error('La ficha es obligatoria', 422);
        }

        $response = $this->assitancesServices->deleteAllByFicha($data);

        if ($response['error']) {
            return ResponseFormatter::error($response['message'], $response['code']);
        }

        return ResponseFormatter::success($response['message'], $response['code'], $response['data'] ?? []);
    }

    // --- Métodos de Totales y Estadísticas ---

    public function getTotalByDay()
    {
        $response = $this->assitancesServices->getTotalByDay();
        if ($response['error']) return ResponseFormatter::error($response['message'], $response['code']);
        return ResponseFormatter::success($response['message'], $response['code'], $response['data'] ?? []);
    }

    public function getTotalByWeek()
    {
        $response = $this->assitancesServices->getTotalByWeek();
        if ($response['error']) return ResponseFormatter::error($response['message'], $response['code']);
        return ResponseFormatter::success($response['message'], $response['code'], $response['data'] ?? []);
    }

    public function getTotalByMonth()
    {
        $response = $this->assitancesServices->getTotalByMonth();
        if ($response['error']) return ResponseFormatter::error($response['message'], $response['code']);
        return ResponseFormatter::success($response['message'], $response['code'], $response['data'] ?? []);
    }

    public function getTotalGraduates()
    {
        $response = $this->assitancesServices->getTotalGraduates();
        if ($response['error']) return ResponseFormatter::error($response['message'], $response['code']);
        return ResponseFormatter::success($response['message'], $response['code'], $response['data'] ?? []);
    }
}
