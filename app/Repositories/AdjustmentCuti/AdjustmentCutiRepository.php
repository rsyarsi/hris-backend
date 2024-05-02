<?php

namespace App\Repositories\AdjustmentCuti;

use App\Models\AdjustmentCuti;
use Illuminate\Support\Facades\DB;
use App\Services\CatatanCuti\CatatanCutiServiceInterface;
use App\Repositories\AdjustmentCuti\AdjustmentCutiRepositoryInterface;


class AdjustmentCutiRepository implements AdjustmentCutiRepositoryInterface
{
    private $model;
    private $catatanCutiService;
    private $field =
    [
        'id',
        'employee_id',
        'quantity_awal',
        'quantity_adjustment',
        'quantity_akhir',
        'year',
        'description'
    ];

    public function __construct(AdjustmentCuti $model, CatatanCutiServiceInterface $catatanCutiService)
    {
        $this->model = $model;
        $this->catatanCutiService = $catatanCutiService;
    }

    public function index($perPage, $search = null)
    {
        $query = $this->model->with([
            'employee' => function ($query) {
                $query->select('id', 'name', 'employment_number');
            },
        ])->select($this->field);
        if ($search !== null) {
            $query->where(function ($subquery) use ($search) {
                $subquery->where('year', 'like', '%' . $search . '%')
                    ->orWhere('employee_id', $search)
                    ->orWhereHas('employee', function ($employeeQuery) use ($search) {
                        $employeeQuery->whereRaw('LOWER(name) LIKE ?', ["%" . strtolower($search) . "%"])
                            ->orWhere('employment_number', 'like', '%' . $search . '%');
                    });
            });
        }
        return $query->orderBy('year', 'DESC')->paginate($perPage);
    }

    public function store(array $data)
    {
        // Start the database transaction
        DB::beginTransaction();

        try {
            // Create the adjustment cuti
            $adjustmentCuti = $this->model->create($data);

            // Determine the value of quantity_in and quantity_out
            $quantityAwal = $adjustmentCuti->quantity_awal;
            $quantityAdjustment = $adjustmentCuti->quantity_adjustment;
            $quantityAkhir = $adjustmentCuti->quantity_akhir;
            $valueIn = 0;
            $valueOut = 0;
            if ($quantityAkhir < $quantityAwal) {
                $valueOut = $quantityAdjustment;
            } else {
                $valueIn = $quantityAdjustment;
            }

            // Prepare data for catatan cuti
            $catatanCutiData = [
                'adjustment_cuti_id' => $adjustmentCuti->id,
                'leave_id' => null,
                'employee_id' => $adjustmentCuti->employee_id,
                'quantity_awal' => $adjustmentCuti->quantity_awal,
                'quantity_akhir' => $adjustmentCuti->quantity_akhir,
                'quantity_in' => $valueIn,
                'quantity_out' => $valueOut,
                'type' => 'ADJUSTMENT CUTI',
                'description' => $adjustmentCuti->description,
                'batal' => 0,
                'year' => now()->format('Y'),
            ];

            // Store catatan cuti within the transaction
            $this->catatanCutiService->store($catatanCutiData);

            // Commit the transaction
            DB::commit();

            return $adjustmentCuti;
        } catch (\Exception $e) {
            // Rollback the transaction in case of any exception
            DB::rollback();

            // Handle the exception (log, throw, etc.)
            // For now, we'll just rethrow the exception
            throw $e;
        }
    }

    public function show($id)
    {
        $adjustmentcuti = $this->model
            ->with([
                'employee' => function ($query) {
                    $query->select('id', 'name', 'email', 'employment_number');
                },
            ])
            ->where('id', $id)
            ->first($this->field);
        return $adjustmentcuti ? $adjustmentcuti : $adjustmentcuti = null;
    }

    public function update($id, $data)
    {
        // Start the database transaction
        DB::beginTransaction();

        try {
            // Find the adjustment cuti by ID
            $adjustmentCuti = $this->model->find($id);

            // If the adjustment cuti exists, update it
            if ($adjustmentCuti) {
                $adjustmentCuti->update($data);

                // Determine the value of quantity_in and quantity_out
                $quantityAwal = $adjustmentCuti->quantity_awal;
                $quantityAdjustment = $adjustmentCuti->quantity_adjustment;
                $quantityAkhir = $adjustmentCuti->quantity_akhir;
                $valueIn = 0;
                $valueOut = 0;
                if ($quantityAkhir < $quantityAwal) {
                    $valueOut = $quantityAdjustment;
                } else {
                    $valueIn = $quantityAdjustment;
                }

                // Prepare data for catatan cuti
                $catatanCutiData = [
                    'adjustment_cuti_id' => $adjustmentCuti->id,
                    'leave_id' => null,
                    'employee_id' => $adjustmentCuti->employee_id,
                    'quantity_awal' => $adjustmentCuti->quantity_awal,
                    'quantity_akhir' => $adjustmentCuti->quantity_akhir,
                    'quantity_in' => $valueIn,
                    'quantity_out' => $valueOut,
                    'type' => 'ADJUSTMENT CUTI',
                    'description' => $adjustmentCuti->description,
                    'batal' => 0,
                    'year' => now()->format('Y'),
                ];

                // Store catatan cuti within the transaction
                $this->catatanCutiService->store($catatanCutiData);

                // Commit the transaction
                DB::commit();

                return $adjustmentCuti;
            }

            return null;
        } catch (\Exception $e) {
            // Rollback the transaction in case of any exception
            DB::rollback();

            // Handle the exception (log, throw, etc.)
            // For now, we'll just rethrow the exception
            throw $e;
        }
    }

    public function destroy($id)
    {
        $adjustmentcuti = $this->model->find($id);
        if ($adjustmentcuti) {
            $adjustmentcuti->delete();
            return $adjustmentcuti;
        }
        return null;
    }

    public function adjustmentCutiEmployee($perPage, $search = null, $employeeId)
    {
        $query = $this->model->select($this->field);
        $query->where('employee_id', $employeeId);
        return $query->orderBy('id', 'DESC')->paginate($perPage);
    }
}
