<?php

namespace App\Http\Controllers\API\V1;

use App\Traits\ResponseAPI;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\ContractTypeRequest;
use App\Services\ContractType\ContractTypeServiceInterface;

class ContractTypeController extends Controller
{
    use ResponseAPI;

    private $contracttypeService;

    public function __construct(ContractTypeServiceInterface $contracttypeService)
    {
        $this->middleware('auth:api');
        $this->contracttypeService = $contracttypeService;
    }

    public function index(Request $request)
    {
        try {
            $perPage = $request->input('per_page', 10);
            $search = $request->input('search');
            $contracttypes = $this->contracttypeService->index($perPage, $search);
            return $this->success('Contract Types retrieved successfully', $contracttypes);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function store(ContractTypeRequest $request)
    {
        try {
            $data = $request->validated();
            $contracttype = $this->contracttypeService->store($data);
            return $this->success('Contract Type created successfully', $contracttype, 201);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function show($id)
    {
        try {
            $contracttype = $this->contracttypeService->show($id);
            if (!$contracttype) {
                return $this->error('Contract Type not found', 404);
            }
            return $this->success('Contract Type retrieved successfully', $contracttype);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function update(ContractTypeRequest $request, $id)
    {
        try {
            $data = $request->validated();
            $contracttype = $this->contracttypeService->update($id, $data);
            if (!$contracttype) {
                return $this->error('Contract Type not found', 404);
            }
            return $this->success('Contract Type updated successfully', $contracttype, 201);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function destroy($id)
    {
        try {
            $contracttype = $this->contracttypeService->destroy($id);
            if (!$contracttype) {
                return $this->error('Contract Type not found', 404);
            }
            return $this->success('Contract Type deleted successfully, id : '.$contracttype->id, []);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }
}
