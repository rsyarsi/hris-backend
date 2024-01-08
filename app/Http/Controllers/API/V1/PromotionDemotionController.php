<?php

namespace App\Http\Controllers\API\V1;

use App\Traits\ResponseAPI;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\PromotionDemotionRequest;
use App\Services\PromotionDemotion\PromotionDemotionServiceInterface;

class PromotionDemotionController extends Controller
{
    use ResponseAPI;

    private $promotionDemotionService;

    public function __construct(PromotionDemotionServiceInterface $promotionDemotionService)
    {
        $this->middleware('auth:api');
        $this->promotionDemotionService = $promotionDemotionService;
    }

    public function index(Request $request)
    {
        try {
            $perPage = $request->input('per_page', 10);
            $search = $request->input('search');
            $promotionDemotion = $this->promotionDemotionService->index($perPage, $search);
            return $this->success('Promotion Demotion retrieved successfully', $promotionDemotion);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function store(PromotionDemotionRequest $request)
    {
        try {
            $data = $request->validated();
            $promotionDemotion = $this->promotionDemotionService->store($data);
            return $this->success('Promotion Demotion created successfully', $promotionDemotion, 201);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function show($id)
    {
        try {
            $promotionDemotion = $this->promotionDemotionService->show($id);
            if (!$promotionDemotion) {
                return $this->error('Promotion Demotion not found', 404);
            }
            return $this->success('Promotion Demotion retrieved successfully', $promotionDemotion);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function update(PromotionDemotionRequest $request, $id)
    {
        try {
            $data = $request->validated();
            $promotionDemotion = $this->promotionDemotionService->update($id, $data);
            if (!$promotionDemotion) {
                return $this->error('Promotion Demotion not found', 404);
            }
            return $this->success('Promotion Demotion updated successfully', $promotionDemotion, 201);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function destroy($id)
    {
        try {
            $promotionDemotion = $this->promotionDemotionService->destroy($id);
            if (!$promotionDemotion) {
                return $this->error('Promotion Demotion not found', 404);
            }
            return $this->success('Promotion Demotion deleted successfully, id : '.$promotionDemotion->id, []);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function promotionDemotionEmployee(Request $request)
    {
        try {
            $perPage = $request->input('per_page', 10);
            $search = $request->input('search');
            $promotionDemotion = $this->promotionDemotionService->promotionDemotionEmployee($perPage, $search);
            return $this->success('Promotion Demotion employee retrieved successfully', $promotionDemotion);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }
}
