<?php

namespace App\Http\Controllers\API\V1;

use App\Traits\ResponseAPI;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\SkillTypeRequest;
use App\Services\SkillType\SkillTypeServiceInterface;

class SkillTypeController extends Controller
{
    use ResponseAPI;

    private $skilltypeService;

    public function __construct(SkillTypeServiceInterface $skilltypeService)
    {
        $this->middleware('auth:api');
        $this->skilltypeService = $skilltypeService;
    }

    public function index(Request $request)
    {
        try {
            $perPage = $request->input('per_page', 10);
            $search = $request->input('search');
            $skilltypes = $this->skilltypeService->index($perPage, $search);
            return $this->success('Skill Types retrieved successfully', $skilltypes);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function store(SkillTypeRequest $request)
    {
        try {
            $data = $request->validated();
            $skilltype = $this->skilltypeService->store($data);
            return $this->success('Skill Type created successfully', $skilltype, 201);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function show($id)
    {
        try {
            $skilltype = $this->skilltypeService->show($id);
            if (!$skilltype) {
                return $this->error('Skill Type not found', 404);
            }
            return $this->success('Skill Type retrieved successfully', $skilltype);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function update(SkillTypeRequest $request, $id)
    {
        try {
            $data = $request->validated();
            $skilltype = $this->skilltypeService->update($id, $data);
            if (!$skilltype) {
                return $this->error('Skill Type not found', 404);
            }
            return $this->success('Skill Type updated successfully', $skilltype, 201);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function destroy($id)
    {
        try {
            $skilltype = $this->skilltypeService->destroy($id);
            if (!$skilltype) {
                return $this->error('Skill Type not found', 404);
            }
            return $this->success('Skill Type deleted successfully, id : '.$skilltype->id, []);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }
}
