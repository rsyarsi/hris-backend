<?php

namespace App\Http\Controllers\API\V1;

use App\Traits\ResponseAPI;
use Illuminate\Http\Request;
use App\Imports\PengembalianImport;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Requests\PengembalianRequest;
use App\Http\Requests\ImportPengembalianRequest;
use App\Services\Pengembalian\PengembalianServiceInterface;

class PengembalianController extends Controller
{
    use ResponseAPI;

    private $pengembalianService;

    public function __construct(PengembalianServiceInterface $pengembalianService)
    {
        $this->middleware('auth:api');
        $this->pengembalianService = $pengembalianService;
    }

    public function index(Request $request)
    {
        try {
            $perPage = $request->input('per_page', 10);
            $search = $request->input('search');
            $pengembalian = $this->pengembalianService->index($perPage, $search);
            return $this->success('Pengembalian retrieved successfully', $pengembalian);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function store(PengembalianRequest $request)
    {
        try {
            $data = $request->validated();
            $pengembalian = $this->pengembalianService->store($data);
            return $this->success('Pengembalian created successfully', $pengembalian, 201);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function show($id)
    {
        try {
            $pengembalian = $this->pengembalianService->show($id);
            if (!$pengembalian) {
                return $this->error('Pengembalian not found', 404);
            }
            return $this->success('Pengembalian retrieved successfully', $pengembalian);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function update(PengembalianRequest $request, $id)
    {
        try {
            $data = $request->validated();
            $pengembalian = $this->pengembalianService->update($id, $data);
            if (!$pengembalian) {
                return $this->error('Pengembalian not found', 404);
            }
            return $this->success('Pengembalian updated successfully', $pengembalian, 201);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function destroy($id)
    {
        try {
            $pengembalian = $this->pengembalianService->destroy($id);
            if (!$pengembalian) {
                return $this->error('Pengembalian not found', 404);
            }
            return $this->success('Pengembalian deleted successfully, id : '.$pengembalian->id, []);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function pengembalianEmployee(Request $request)
    {
        try {
            $perPage = $request->input('per_page', 10);
            $search = $request->input('search');
            $pengembalian = $this->pengembalianService->pengembalianEmployee($perPage, $search);
            return $this->success('Pengembalian employee retrieved successfully', $pengembalian);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function importPengembalian(ImportPengembalianRequest $request)
    {
        try {
            $import = Excel::import(new PengembalianImport, request()->file('file'));
            return $this->success('Pengembalian imported successfully', $import, 201);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }
}
