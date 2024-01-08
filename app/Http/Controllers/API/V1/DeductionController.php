<?php

namespace App\Http\Controllers\API\V1;

use App\Traits\ResponseAPI;
use Illuminate\Http\Request;
use App\Imports\DeductionImport;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use App\Services\Deduction\DeductionServiceInterface;
use App\Http\Requests\{DeductionRequest, ImportDeductionRequest};

class DeductionController extends Controller
{
    use ResponseAPI;

    private $deductionService;

    public function __construct(DeductionServiceInterface $deductionService)
    {
        $this->middleware('auth:api');
        $this->deductionService = $deductionService;
    }

    public function index(Request $request)
    {
        try {
            $perPage = $request->input('per_page', 10);
            $search = $request->input('search');
            $period = $request->input('period');
            $deduction = $this->deductionService->index($perPage, $search, $period);
            return $this->success('Deduction retrieved successfully', $deduction);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function store(DeductionRequest $request)
    {
        try {
            $data = $request->validated();
            $deduction = $this->deductionService->store($data);
            return $this->success('Deduction created successfully', $deduction, 201);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function show($id)
    {
        try {
            $deduction = $this->deductionService->show($id);
            if (!$deduction) {
                return $this->error('Deduction not found', 404);
            }
            return $this->success('Deduction retrieved successfully', $deduction);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function update(DeductionRequest $request, $id)
    {
        try {
            $data = $request->validated();
            $deduction = $this->deductionService->update($id, $data);
            if (!$deduction) {
                return $this->error('Deduction not found', 404);
            }
            return $this->success('Deduction updated successfully', $deduction, 201);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function destroy($id)
    {
        try {
            $deduction = $this->deductionService->destroy($id);
            if (!$deduction) {
                return $this->error('Deduction not found', 404);
            }
            return $this->success('Deduction deleted successfully, id : '.$deduction->id, []);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function deductionEmployee(Request $request)
    {
        try {
            $perPage = $request->input('per_page', 10);
            $search = $request->input('search');
            $deduction = $this->deductionService->deductionEmployee($perPage, $search);
            return $this->success('Deduction employee retrieved successfully', $deduction);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function importDeduction(ImportDeductionRequest $request)
    {
        try {
            Excel::import(new DeductionImport, request()->file('file'));
            return $this->success('Deduction imported successfully', [], 201);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }
}
