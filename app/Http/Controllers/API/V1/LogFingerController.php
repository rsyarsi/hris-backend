<?php

namespace App\Http\Controllers\API\V1;

use App\Traits\ResponseAPI;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\LogFingerRequest;
use App\Services\LogFinger\LogFingerServiceInterface;

class LogFingerController extends Controller
{
    use ResponseAPI;

    private $logfingerService;

    public function __construct(LogFingerServiceInterface $logfingerService)
    {
        $this->middleware('auth:api');
        $this->logfingerService = $logfingerService;
    }

    public function index(Request $request)
    {
        try {
            $perPage = $request->input('per_page', 10);
            $search = $request->input('search');
            $logfingers = $this->logfingerService->index($perPage, $search);
            return $this->success('Log Fingers retrieved successfully', $logfingers);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function store(LogFingerRequest $request)
    {
        try {
            $data = $request->validated();
            $logfinger = $this->logfingerService->store($data);
            return $this->success('Log Finger created successfully', $logfinger, 201);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function show($id)
    {
        try {
            $logfinger = $this->logfingerService->show($id);
            if (!$logfinger) {
                return $this->error('Log Finger not found', 404);
            }
            return $this->success('Log Finger retrieved successfully', $logfinger);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function update(LogFingerRequest $request, $id)
    {
        try {
            $data = $request->validated();
            $logfinger = $this->logfingerService->update($id, $data);
            if (!$logfinger) {
                return $this->error('Log Finger not found', 404);
            }
            return $this->success('Log Finger updated successfully', $logfinger, 201);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function destroy($id)
    {
        try {
            $logfinger = $this->logfingerService->destroy($id);
            if (!$logfinger) {
                return $this->error('Log Finger not found', 404);
            }
            return $this->success('Log Finger deleted successfully, id : '.$logfinger->id, []);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }
}
