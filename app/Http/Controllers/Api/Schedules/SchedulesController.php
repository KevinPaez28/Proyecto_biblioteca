<?php

namespace App\Http\Controllers\Api\Schedules;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Http\Requests\Schedulesy\createSchedules;
use App\Http\Requests\Schedulesy\updateSchedules;
use App\Services\Schedules\SchedulesServices;
use Illuminate\Http\Request;

class SchedulesController extends Controller
{
    protected $ScheduleServices;


    public function __construct(SchedulesServices $Scheduleservices)
    {
        $this->ScheduleServices = $Scheduleservices;
    }

    public function getAll()
    {
        $response = $this->ScheduleServices->getSchedules();


        if ($response['error'])
            return ResponseFormatter::error($response['message'], $response['code']);

        return ResponseFormatter::success($response['message'], $response['code'], $response['data'] ?? []);
    }
    public function jornadasandhorarios()
    {
        $response = $this->ScheduleServices->getJornadasAndHorarios();

        if ($response['error'])
            return ResponseFormatter::error($response['message'], $response['code']);

        return ResponseFormatter::success($response['message'], $response['code'], $response['data'] ?? []);
    }

    public function create(createSchedules $request)
    {
        $data = $request->validated();

        $response = $this->ScheduleServices->CreateSchedules($data);


        if ($response['error'])
            return ResponseFormatter::error($response['message'], $response['code']);

        return ResponseFormatter::success($response['message'], $response['code'], $response['data'] ?? []);
    }
    public function update(updateSchedules $request, string $id)
    {
        $data = $request->validated();

        $response = $this->ScheduleServices->updateSchedules($data, $id);

        if ($response['error'])
            return ResponseFormatter::error($response['message'], $response['code']);

        return ResponseFormatter::success($response['message'], $response['code'], $response['data'] ?? []);
    }
    public function delete(string $id)
    {
        $response = $this->ScheduleServices->deleteSchedules($id);

        if ($response['error'])
            return ResponseFormatter::error($response['message'], $response['code']);

        return ResponseFormatter::success($response['message'], $response['code'], $response['data'] ?? []);
    }
}
