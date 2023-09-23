<?php

namespace App\Http\Controllers\API\V1;

use App\Traits\ResponseAPI;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\OvertimeRequest;
use App\Services\Overtime\OvertimeServiceInterface;

class OvertimeController extends Controller
{
    use ResponseAPI;

    private $overtimeService;

    public function __construct(OvertimeServiceInterface $overtimeService)
    {
        $this->middleware('auth:api');
        $this->overtimeService = $overtimeService;
    }

    public function index(Request $request)
    {
        try {
            $perPage = $request->input('per_page', 10);
            $search = $request->input('search');
            $overtimes = $this->overtimeService->index($perPage, $search);
            return $this->success('Overtime retrieved successfully', $overtimes);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function store(OvertimeRequest $request)
    {
        try {
            $data = $request->validated();
            $overtime = $this->overtimeService->store($data);
            return $this->success('Overtime created successfully', $overtime, 201);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function show($id)
    {
        try {
            $overtime = $this->overtimeService->show($id);
            if (!$overtime) {
                return $this->error('Overtime not found', 404);
            }
            return $this->success('Overtime retrieved successfully', $overtime);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function update(OvertimeRequest $request, $id)
    {
        try {
            $data = $request->validated();
            $overtime = $this->overtimeService->update($id, $data);
            if (!$overtime) {
                return $this->error('Overtime not found', 404);
            }
            return $this->success('Overtime updated successfully', $overtime, 201);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function destroy($id)
    {
        try {
            $overtime = $this->overtimeService->destroy($id);
            if (!$overtime) {
                return $this->error('Overtime not found', 404);
            }
            return $this->success('Overtime deleted successfully, id : '.$overtime->id, []);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }
}
