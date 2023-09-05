<?php

namespace App\Http\Controllers\API\V1;

use App\Traits\ResponseAPI;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\LegalityTypeRequest;
use App\Services\LegalityType\LegalityTypeServiceInterface;

class LegalityTypeController extends Controller
{
    use ResponseAPI;

    private $legalitytypeService;

    public function __construct(LegalityTypeServiceInterface $legalitytypeService)
    {
        $this->middleware('auth:api');
        $this->legalitytypeService = $legalitytypeService;
    }

    public function index(Request $request)
    {
        try {
            $perPage = $request->input('per_page', 10);
            $search = $request->input('search');
            $legalitytypes = $this->legalitytypeService->index($perPage, $search);
            return $this->success('Legality Types retrieved successfully', $legalitytypes);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function store(LegalityTypeRequest $request)
    {
        try {
            $data = $request->validated();
            $legalitytype = $this->legalitytypeService->store($data);
            return $this->success('Legality Type created successfully', $legalitytype, 201);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function show($id)
    {
        try {
            $legalitytype = $this->legalitytypeService->show($id);
            if (!$legalitytype) {
                return $this->error('Legality Type not found', 404);
            }
            return $this->success('Legality Type retrieved successfully', $legalitytype);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function update(LegalityTypeRequest $request, $id)
    {
        try {
            $data = $request->validated();
            $legalitytype = $this->legalitytypeService->update($id, $data);
            if (!$legalitytype) {
                return $this->error('Legality Type not found', 404);
            }
            return $this->success('Legality Type updated successfully', $legalitytype, 201);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function destroy($id)
    {
        try {
            $legalitytype = $this->legalitytypeService->destroy($id);
            if (!$legalitytype) {
                return $this->error('Legality Type not found', 404);
            }
            return $this->success('Legality Type deleted successfully, id : '.$legalitytype->id, []);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }
}
