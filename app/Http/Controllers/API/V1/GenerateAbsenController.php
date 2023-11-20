<?php

namespace App\Http\Controllers\API\V1;

use App\Traits\ResponseAPI;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\GenerateAbsenRequest;
use App\Services\GenerateAbsen\GenerateAbsenServiceInterface;

class GenerateAbsenController extends Controller
{
    use ResponseAPI;

    private $generateAbsenService;

    public function __construct(GenerateAbsenServiceInterface $generateAbsenService)
    {
        $this->middleware('auth:api');
        $this->generateAbsenService = $generateAbsenService;
    }

    public function index(Request $request)
    {
        try {
            $perPage = $request->input('per_page', 10);
            $search = $request->input('search');
            $period_1 = $request->input('period_1');
            $period_2 = $request->input('period_2');
            $unit = $request->input('unit');
            $generateabsens = $this->generateAbsenService->index($perPage, $search, $period_1, $period_2, $unit);
            return $this->success('Generate Absens retrieved successfully', $generateabsens);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function store(GenerateAbsenRequest $request)
    {
        try {
            $data = $request->validated();
            $generateabsen = $this->generateAbsenService->store($data);
            return $this->success('Generate Absen created successfully', $generateabsen, 201);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function show($id)
    {
        try {
            $generateabsen = $this->generateAbsenService->show($id);
            if (!$generateabsen) {
                return $this->error('Generate Absen not found', 404);
            }
            return $this->success('Generate Absen retrieved successfully', $generateabsen);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function update(GenerateAbsenRequest $request, $id)
    {
        try {
            $data = $request->validated();
            $generateabsen = $this->generateAbsenService->update($id, $data);
            if (!$generateabsen) {
                return $this->error('Generate Absen not found', 404);
            }
            return $this->success('Generate Absen updated successfully', $generateabsen, 201);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function destroy($id)
    {
        try {
            $generateabsen = $this->generateAbsenService->destroy($id);
            if (!$generateabsen) {
                return $this->error('Generate Absen not found', 404);
            }
            return $this->success('Generate Absen deleted successfully, id : '.$generateabsen->id, []);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }
}
