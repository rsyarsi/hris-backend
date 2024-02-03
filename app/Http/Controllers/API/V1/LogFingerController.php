<?php

namespace App\Http\Controllers\API\V1;

use App\Traits\ResponseAPI;
use Illuminate\Http\Request;
use App\Imports\LogFingerImport;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use App\Services\LogFinger\LogFingerServiceInterface;
use App\Http\Requests\{LogFingerRequest, ImportLogFingerRequest};

class LogFingerController extends Controller
{
    use ResponseAPI;

    private $logfingerService;

    public function __construct(LogFingerServiceInterface $logfingerService)
    {
        $this->middleware('auth:api');
        $this->logfingerService = $logfingerService;
    }

    public function index(Request $request)
    {
        try {
            $perPage = $request->input('per_page', 10);
            $search = $request->input('search');
            $startDate = $request->input('start_date');
            $endDate = $request->input('end_date');
            $logfingers = $this->logfingerService->index($perPage, $search, $startDate, $endDate);
            return $this->success('Log Fingers retrieved successfully', $logfingers);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function store(LogFingerRequest $request)
    {
        try {
            $data = $request->validated();
            $logfinger = $this->logfingerService->store($data);
            return $this->success('Log Finger created successfully', $logfinger, 201);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function show($id)
    {
        try {
            $logfinger = $this->logfingerService->show($id);
            if (!$logfinger) {
                return $this->error('Log Finger not found', 404);
            }
            return $this->success('Log Finger retrieved successfully', $logfinger);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function update(LogFingerRequest $request, $id)
    {
        try {
            $data = $request->validated();
            $logfinger = $this->logfingerService->update($id, $data);
            if (!$logfinger) {
                return $this->error('Log Finger not found', 404);
            }
            return $this->success('Log Finger updated successfully', $logfinger, 201);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function destroy($id)
    {
        try {
            $logfinger = $this->logfingerService->destroy($id);
            if (!$logfinger) {
                return $this->error('Log Finger not found', 404);
            }
            return $this->success('Log Finger deleted successfully, id : '.$logfinger->id, []);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function importLogFinger(ImportLogFingerRequest $request)
    {
        try {
            $import = new LogFingerImport();
            $logFinger = Excel::import($import, request()->file('file'));
            // Access the imported data
            $importedData = $import->getImportedData();
            // If the import is successful, you can return a success response
            return response()->json([
                'message' => 'Log Finger imported successfully!',
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
                    $nameRow = 'CODE_SN_FINGER';
                } else if ($failure->attribute() == '2') {
                    $nameRow = 'DATETIME';
                } else if ($failure->attribute() == '3') {
                    $nameRow = 'MANUAL';
                } else if ($failure->attribute() == '4') {
                    $nameRow = 'CODE_PIN';
                } else if ($failure->attribute() == '5') {
                    $nameRow = 'TIME_IN';
                } else if ($failure->attribute() == '6') {
                    $nameRow = 'TIME_OUT';
                } else if ($failure->attribute() == '7') {
                    $nameRow = 'TANGGAL_LOG';
                } else if ($failure->attribute() == '8') {
                    $nameRow = 'ABSEN_TYPE';
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

    public function logFingerUser(Request $request)
    {
        try {
            $perPage = $request->input('per_page', 10);
            $startDate = $request->input('start_date');
            $endDate = $request->input('end_date');
            $logfingers = $this->logfingerService->logFingerUser($perPage, $startDate, $endDate);
            return $this->success('Log Fingers user retrieved successfully', $logfingers);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }
}
