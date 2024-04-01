<?php

namespace App\Http\Controllers\API\V1;

use App\Traits\ResponseAPI;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\HumanResourcesTestRequest;
use App\Services\HumanResourcesTest\HumanResourcesTestServiceInterface;

class HumanResourcesTestController extends Controller
{
    use ResponseAPI;

    private $humanResourcesTestService;

    public function __construct(HumanResourcesTestServiceInterface $humanResourcesTestService)
    {
        $this->middleware('api_or_candidate_auth');
        $this->humanResourcesTestService = $humanResourcesTestService;
    }

    public function index(Request $request)
    {
        try {
            $perPage = $request->input('per_page', 10);
            $search = $request->input('search');
            $humanResourcesTests = $this->humanResourcesTestService->index($perPage, $search);
            return $this->success('Human Resources Test retrieved successfully', $humanResourcesTests);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function store(HumanResourcesTestRequest $request)
    {
        try {
            $data = $request->validated();
            $humanResourcesTest = $this->humanResourcesTestService->store($data);
            return $this->success('Human Resources Test created successfully', $humanResourcesTest, 201);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function show($id)
    {
        try {
            $humanResourcesTest = $this->humanResourcesTestService->show($id);
            if (!$humanResourcesTest) {
                return $this->error('Human Resources Test not found', 404);
            }
            return $this->success('Human Resources Test retrieved successfully', $humanResourcesTest);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function update(HumanResourcesTestRequest $request, $id)
    {
        try {
            $data = $request->validated();
            $humanResourcesTest = $this->humanResourcesTestService->update($id, $data);
            if (!$humanResourcesTest) {
                return $this->error('Human Resources Test not found', 404);
            }
            return $this->success('Human Resources Test updated successfully', $humanResourcesTest, 201);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function destroy($id)
    {
        try {
            $humanResourcesTest = $this->humanResourcesTestService->destroy($id);
            if (!$humanResourcesTest) {
                return $this->error('Human Resources Test not found', 404);
            }
            return $this->success('Human Resources Test deleted successfully, id : ' . $humanResourcesTest->id, []);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }
}
