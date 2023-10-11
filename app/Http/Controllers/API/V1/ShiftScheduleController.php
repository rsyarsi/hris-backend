<?php

namespace App\Http\Controllers\API\V1;

use App\Traits\ResponseAPI;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\ShiftScheduleRequest;
use App\Services\ShiftSchedule\ShiftScheduleServiceInterface;

class ShiftScheduleController extends Controller
{
    use ResponseAPI;

    private $shiftScheduleService;

    public function __construct(ShiftScheduleServiceInterface $shiftScheduleService)
    {
        $this->middleware('auth:api');
        $this->shiftScheduleService = $shiftScheduleService;
    }

    public function index(Request $request)
    {
        try {
            $perPage = $request->input('per_page', 10);
            $search = $request->input('search');
            $shiftSchedules = $this->shiftScheduleService->index($perPage, $search);
            return $this->success('Shift Schedule retrieved successfully', $shiftSchedules);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function store(ShiftScheduleRequest $request)
    {
        try {
            $data = $request->validated();
            $shiftSchedule = $this->shiftScheduleService->store($data);
            return $this->success('Shift Schedule created successfully', $shiftSchedule, 201);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function show($id)
    {
        try {
            $shiftSchedule = $this->shiftScheduleService->show($id);
            if (!$shiftSchedule) {
                return $this->error('Shift Schedule not found', 404);
            }
            return $this->success('Shift Schedule retrieved successfully', $shiftSchedule);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function update(ShiftScheduleRequest $request, $id)
    {
        try {
            $data = $request->validated();
            $shiftSchedule = $this->shiftScheduleService->update($id, $data);
            if (!$shiftSchedule) {
                return $this->error('Shift Schedule not found', 404);
            }
            return $this->success('Shift Schedule updated successfully', $shiftSchedule, 201);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function destroy($id)
    {
        try {
            $shiftSchedule = $this->shiftScheduleService->destroy($id);
            if (!$shiftSchedule) {
                return $this->error('Shift Schedule not found', 404);
            }
            return $this->success('Shift Schedule deleted successfully, id : '.$shiftSchedule->id, []);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function storeMultiple(ShiftScheduleRequest $request)
    {
        try {
            $data = $request->validated();
            $shiftSchedule = $this->shiftScheduleService->storeMultiple($data);
            return $this->success('Shift schedule created successfully', $shiftSchedule, 201);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }
}
