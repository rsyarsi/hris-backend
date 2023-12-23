<?php

namespace App\Http\Controllers\API\V1;

use App\Traits\ResponseAPI;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;
use App\Services\GeneratePayroll\GeneratePayrollServiceInterface;
use App\Http\Requests\{ExecuteGeneratePayrollRequest, GeneratePayrollRequest};

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
            $unit = $request->input('unit');
            $period = $request->input('period');
            $generatepayrolls = $this->generatePayrollService->index($perPage, $search, $unit, $period);
            return $this->success('Generate Payrolls retrieved successfully', $generatepayrolls);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function indexMobile(Request $request)
    {
        $employeeId = $request->input('employee_id');
        $generatepayrolls = $this->generatePayrollService->indexMobile($employeeId);
        return $this->success('Generate payrolls mobile retrieved successfully', $generatepayrolls);
        try {
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

    public function executeStoredProcedure(ExecuteGeneratePayrollRequest $request)
    {
        try {
            $periodeAbsen = $request->input('periode_absen');
            $periodePayroll = $request->input('periode_payroll');
            $generatePayroll = $this->generatePayrollService->executeStoredProcedure($periodeAbsen, $periodePayroll);
            return $this->success('Generate Payroll successfully', $generatePayroll, 201);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function generatePayrollEmployee(Request $request)
    {
        try {
            $perPage = $request->input('per_page', 10);
            $search = $request->input('search');
            $employeeId = $request->input('employee_id');
            $generatepayrolls = $this->generatePayrollService->generatePayrollEmployee($perPage, $search, $employeeId);
            return $this->success('Generate Payrolls employee retrieved successfully', $generatepayrolls);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function sendSlipGaji($id)
    {
        try {
            $generatepayrolls = $this->generatePayrollService->sendSlipGaji($id);
            return $this->success('Slip Gaji send successfully!', [
                'file_name' => $generatepayrolls['file_name'],
                'file_path' => $generatepayrolls['file_path'],
                'file_url' => $generatepayrolls['file_url'],
                'email_address' => $generatepayrolls['email_address'],
            ]);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function printSlipGaji($id)
    {
        try {
            $generatepayrolls = $this->generatePayrollService->show($id);

            // // Get the file content from AWS S3
            // $fileContent = Storage::disk('s3')->get($generatepayrolls['file_path']);

            // // Create a response with the file content and appropriate headers
            // $response = Response::make($fileContent, 200);

            // // Set the content type based on your file type (e.g., application/pdf)
            // $response->header('Content-Type', 'application/pdf');

            // // Set the content disposition to force download
            // $response->header('Content-Disposition', 'attachment; filename="' . $generatepayrolls['file_name'] . '"');

            // return $response;
            return $this->success('Slip Gaji send successfully!', [
                'file_name' => $generatepayrolls['file_name'],
                'file_path' => $generatepayrolls['file_path'],
                'file_url' => $generatepayrolls['file_url'],
            ]);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function sendSlipGajiPeriod(Request $request)
    {
        try {
            $period = $request->input('period');
            $generatepayrolls = $this->generatePayrollService->sendSlipGajiPeriod($period);
            return $this->success('Slip Gaji Send successfully!', $generatepayrolls);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }
}
