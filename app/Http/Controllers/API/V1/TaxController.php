<?php

namespace App\Http\Controllers\API\V1;

use App\Traits\ResponseAPI;
use App\Http\Requests\TaxRequest;
use App\Http\Controllers\Controller;
use App\Services\Tax\TaxServiceInterface;

class TaxController extends Controller
{
    use ResponseAPI;

    private $taxService;

    public function __construct(TaxServiceInterface $taxService)
    {
        $this->middleware('auth:api');
        $this->taxService = $taxService;
    }

    public function index()
    {
        try {
            $taxs = $this->taxService->index();
            return $this->success('Taxs retrieved successfully', $taxs);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function store(TaxRequest $request)
    {
        try {
            $data = $request->validated();
            $tax = $this->taxService->store($data);
            return $this->success('Tax created successfully', $tax, 201);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function show($id)
    {
        try {
            $tax = $this->taxService->show($id);
            if (!$tax) {
                return $this->error('Tax not found', 404);
            }
            return $this->success('Tax retrieved successfully', $tax);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function update(TaxRequest $request, $id)
    {
        try {
            $data = $request->validated();
            $tax = $this->taxService->update($id, $data);
            if (!$tax) {
                return $this->error('Tax not found', 404);
            }
            return $this->success('Tax updated successfully', $tax, 201);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function destroy($id)
    {
        try {
            $tax = $this->taxService->destroy($id);
            if (!$tax) {
                return $this->error('Tax not found', 404);
            }
            return $this->success('Tax deleted successfully, id : '.$tax->id, []);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }
}
