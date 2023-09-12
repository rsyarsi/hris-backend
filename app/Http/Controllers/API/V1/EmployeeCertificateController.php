<?php

namespace App\Http\Controllers\API\V1;

use App\Traits\ResponseAPI;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\EmployeeCertificateRequest;
use App\Services\EmployeeCertificate\EmployeeCertificateServiceInterface;

class EmployeeCertificateController extends Controller
{
    use ResponseAPI;

    private $employeeCertificateService;

    public function __construct(EmployeeCertificateServiceInterface $employeeCertificateService)
    {
        $this->middleware('auth:api');
        $this->employeeCertificateService = $employeeCertificateService;
    }

    public function index(Request $request)
    {
        try {
            $perPage = $request->input('per_page', 10);
            $search = $request->input('search');
            $employeecertificates = $this->employeeCertificateService->index($perPage, $search);
            return $this->success('Employee Certificates retrieved successfully', $employeecertificates);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function store(EmployeeCertificateRequest $request)
    {
        try {
            $data = $request->validated();
            $employeecertificate = $this->employeeCertificateService->store($data);
            return $this->success('Employee Certificate created successfully', $employeecertificate, 201);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function show($id)
    {
        try {
            $employeecertificate = $this->employeeCertificateService->show($id);
            if (!$employeecertificate) {
                return $this->error('Employee Certificate not found', 404);
            }
            return $this->success('Employee Certificate retrieved successfully', $employeecertificate);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function update(EmployeeCertificateRequest $request, $id)
    {
        try {
            $data = $request->validated();
            $employeecertificate = $this->employeeCertificateService->update($id, $data);
            if (!$employeecertificate) {
                return $this->error('Employee Certificate not found', 404);
            }
            return $this->success('Employee Certificate updated successfully', $employeecertificate, 201);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function destroy($id)
    {
        try {
            $employeecertificate = $this->employeeCertificateService->destroy($id);
            if (!$employeecertificate) {
                return $this->error('Employee Certificate not found', 404);
            }
            return $this->success('Employee Certificate deleted successfully, id : '.$employeecertificate->id, []);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }
}
