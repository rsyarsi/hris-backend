<?php

namespace App\Http\Controllers\API\V1;

use App\Traits\ResponseAPI;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\VillageRequest;
use App\Services\Village\VillageServiceInterface;

class VillageController extends Controller
{
    use ResponseAPI;

    private $villageService;

    public function __construct(VillageServiceInterface $villageService)
    {
        $this->middleware('auth:api');
        $this->villageService = $villageService;
    }

    public function index(Request $request)
    {
        try {
            $perPage = $request->input('per_page', 10);
            $search = $request->input('search');
            $villages = $this->villageService->index($perPage, $search);
            return $this->success('Villages retrieved successfully', $villages);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function store(VillageRequest $request)
    {
        try {
            $data = $request->validated();
            $village = $this->villageService->store($data);
            return $this->success('Village created successfully', $village, 201);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function show($id)
    {
        try {
            $village = $this->villageService->show($id);
            if (!$village) {
                return $this->error('Village not found', 404);
            }
            return $this->success('Village retrieved successfully', $village);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function update(VillageRequest $request, $id)
    {
        try {
            $data = $request->validated();
            $village = $this->villageService->update($id, $data);
            if (!$village) {
                return $this->error('Village not found', 404);
            }
            return $this->success('Village updated successfully', $village, 201);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function destroy($id)
    {
        try {
            $village = $this->villageService->destroy($id);
            if (!$village) {
                return $this->error('Village not found', 404);
            }
            return $this->success('Village deleted successfully, id : '.$village->id, []);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }
}
