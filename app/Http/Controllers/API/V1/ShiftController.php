<?php

namespace App\Http\Controllers\API\V1;

use App\Traits\ResponseAPI;
use Illuminate\Http\Request;
use App\Http\Requests\ShiftRequest;
use App\Http\Controllers\Controller;
use App\Services\Shift\ShiftServiceInterface;

class ShiftController extends Controller
{
    use ResponseAPI;

    private $shiftService;

    public function __construct(ShiftServiceInterface $shiftService)
    {
        $this->middleware('auth:api');
        $this->shiftService = $shiftService;
    }

    public function index(Request $request)
    {
        try {
            $perPage = $request->input('per_page', 10);
            $search = $request->input('search');
            $groupShiftId = $request->input('group_shift_id');
            $active = $request->input('active');
            $shifts = $this->shiftService->index($perPage, $search, $groupShiftId, $active);
            return $this->success('Shifts retrieved successfully', $shifts);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function store(ShiftRequest $request)
    {
        try {
            $data = $request->validated();
            $shift = $this->shiftService->store($data);
            return $this->success('Shift created successfully', $shift, 201);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function show($id)
    {
        try {
            $shift = $this->shiftService->show($id);
            if (!$shift) {
                return $this->error('Shift not found', 404);
            }
            return $this->success('Shift retrieved successfully', $shift);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function update(ShiftRequest $request, $id)
    {
        try {
            $data = $request->validated();
            $shift = $this->shiftService->update($id, $data);
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
            $shift = $this->shiftService->destroy($id);
            if (!$shift) {
                return $this->error('Shift not found', 404);
            }
            return $this->success('Shift deleted successfully, id : ' . $shift->id, []);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }
}
