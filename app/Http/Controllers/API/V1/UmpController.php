<?php

namespace App\Http\Controllers\API\V1;

use App\Traits\ResponseAPI;
use Illuminate\Http\Request;
use App\Http\Requests\UmpRequest;
use App\Http\Controllers\Controller;
use App\Services\Ump\UmpServiceInterface;

class UmpController extends Controller
{
    use ResponseAPI;

    private $umpService;

    public function __construct(UmpServiceInterface $umpService)
    {
        $this->middleware('auth:api');
        $this->umpService = $umpService;
    }

    public function index(Request $request)
    {
        try {
            $perPage = $request->input('per_page', 10);
            $search = $request->input('search');
            $umps = $this->umpService->index($perPage, $search);
            return $this->success('Umps retrieved successfully', $umps);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function store(UmpRequest $request)
    {
        try {
            $data = $request->validated();
            $ump = $this->umpService->store($data);
            return $this->success('Ump created successfully', $ump, 201);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function show($id)
    {
        try {
            $ump = $this->umpService->show($id);
            if (!$ump) {
                return $this->error('Ump not found', 404);
            }
            return $this->success('Ump retrieved successfully', $ump);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function update(UmpRequest $request, $id)
    {
        try {
            $data = $request->validated();
            $ump = $this->umpService->update($id, $data);
            if (!$ump) {
                return $this->error('Ump not found', 404);
            }
            return $this->success('Ump updated successfully', $ump, 201);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function destroy($id)
    {
        try {
            $ump = $this->umpService->destroy($id);
            if (!$ump) {
                return $this->error('Ump not found', 404);
            }
            return $this->success('Ump deleted successfully, id : '.$ump->id, []);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }
}
