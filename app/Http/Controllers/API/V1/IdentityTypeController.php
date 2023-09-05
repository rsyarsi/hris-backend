<?php

namespace App\Http\Controllers\API\V1;

use App\Traits\ResponseAPI;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\IdentityTypeRequest;
use App\Services\IdentityType\IdentityTypeServiceInterface;

class IdentityTypeController extends Controller
{
    use ResponseAPI;

    private $identitytypeService;

    public function __construct(IdentityTypeServiceInterface $identitytypeService)
    {
        $this->middleware('auth:api');
        $this->identitytypeService = $identitytypeService;
    }

    public function index(Request $request)
    {
        try {
            $perPage = $request->input('per_page', 10);
            $search = $request->input('search');
            $identitytypes = $this->identitytypeService->index($perPage, $search);
            return $this->success('Identity Type retrieved successfully', $identitytypes);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function store(IdentityTypeRequest $request)
    {
        try {
            $data = $request->validated();
            $identitytype = $this->identitytypeService->store($data);
            return $this->success('Identity Type created successfully', $identitytype, 201);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function show($id)
    {
        try {
            $identitytype = $this->identitytypeService->show($id);
            if (!$identitytype) {
                return $this->error('Identity Type not found', 404);
            }
            return $this->success('Identity Type retrieved successfully', $identitytype);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function update(IdentityTypeRequest $request, $id)
    {
        try {
            $data = $request->validated();
            $identitytype = $this->identitytypeService->update($id, $data);
            if (!$identitytype) {
                return $this->error('Identity Type not found', 404);
            }
            return $this->success('Identity Type updated successfully', $identitytype, 201);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function destroy($id)
    {
        try {
            $identitytype = $this->identitytypeService->destroy($id);
            if (!$identitytype) {
                return $this->error('Identity Type not found', 404);
            }
            return $this->success('Identity Type deleted successfully, id : '.$identitytype->id, []);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }
}
