<?php

namespace App\Http\Controllers\API\V1;

use App\Traits\ResponseAPI;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\ReligionRequest;
use App\Services\Religion\ReligionServiceInterface;

class ReligionController extends Controller
{
    use ResponseAPI;

    private $religionService;

    public function __construct(ReligionServiceInterface $religionService)
    {
        $this->middleware('auth:api');
        $this->religionService = $religionService;
    }

    public function index(Request $request)
    {
        try {
            $perPage = $request->input('per_page', 10);
            $search = $request->input('search');
            $religions = $this->religionService->index($perPage, $search);
            return $this->success('Religions retrieved successfully', $religions);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function store(ReligionRequest $request)
    {
        try {
            $data = $request->validated();
            $religion = $this->religionService->store($data);
            return $this->success('Religion created successfully', $religion, 201);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function show($id)
    {
        try {
            $religion = $this->religionService->show($id);
            if (!$religion) {
                return $this->error('Religion not found', 404);
            }
            return $this->success('Religion retrieved successfully', $religion);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function update(ReligionRequest $request, $id)
    {
        try {
            $data = $request->validated();
            $religion = $this->religionService->update($id, $data);
            if (!$religion) {
                return $this->error('Religion not found', 404);
            }
            return $this->success('Religion updated successfully', $religion, 201);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function destroy($id)
    {
        try {
            $religion = $this->religionService->destroy($id);
            if (!$religion) {
                return $this->error('Religion not found', 404);
            }
            return $this->success('Religion deleted successfully, id : '.$religion->id, []);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }
}
