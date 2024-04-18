<?php

namespace App\Http\Controllers\API\V1;

use App\Traits\ResponseAPI;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\LogGenerateAbsen\LogGenerateAbsenServiceInterface;

class LogGenerateAbsenController extends Controller
{
    use ResponseAPI;

    private $logGenerateAbsenService;

    public function __construct(LogGenerateAbsenServiceInterface $logGenerateAbsenService)
    {
        $this->middleware('auth:api');
        $this->logGenerateAbsenService = $logGenerateAbsenService;
    }

    public function index(Request $request)
    {
        try {
            $perPage = $request->input('per_page', 10);
            $search = $request->input('search');
            $period_1 = $request->input('period_1');
            $period_2 = $request->input('period_2');
            $unit = $request->input('unit');
            $logGenerateabsens = $this->logGenerateAbsenService->index($perPage, $search, $period_1, $period_2, $unit);
            return $this->success('Log Generate Absens retrieved successfully', $logGenerateabsens);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function show($id)
    {
        try {
            $logGenerateabsen = $this->logGenerateAbsenService->show($id);
            if (!$logGenerateabsen) {
                return $this->error('Log Generate Absen not found', 404);
            }
            return $this->success('Log Generate Absen retrieved successfully', $logGenerateabsen);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }
}
