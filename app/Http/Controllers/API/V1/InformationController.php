<?php

namespace App\Http\Controllers\API\V1;

use App\Traits\ResponseAPI;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\InformationRequest;
use App\Services\Information\InformationServiceInterface;

class InformationController extends Controller
{
    use ResponseAPI;

    private $informationService;

    public function __construct(InformationServiceInterface $informationService)
    {
        $this->middleware('auth:api');
        $this->informationService = $informationService;
    }

    public function index(Request $request)
    {
        try {
            $perPage = $request->input('per_page', 10);
            $search = $request->input('search');
            $informations = $this->informationService->index($perPage, $search);
            return $this->success('Informations retrieved successfully', $informations);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function store(InformationRequest $request)
    {
        try {
            $data = $request->validated();
            $information = $this->informationService->store($data);
            return $this->success('Information created successfully', $information, 201);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function show($id)
    {
        try {
            $information = $this->informationService->show($id);
            if (!$information) {
                return $this->error('Information not found', 404);
            }
            return $this->success('Information retrieved successfully', $information);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function update(InformationRequest $request, $id)
    {
        try {
            $data = $request->validated();
            $information = $this->informationService->update($id, $data);
            if (!$information) {
                return $this->error('Information not found', 404);
            }
            return $this->success('Information updated successfully', $information, 201);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function destroy($id)
    {
        try {
            $information = $this->informationService->destroy($id);
            if (!$information) {
                return $this->error('Information not found', 404);
            }
            return $this->success('Information deleted successfully, id : '.$information->id, []);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }
}
