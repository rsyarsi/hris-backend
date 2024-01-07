<?php

namespace App\Http\Controllers\API\V1;

use App\Traits\ResponseAPI;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\SuratPeringatanRequest;
use App\Services\SuratPeringatan\SuratPeringatanServiceInterface;

class SuratPeringatanController extends Controller
{
    use ResponseAPI;

    private $suratPeringatanService;

    public function __construct(SuratPeringatanServiceInterface $suratPeringatanService)
    {
        $this->middleware('auth:api');
        $this->suratPeringatanService = $suratPeringatanService;
    }

    public function index(Request $request)
    {
        try {
            $perPage = $request->input('per_page', 10);
            $search = $request->input('search');
            $employeeId = $request->input('employee_id');
            $suratPeringatans = $this->suratPeringatanService->index($perPage, $search, $employeeId);
            return $this->success('Surat Peringatan retrieved successfully', $suratPeringatans);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function store(SuratPeringatanRequest $request)
    {
        try {
            $data = $request->validated();
            $suratPeringatan = $this->suratPeringatanService->store($data);
            return $this->success('Surat Peringatan created successfully', $suratPeringatan, 201);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function show($id)
    {
        try {
            $suratPeringatan = $this->suratPeringatanService->show($id);
            if (!$suratPeringatan) {
                return $this->error('Surat Peringatan not found', 404);
            }
            return $this->success('Surat Peringatan retrieved successfully', $suratPeringatan);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function update(SuratPeringatanRequest $request, $id)
    {
        try {
            $data = $request->validated();
            $suratPeringatan = $this->suratPeringatanService->update($id, $data);
            if (!$suratPeringatan) {
                return $this->error('Surat Peringatan not found', 404);
            }
            return $this->success('Surat Peringatan updated successfully', $suratPeringatan, 201);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function destroy($id)
    {
        try {
            $suratPeringatan = $this->suratPeringatanService->destroy($id);
            if (!$suratPeringatan) {
                return $this->error('Surat Peringatan not found', 404);
            }
            return $this->success('Surat Peringatan deleted successfully, id : '.$suratPeringatan->id, []);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }
}
