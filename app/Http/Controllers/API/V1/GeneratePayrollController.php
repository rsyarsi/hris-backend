<?php

namespace App\Http\Controllers\API\V1;

use Carbon\Carbon;
use App\Traits\ResponseAPI;
use Illuminate\Http\Request;
use App\Models\GeneratePayroll;
use App\Http\Controllers\Controller;
use App\Http\Requests\GeneratePayrollRequest;
use App\Services\GeneratePayroll\GeneratePayrollServiceInterface;

class GeneratePayrollController extends Controller
{
    use ResponseAPI;

    private $generatePayrollService;

    public function __construct(GeneratePayrollServiceInterface $generatePayrollService)
    {
        $this->middleware('auth:api');
        $this->generatePayrollService = $generatePayrollService;
    }

    public function index(Request $request)
    {
        try {
            $perPage = $request->input('per_page', 10);
            $search = $request->input('search');
            $period_1 = $request->input('period_1');
            $period_2 = $request->input('period_2');
            $unit = $request->input('unit');
            $generatepayrolls = $this->generatePayrollService->index($perPage, $search, $period_1, $period_2, $unit);
            return $this->success('Generate Payrolls retrieved successfully', $generatepayrolls);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function store(GeneratePayrollRequest $request)
    {
        try {
            $data = $request->validated();
            $generatepayroll = $this->generatePayrollService->store($data);
            return $this->success('Generate Payroll created successfully', $generatepayroll, 201);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function show($id)
    {
        try {
            $generatepayroll = $this->generatePayrollService->show($id);
            if (!$generatepayroll) {
                return $this->error('Generate Payroll not found', 404);
            }
            return $this->success('Generate Payroll retrieved successfully', $generatepayroll);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function update(GeneratePayrollRequest $request, $id)
    {
        try {
            $data = $request->validated();
            $generatepayroll = $this->generatePayrollService->update($id, $data);
            if (!$generatepayroll) {
                return $this->error('Generate Payroll not found', 404);
            }
            return $this->success('Generate Payroll updated successfully', $generatepayroll, 201);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function destroy($id)
    {
        try {
            $generatepayroll = $this->generatePayrollService->destroy($id);
            if (!$generatepayroll) {
                return $this->error('Generate Payroll not found', 404);
            }
            return $this->success('Generate Payroll deleted successfully, id : '.$generatepayroll->id, []);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function executeStoredProcedure(GeneratePayrollRequest $request)
    {
        try {
            $employeeIdss = $request->input('employee_idss');
            $periodeAbsen = $request->input('periode_absen');
            $periodePayroll = $request->input('periode_payroll');
            $years = $request->input('years');
            $dateNow = $request->input('datenow');
            // $dateNow = Carbon::now()->toDateString();
            $generatePayroll = GeneratePayroll::executeStoredProcedure($employeeIdss, $periodeAbsen, $periodePayroll, $years, $dateNow);
            return $this->success('Generate Payroll successfully', $generatePayroll, 201);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }
}
