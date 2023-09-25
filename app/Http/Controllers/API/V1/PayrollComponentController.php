<?php

namespace App\Http\Controllers\API\V1;

use App\Traits\ResponseAPI;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\PayrollComponentRequest;
use App\Services\PayrollComponent\PayrollComponentServiceInterface;

class PayrollComponentController extends Controller
{
    use ResponseAPI;

    private $payrollcomponentService;

    public function __construct(PayrollComponentServiceInterface $payrollcomponentService)
    {
        $this->middleware('auth:api');
        $this->payrollcomponentService = $payrollcomponentService;
    }

    public function index(Request $request)
    {
        try {
            $perPage = $request->input('per_page', 10);
            $search = $request->input('search');
            $payrollcomponents = $this->payrollcomponentService->index($perPage, $search);
            return $this->success('Payroll Components retrieved successfully', $payrollcomponents);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function store(PayrollComponentRequest $request)
    {
        try {
            $data = $request->validated();
            $payrollcomponent = $this->payrollcomponentService->store($data);
            return $this->success('Payroll Component created successfully', $payrollcomponent, 201);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function show($id)
    {
        try {
            $payrollcomponent = $this->payrollcomponentService->show($id);
            if (!$payrollcomponent) {
                return $this->error('Payroll Component not found', 404);
            }
            return $this->success('Payroll Component retrieved successfully', $payrollcomponent);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function update(PayrollComponentRequest $request, $id)
    {
        try {
            $data = $request->validated();
            $payrollcomponent = $this->payrollcomponentService->update($id, $data);
            if (!$payrollcomponent) {
                return $this->error('Payroll Component not found', 404);
            }
            return $this->success('Payroll Component updated successfully', $payrollcomponent, 201);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function destroy($id)
    {
        try {
            $payrollcomponent = $this->payrollcomponentService->destroy($id);
            if (!$payrollcomponent) {
                return $this->error('Payroll Component not found', 404);
            }
            return $this->success('Payroll Component deleted successfully, id : '.$payrollcomponent->id, []);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }
}
