<?php

namespace App\Http\Controllers\API\V1;

use App\Traits\ResponseAPI;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\ShiftScheduleExchangeRequest;
use App\Services\ShiftScheduleExchange\ShiftScheduleExchangeServiceInterface;

class ShiftScheduleExchangeController extends Controller
{
    use ResponseAPI;

    private $shiftScheduleExchangeService;

    public function __construct(ShiftScheduleExchangeServiceInterface $shiftScheduleExchangeService)
    {
        $this->middleware('auth:api');
        $this->shiftScheduleExchangeService = $shiftScheduleExchangeService;
    }

    public function index(Request $request)
    {
        try {
            $perPage = $request->input('per_page', 10);
            $search = $request->input('search');
            $startDate = $request->input('start_date');
            $endDate = $request->input('end_date');
            $shifts = $this->shiftScheduleExchangeService->index($perPage, $search, $startDate, $endDate);
            return $this->success('Shifts retrieved successfully', $shifts);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function store(ShiftScheduleExchangeRequest $request)
    {
        try {
            $data = $request->validated();
            $shift = $this->shiftScheduleExchangeService->store($data);
            return $this->success('Shift created successfully', $shift, 201);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function show($id)
    {
        try {
            $shift = $this->shiftScheduleExchangeService->show($id);
            if (!$shift) {
                return $this->error('Shift not found', 404);
            }
            return $this->success('Shift retrieved successfully', $shift);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function update(ShiftScheduleExchangeRequest $request, $id)
    {
        try {
            $data = $request->validated();
            $shift = $this->shiftScheduleExchangeService->update($id, $data);
            if (!$shift) {
                return $this->error('Shift not found', 404);
            }
            return $this->success('Shift updated successfully', $shift, 201);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function destroy($id)
    {
        try {
            $shift = $this->shiftScheduleExchangeService->destroy($id);
            if (!$shift) {
                return $this->error('Shift not found', 404);
            }
            return $this->success('Shift deleted successfully, id : '.$shift->id, []);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }
}
