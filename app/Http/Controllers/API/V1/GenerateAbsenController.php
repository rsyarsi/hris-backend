<?php

namespace App\Http\Controllers\API\V1;

use App\Models\Unit;
use App\Traits\ResponseAPI;
use Illuminate\Http\Request;
use App\Models\GenerateAbsen;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use App\Http\Requests\UpdateGenerateAbsenRequest;
use App\Services\GenerateAbsen\GenerateAbsenServiceInterface;
use App\Exports\{MonitoringAbsenExport, MonitoringAbsenRekapExport};
use App\Http\Requests\{AbsenFromMobileRequest, GenerateAbsenRequest};

class GenerateAbsenController extends Controller
{
    use ResponseAPI;

    private $generateAbsenService;

    public function __construct(GenerateAbsenServiceInterface $generateAbsenService)
    {
        $this->middleware('auth:api');
        $this->generateAbsenService = $generateAbsenService;
    }

    public function index(Request $request)
    {
        try {
            $perPage = $request->input('per_page', 10);
            $search = $request->input('search');
            $period_1 = $request->input('period_1');
            $period_2 = $request->input('period_2');
            $unit = $request->input('unit');
            $generateabsens = $this->generateAbsenService->index($perPage, $search, $period_1, $period_2, $unit);
            return $this->success('Generate Absens retrieved successfully', $generateabsens);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function generateAbsenEmployee($employeeId)
    {
        try {
            $generateabsens = $this->generateAbsenService->generateAbsenEmployee($employeeId);
            return $this->success('Generate Absens employee retrieved successfully', $generateabsens);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function monitoringAbsen(Request $request)
    {
        try {
            $perPage = $request->input('per_page', 10);
            $search = $request->input('search');
            $period_1 = $request->input('period_1');
            $period_2 = $request->input('period_2');
            $unit = $request->input('unit');
            $generateabsens = $this->generateAbsenService->monitoringAbsen($perPage, $search, $period_1, $period_2, $unit);
            return $this->success('Monitoring Absens retrieved successfully', $generateabsens);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function generateAbsenSubordinate(Request $request)
    {
        try {
            $perPage = $request->input('per_page', 10);
            $search = $request->input('search');
            $period_1 = $request->input('period_1');
            $period_2 = $request->input('period_2');
            $unit = $request->input('unit');
            $generateabsens = $this->generateAbsenService->generateAbsenSubordinate($perPage, $search, $period_1, $period_2, $unit);
            return $this->success('Absens Subordinate retrieved successfully', $generateabsens);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function generateAbsenSubordinateMobile(Request $request)
    {
        try {
            $employmentId = $request->input('Employment_id');
            $search = $request->input('search');
            $period_1 = $request->input('period_1');
            $period_2 = $request->input('period_2');
            $unit = $request->input('unit');
            $generateabsens = $this->generateAbsenService->generateAbsenSubordinateMobile($employmentId, $search, $period_1, $period_2, $unit);
            return $this->success('Absens Subordinate Mobile retrieved successfully', $generateabsens);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function store(GenerateAbsenRequest $request)
    {
        try {
            $data = $request->validated();
            $generateabsen = $this->generateAbsenService->store($data);
            return $this->success('Generate Absen created successfully', $generateabsen, 201);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function show($id)
    {
        try {
            $generateabsen = $this->generateAbsenService->show($id);
            if (!$generateabsen) {
                return $this->error('Generate Absen not found', 404);
            }
            return $this->success('Generate Absen retrieved successfully', $generateabsen);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function update(UpdateGenerateAbsenRequest $request, $id)
    {
        try {
            $data = $request->validated();
            $generateabsen = $this->generateAbsenService->update($id, $data);
            if (!$generateabsen) {
                return $this->error('Generate Absen not found', 404);
            }
            return $this->success('Generate Absen updated successfully', $generateabsen, 201);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function destroy($id)
    {
        try {
            $generateabsen = $this->generateAbsenService->destroy($id);
            if (!$generateabsen) {
                return $this->error('Generate Absen not found', 404);
            }
            return $this->success('Generate Absen deleted successfully, id : '.$generateabsen->id, []);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function executeStoredProcedure(GenerateAbsenRequest $request)
    {
        try {
            $period1 = $request->input('period_1');
            $period2 = $request->input('period_2');
            $generateAbsen = GenerateAbsen::executeStoredProcedure($period1, $period2);
            return $this->success('Generate Absen successfully', $generateAbsen, 201);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function absenFromMobile(AbsenFromMobileRequest $request)
    {
        try {
            $data = $request->validated();
            $ipAddress = str_replace('.', '', $request->input('Ip_address'));
            $ipAddressServer = '17216';
            // Extract the first 5 characters from $ipAddress
            $firstFiveCharacters = substr($ipAddress, 0, 5);
            if ($firstFiveCharacters == substr($ipAddressServer, 0, 5)) {
                $generateabsen = $this->generateAbsenService->absenFromMobile($data);
                return response()->json([
                    'message' => $generateabsen['message'],
                    'success' => 'true',
                    'code' => 200,
                    'data' => $generateabsen['data'],
                ]);
            } else {
                return response()->json([
                    'message' => 'Anda tidak berada di jaringan yang ditentukan!',
                    'success' => 'false',
                    'code' => 422,
                    'data' => [],
                ]);
            }
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function exportMonitoringAbsen(Request $request)
    {
        try {
            $period1 = $request->input('period_1');
            $period2 = $request->input('period_2');
            $nameFile = 'data-monitoring-absen-'.date("Y-m-d", strtotime($period1)).'-'.date("Y-m-d", strtotime($period2)).'.xlsx';
            return Excel::download(new MonitoringAbsenExport, $nameFile);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function exportMonitoringAbsenRekap(Request $request)
    {
        try {
            $export = new MonitoringAbsenRekapExport();
            $nameFile = 'data-monitoring-absen-rekap.xlsx';
            return Excel::download($export, $nameFile);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }
}
