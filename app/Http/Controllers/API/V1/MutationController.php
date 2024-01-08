<?php

namespace App\Http\Controllers\API\V1;

use App\Traits\ResponseAPI;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\MutationRequest;
use App\Services\Mutation\MutationServiceInterface;

class MutationController extends Controller
{
    use ResponseAPI;

    private $mutationService;

    public function __construct(MutationServiceInterface $mutationService)
    {
        $this->middleware('auth:api');
        $this->mutationService = $mutationService;
    }

    public function index(Request $request)
    {
        try {
            $perPage = $request->input('per_page', 10);
            $search = $request->input('search');
            $mutation = $this->mutationService->index($perPage, $search);
            return $this->success('Mutation retrieved successfully', $mutation);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function store(MutationRequest $request)
    {
        try {
            $data = $request->validated();
            $mutation = $this->mutationService->store($data);
            return $this->success('Mutation created successfully', $mutation, 201);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function show($id)
    {
        try {
            $mutation = $this->mutationService->show($id);
            if (!$mutation) {
                return $this->error('Mutation not found', 404);
            }
            return $this->success('Mutation retrieved successfully', $mutation);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function update(MutationRequest $request, $id)
    {
        try {
            $data = $request->validated();
            $mutation = $this->mutationService->update($id, $data);
            if (!$mutation) {
                return $this->error('Mutation not found', 404);
            }
            return $this->success('Mutation updated successfully', $mutation, 201);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function destroy($id)
    {
        try {
            $mutation = $this->mutationService->destroy($id);
            if (!$mutation) {
                return $this->error('Mutation not found', 404);
            }
            return $this->success('Mutation deleted successfully, id : '.$mutation->id, []);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function mutationEmployee(Request $request)
    {
        try {
            $perPage = $request->input('per_page', 10);
            $search = $request->input('search');
            $mutation = $this->mutationService->mutationEmployee($perPage, $search);
            return $this->success('Mutation employee retrieved successfully', $mutation);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }
}
