<?php

namespace App\Http\Controllers\API\V1;

use App\Traits\ResponseAPI;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\CatatanCutiRequest;
use App\Services\CatatanCuti\CatatanCutiServiceInterface;

class CatatanCutiController extends Controller
{
    use ResponseAPI;

    private $catatanCutiService;

    public function __construct(CatatanCutiServiceInterface $catatancutiService)
    {
        $this->middleware('auth:api');
        $this->catatanCutiService = $catatancutiService;
    }

    public function index(Request $request)
    {
        try {
            $perPage = $request->input('per_page', 10);
            $search = $request->input('search');
            $catatanCuti = $this->catatanCutiService->index($perPage, $search);
            return $this->success('Catatan Cuti retrieved successfully', $catatanCuti);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function store(CatatanCutiRequest $request)
    {
        try {
            $data = $request->validated();
            $catatanCuti = $this->catatanCutiService->store($data);
            return $this->success('Catatan Cuti created successfully', $catatanCuti, 201);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function show($id)
    {
        try {
            $catatanCuti = $this->catatanCutiService->show($id);
            if (!$catatanCuti) {
                return $this->error('Catatan Cuti not found', 404);
            }
            return $this->success('Catatan Cuti retrieved successfully', $catatanCuti);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function update(CatatanCutiRequest $request, $id)
    {
        try {
            $data = $request->validated();
            $catatanCuti = $this->catatanCutiService->update($id, $data);
            if (!$catatanCuti) {
                return $this->error('Catatan Cuti not found', 404);
            }
            return $this->success('Catatan Cuti updated successfully', $catatanCuti, 201);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function destroy($id)
    {
        try {
            $catatanCuti = $this->catatanCutiService->destroy($id);
            if (!$catatanCuti) {
                return $this->error('Catatan Cuti not found', 404);
            }
            return $this->success('Catatan Cuti deleted successfully, id : '.$catatanCuti->id, []);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function catatanCutiEmployee(Request $request)
    {
        try {
            $perPage = $request->input('per_page', 10);
            $search = $request->input('search');
            $employeeId = $request->input('employee_id');
            $catatanCuti = $this->catatanCutiService->catatanCutiEmployee($perPage, $search, $employeeId);
            return $this->success('Catatan Cuti employee retrieved successfully', $catatanCuti);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }
}
