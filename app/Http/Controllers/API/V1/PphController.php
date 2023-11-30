<?php

namespace App\Http\Controllers\API\V1;

use App\Imports\PphImport;
use App\Traits\ResponseAPI;
use Illuminate\Http\Request;
use App\Http\Requests\{PphRequest, ImportPphRequest};
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use App\Services\Pph\PphServiceInterface;

class PphController extends Controller
{
    use ResponseAPI;

    private $pphService;

    public function __construct(PphServiceInterface $pphService)
    {
        $this->middleware('auth:api');
        $this->pphService = $pphService;
    }

    public function index(Request $request)
    {
        try {
            $perPage = $request->input('per_page', 10);
            $search = $request->input('search');
            $pph = $this->pphService->index($perPage, $search);
            return $this->success('Pph retrieved successfully', $pph);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function store(PphRequest $request)
    {
        try {
            $data = $request->validated();
            $pph = $this->pphService->store($data);
            return $this->success('Pph created successfully', $pph, 201);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function show($id)
    {
        try {
            $pph = $this->pphService->show($id);
            if (!$pph) {
                return $this->error('Pph not found', 404);
            }
            return $this->success('Pph retrieved successfully', $pph);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function update(PphRequest $request, $id)
    {
        try {
            $data = $request->validated();
            $pph = $this->pphService->update($id, $data);
            if (!$pph) {
                return $this->error('Pph not found', 404);
            }
            return $this->success('Pph updated successfully', $pph, 201);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function destroy($id)
    {
        try {
            $pph = $this->pphService->destroy($id);
            if (!$pph) {
                return $this->error('Pph not found', 404);
            }
            return $this->success('Pph deleted successfully, id : '.$pph->id, []);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function pphEmployee(Request $request)
    {
        try {
            $perPage = $request->input('per_page', 10);
            $search = $request->input('search');
            $pph = $this->pphService->pphEmployee($perPage, $search);
            return $this->success('Pph employee retrieved successfully', $pph);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function importPph(ImportPphRequest $request)
    {
        try {
            Excel::import(new PphImport, request()->file('file'));
            return $this->success('Pph imported successfully', [], 201);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }
}
