<?php

namespace App\Http\Controllers\API\V1;

use App\Traits\ResponseAPI;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\CandidateAccountRequest;
use App\Services\CandidateAccount\CandidateAccountServiceInterface;

class CandidateAccountAccountController extends Controller
{
    use ResponseAPI;

    private $candidateAccountService;

    public function __construct(CandidateAccountServiceInterface $candidateAccountService)
    {
        $this->middleware('auth:api');
        $this->candidateAccountService = $candidateAccountService;
    }

    public function index(Request $request)
    {
        try {
            $perPage = $request->input('per_page', 10);
            $search = $request->input('search');
            $active = $request->input('active');
            $candidateAccounts = $this->candidateAccountService->index($perPage, $search, $active);
            return $this->success('Candidate Accounts retrieved successfully', $candidateAccounts);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function store(CandidateAccountRequest $request)
    {
        try {
            $data = $request->validated();
            $candidateaccount = $this->candidateAccountService->store($data);
            return $this->success('Candidate Account created successfully', $candidateaccount, 201);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function show($id)
    {
        try {
            $candidateaccount = $this->candidateAccountService->show($id);
            if (!$candidateaccount) {
                return $this->error('Candidate Account not found', 404);
            }
            return $this->success('Candidate Account retrieved successfully', $candidateaccount);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function update(CandidateAccountRequest $request, $id)
    {
        try {
            $data = $request->validated();
            $candidateaccount = $this->candidateAccountService->update($id, $data);
            if (!$candidateaccount) {
                return $this->error('Candidate Account not found', 404);
            }
            return $this->success('Candidate Account updated successfully', $candidateaccount, 201);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function destroy($id)
    {
        try {
            $candidateaccount = $this->candidateAccountService->destroy($id);
            if (!$candidateaccount) {
                return $this->error('Candidate Account not found', 404);
            }
            return $this->success('Candidate Account deleted successfully, id : '.$candidateaccount->id, []);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }
}
