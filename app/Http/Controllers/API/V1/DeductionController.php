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
            $import = new DeductionImport();
            $deduction = Excel::import($import, request()->file('file'));
            // Access the imported data
            $importedData = $import->getImportedData();
            // If the import is successful, you can return a success response
            return response()->json([
                'message' => 'Deduction imported successfully!',
                'success' => true,
                'code' => 200,
                'data' => $importedData,
            ], 200);
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $failures = $e->failures();
            $errorData = [];
            foreach ($failures as $failure) {
                if ($failure->attribute() == '0') {
                    $nameRow = 'KD_PGW';
                } else if ($failure->attribute() == '1') {
                    $nameRow = 'NILAI';
                } else if ($failure->attribute() == '4') {
                    $nameRow = 'PERIODE_PAYROLL';
                }
                $errorData[] = [
                    'lokasi_row' => $failure->row(),
                    'lokasi_column' => $nameRow,
                    'errors' => $failure->errors(),
                ];
            }
            return response()->json([
                'message' => 'Error Saat Proses Import.',
                'success' => false,
                'code' => 422,
                'data' => $errorData,
            ], 422);
        } catch (\Exception $e) {
            // If there's any other exception
            return response()->json([
                'message' => 'Error Saat Proses Import.: ' . $e->getMessage(),
                'success' => false,
                'code' => $e->getCode(),
                'data' => null,
            ], $e->getCode());
        }
    }
}
