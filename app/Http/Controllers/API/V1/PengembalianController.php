<?php

namespace App\Http\Controllers\API\V1;

use App\Traits\ResponseAPI;
use Illuminate\Http\Request;
use App\Imports\PengembalianImport;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Requests\{PengembalianRequest, ImportPengembalianRequest};
use App\Services\Pengembalian\PengembalianServiceInterface;

class PengembalianController extends Controller
{
    use ResponseAPI;

    private $pengembalianService;

    public function __construct(PengembalianServiceInterface $pengembalianService)
    {
        $this->middleware('auth:api');
        $this->pengembalianService = $pengembalianService;
    }

    public function index(Request $request)
    {
        try {
            $perPage = $request->input('per_page', 10);
            $search = $request->input('search');
            $pengembalian = $this->pengembalianService->index($perPage, $search);
            return $this->success('Pengembalian retrieved successfully', $pengembalian);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function store(PengembalianRequest $request)
    {
        try {
            $data = $request->validated();
            $pengembalian = $this->pengembalianService->store($data);
            return $this->success('Pengembalian created successfully', $pengembalian, 201);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function show($id)
    {
        try {
            $pengembalian = $this->pengembalianService->show($id);
            if (!$pengembalian) {
                return $this->error('Pengembalian not found', 404);
            }
            return $this->success('Pengembalian retrieved successfully', $pengembalian);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function update(PengembalianRequest $request, $id)
    {
        try {
            $data = $request->validated();
            $pengembalian = $this->pengembalianService->update($id, $data);
            if (!$pengembalian) {
                return $this->error('Pengembalian not found', 404);
            }
            return $this->success('Pengembalian updated successfully', $pengembalian, 201);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function destroy($id)
    {
        try {
            $pengembalian = $this->pengembalianService->destroy($id);
            if (!$pengembalian) {
                return $this->error('Pengembalian not found', 404);
            }
            return $this->success('Pengembalian deleted successfully, id : '.$pengembalian->id, []);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function pengembalianEmployee(Request $request)
    {
        try {
            $perPage = $request->input('per_page', 10);
            $search = $request->input('search');
            $pengembalian = $this->pengembalianService->pengembalianEmployee($perPage, $search);
            return $this->success('Pengembalian employee retrieved successfully', $pengembalian);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function importPengembalian(ImportPengembalianRequest $request)
    {
        try {
            $import = new PengembalianImport();
            $pengembalian = Excel::import($import, request()->file('file'));
            // Access the imported data
            $importedData = $import->getImportedData();
            // If the import is successful, you can return a success response
            return response()->json([
                'message' => 'Pengembalian imported successfully!',
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
                } else if ($failure->attribute() == '2') {
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
