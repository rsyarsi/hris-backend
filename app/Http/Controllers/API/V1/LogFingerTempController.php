<?php

namespace App\Http\Controllers\API\V1;

use App\Traits\ResponseAPI;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\LogFingerTempRequest;
use App\Services\LogFingerTemp\LogFingerTempServiceInterface;

class LogFingerTempTempController extends Controller
{
    use ResponseAPI;

    private $logFingerTempService;

    public function __construct(LogFingerTempServiceInterface $logFingerTempService)
    {
        $this->middleware('auth:api');
        $this->logFingerTempService = $logFingerTempService;
    }

    public function index(Request $request)
    {
        try {
            $perPage = $request->input('per_page', 10);
            $search = $request->input('search');
            $logfingertemps = $this->logFingerTempService->index($perPage, $search);
            return $this->success('Log Fingers retrieved successfully', $logfingertemps);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function store(LogFingerTempRequest $request)
    {
        try {
            $data = $request->validated();
            $logfingertemp = $this->logFingerTempService->store($data);
            return $this->success('Log Finger created successfully', $logfingertemp, 201);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function show($id)
    {
        try {
            $logfingertemp = $this->logFingerTempService->show($id);
            if (!$logfingertemp) {
                return $this->error('Log Finger not found', 404);
            }
            return $this->success('Log Finger retrieved successfully', $logfingertemp);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function update(LogFingerTempRequest $request, $id)
    {
        try {
            $data = $request->validated();
            $logfingertemp = $this->logFingerTempService->update($id, $data);
            if (!$logfingertemp) {
                return $this->error('Log Finger not found', 404);
            }
            return $this->success('Log Finger updated successfully', $logfingertemp, 201);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function destroy($id)
    {
        try {
            $logfingertemp = $this->logFingerTempService->destroy($id);
            if (!$logfingertemp) {
                return $this->error('Log Finger not found', 404);
            }
            return $this->success('Log Finger deleted successfully, id : '.$logfingertemp->id, []);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    // public function importLogFingerTemp(ImportLogFingerTempRequest $request)
    // {
    //     try {
    //         Excel::import(new LogFingerTempImport, request()->file('file'));
    //         return $this->success('Log Finger imported successfully', [], 201);
    //     } catch (\Exception $e) {
    //         return $this->error($e->getMessage(), $e->getCode());
    //     }
    // }

    // public function logFingerTempUser(Request $request)
    // {
    //     try {
    //         $perPage = $request->input('per_page', 10);
    //         $search = $request->input('search');
    //         $logfingertemps = $this->logFingerTempService->logFingerTempUser($perPage, $search);
    //         return $this->success('Log Fingers user retrieved successfully', $logfingertemps);
    //     } catch (\Exception $e) {
    //         return $this->error($e->getMessage(), $e->getCode());
    //     }
    // }
}
