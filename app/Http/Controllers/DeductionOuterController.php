<?php

namespace App\Http\Controllers;

use App\Traits\ResponseAPI;
use Illuminate\Http\Request;
use App\Http\Requests\DeductionOuterRequest;
use App\Services\Deduction\DeductionServiceInterface;

class DeductionOuterController extends Controller
{
    use ResponseAPI;

    private $deductionService;

    public function __construct(DeductionServiceInterface $deductionService)
    {
        $this->deductionService = $deductionService;
    }

    public function store(DeductionOuterRequest $request)
    {
        try {
            $data = $request->validated();
            $deduction = $this->deductionService->storeOuter($data);
            return $this->success('Deduction created successfully!', $deduction, 201);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }
}
