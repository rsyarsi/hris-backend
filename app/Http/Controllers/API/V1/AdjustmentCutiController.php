<?php

namespace App\Http\Controllers\API\V1;

use App\Traits\ResponseAPI;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\AdjustmentCutiExport;
use App\Http\Requests\AdjustmentCutiRequest;
use App\Services\AdjustmentCuti\AdjustmentCutiServiceInterface;

class AdjustmentCutiController extends Controller
{
    use ResponseAPI;

    private $adjustmentCutiService;

    public function __construct(AdjustmentCutiServiceInterface $adjustmentcutiService)
    {
        $this->middleware('auth:api');
        $this->adjustmentCutiService = $adjustmentcutiService;
    }

    public function index(Request $request)
    {
        try {
            $perPage = $request->input('per_page', 10);
            $search = $request->input('search');
            $adjustmentCuti = $this->adjustmentCutiService->index($perPage, $search);
            return $this->success('Adjustment Cuti retrieved successfully', $adjustmentCuti);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function store(AdjustmentCutiRequest $request)
    {
        try {
            $data = $request->validated();
            $adjustmentCuti = $this->adjustmentCutiService->store($data);
            return $this->success('Adjustment Cuti created successfully', $adjustmentCuti, 201);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function show($id)
    {
        try {
            $adjustmentCuti = $this->adjustmentCutiService->show($id);
            if (!$adjustmentCuti) {
                return $this->error('Adjustment Cuti not found', 404);
            }
            return $this->success('Adjustment Cuti retrieved successfully', $adjustmentCuti);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function update(AdjustmentCutiRequest $request, $id)
    {
        try {
            $data = $request->validated();
            $adjustmentCuti = $this->adjustmentCutiService->update($id, $data);
            if (!$adjustmentCuti) {
                return $this->error('Adjustment Cuti not found', 404);
            }
            return $this->success('Adjustment Cuti updated successfully', $adjustmentCuti, 201);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function destroy($id)
    {
        try {
            $adjustmentCuti = $this->adjustmentCutiService->destroy($id);
            if (!$adjustmentCuti) {
                return $this->error('Adjustment Cuti not found', 404);
            }
            return $this->success('Adjustment Cuti deleted successfully, id : '.$adjustmentCuti->id, []);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function adjustmentCutiEmployee(Request $request)
    {
        try {
            $perPage = $request->input('per_page', 10);
            $search = $request->input('search');
            $employeeId = $request->input('employee_id');
            $adjustmentCuti = $this->adjustmentCutiService->adjustmentCutiEmployee($perPage, $search, $employeeId);
            return $this->success('Adjustment Cuti employee retrieved successfully', $adjustmentCuti);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function exportAdjustmentCuti(Request $request)
    {
        try {
            $year = $request->input('year');
            $nameFile = 'data-adjustment-cuti-'.$year.'.xlsx';
            return Excel::download(new AdjustmentCutiExport, $nameFile);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }
}
