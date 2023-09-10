<?php

namespace App\Http\Controllers\API\V1;

use App\Traits\ResponseAPI;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\RelationshipRequest;
use App\Services\Relationship\RelationshipServiceInterface;

class RelationshipController extends Controller
{
    use ResponseAPI;

    private $relationshipService;

    public function __construct(RelationshipServiceInterface $relationshipService)
    {
        $this->middleware('auth:api');
        $this->relationshipService = $relationshipService;
    }

    public function index(Request $request)
    {
        try {
            $perPage = $request->input('per_page', 10);
            $search = $request->input('search');
            $relationships = $this->relationshipService->index($perPage, $search);
            return $this->success('Relationships retrieved successfully', $relationships);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function store(RelationshipRequest $request)
    {
        try {
            $data = $request->validated();
            $relationship = $this->relationshipService->store($data);
            return $this->success('Relationship created successfully', $relationship, 201);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function show($id)
    {
        try {
            $relationship = $this->relationshipService->show($id);
            if (!$relationship) {
                return $this->error('Relationship not found', 404);
            }
            return $this->success('Relationship retrieved successfully', $relationship);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function update(RelationshipRequest $request, $id)
    {
        try {
            $data = $request->validated();
            $relationship = $this->relationshipService->update($id, $data);
            if (!$relationship) {
                return $this->error('Relationship not found', 404);
            }
            return $this->success('Relationship updated successfully', $relationship, 201);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function destroy($id)
    {
        try {
            $relationship = $this->relationshipService->destroy($id);
            if (!$relationship) {
                return $this->error('Relationship not found', 404);
            }
            return $this->success('Relationship deleted successfully, id : '.$relationship->id, []);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }
}
