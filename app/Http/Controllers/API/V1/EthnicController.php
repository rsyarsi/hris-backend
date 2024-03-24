<?php

namespace App\Http\Controllers\API\V1;

use App\Traits\ResponseAPI;
use Illuminate\Http\Request;
use App\Imports\EthnicImport;
use App\Http\Controllers\Controller;
use App\Http\Requests\EthnicRequest;
use Maatwebsite\Excel\Facades\Excel;
use App\Services\Ethnic\EthnicServiceInterface;

class EthnicController extends Controller
{
    use ResponseAPI;

    private $ethnicService;

    public function __construct(EthnicServiceInterface $ethnicService)
    {
        $this->middleware('auth:api');
        $this->ethnicService = $ethnicService;
    }

    public function index(Request $request)
    {
        try {
            $perPage = $request->input('per_page', 10);
            $search = $request->input('search');
            $ethnics = $this->ethnicService->index($perPage, $search);
            return $this->success('Ethnics retrieved successfully', $ethnics);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function store(EthnicRequest $request)
    {
        try {
            $data = $request->validated();
            $ethnic = $this->ethnicService->store($data);
            return $this->success('Ethnic created successfully', $ethnic, 201);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function show($id)
    {
        try {
            $ethnic = $this->ethnicService->show($id);
            if (!$ethnic) {
                return $this->error('Ethnic not found', 404);
            }
            return $this->success('Ethnic retrieved successfully', $ethnic);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function update(EthnicRequest $request, $id)
    {
        try {
            $data = $request->validated();
            $ethnic = $this->ethnicService->update($id, $data);
            if (!$ethnic) {
                return $this->error('Ethnic not found', 404);
            }
            return $this->success('Ethnic updated successfully', $ethnic, 201);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function destroy($id)
    {
        try {
            $ethnic = $this->ethnicService->destroy($id);
            if (!$ethnic) {
                return $this->error('Ethnic not found', 404);
            }
            return $this->success('Ethnic deleted successfully, id : '.$ethnic->id, []);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function import()
    {
        try {
            Excel::import(new EthnicImport, request()->file('file'));
            return $this->success('Ethnics imported successfully', [], 201);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }
}
