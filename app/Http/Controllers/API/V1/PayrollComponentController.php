<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

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
            return $this->success('Contract Types retrieved successfully', $payrollcomponents);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function store(PayrollComponentRequest $request)
    {
        try {
            $data = $request->validated();
            $payrollcomponent = $this->payrollcomponentService->store($data);
            return $this->success('Contract Type created successfully', $payrollcomponent, 201);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function show($id)
    {
        try {
            $payrollcomponent = $this->payrollcomponentService->show($id);
            if (!$payrollcomponent) {
                return $this->error('Contract Type not found', 404);
            }
            return $this->success('Contract Type retrieved successfully', $payrollcomponent);
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
                return $this->error('Contract Type not found', 404);
            }
            return $this->success('Contract Type updated successfully', $payrollcomponent, 201);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function destroy($id)
    {
        try {
            $payrollcomponent = $this->payrollcomponentService->destroy($id);
            if (!$payrollcomponent) {
                return $this->error('Contract Type not found', 404);
            }
            return $this->success('Contract Type deleted successfully, id : '.$payrollcomponent->id, []);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }
}
