<?php

namespace App\Repositories\ShiftScheduleExchange;

use App\Models\ShiftSchedule;
use App\Models\ShiftScheduleExchange;
use App\Repositories\ShiftScheduleExchange\ShiftScheduleExchangeRepositoryInterface;
use App\Services\ShiftSchedule\ShiftScheduleServiceInterface;
use Carbon\Carbon;

class ShiftScheduleExchangeRepository implements ShiftScheduleExchangeRepositoryInterface
{
    private $model;
    private $shiftScheduleService;
    private $field = [
        'id',
        'employe_requested_id',
        'shift_schedule_date_requested',
        'shift_schedule_request_id',
        'shift_schedule_code_requested',
        'shift_schedule_name_requested',
        'shift_schedule_time_from_requested',
        'shift_schedule_time_end_requested',
        'shift_exchange_type',
        'to_employee_id',
        'to_shift_schedule_date',
        'to_shift_schedule_id',
        'to_shift_schedule_code',
        'to_shift_schedule_name',
        'to_shift_schedule_time_from',
        'to_shift_schedule_time_end',
        'exchange_employee_id',
        'exchange_date',
        'exchange_shift_schedule_id',
        'exchange_shift_schedule_code',
        'exchange_shift_schedule_name',
        'exchange_shift_schedule_time_from',
        'exchange_shift_schedule_time_end',
        'date_created',
        'date_updated',
        'user_created_id',
        'user_updated_id',
        'cancel',
    ];
    private $fieldShiftSchedule = [
        'id',
        'employee_id',
        'shift_id',
        'date',
        'time_in',
        'time_out',
        'late_note',
        'shift_exchange_id',
        'user_exchange_id',
        'user_exchange_at',
        'created_user_id',
        'updated_user_id',
        'setup_user_id',
        'setup_at',
        'period',
        'leave_note',
        'holiday',
        'night',
        'national_holiday',
        'leave_id',
        'absen_type',
    ];

    public function __construct(
        ShiftScheduleExchange $model,
        ShiftScheduleServiceInterface $shiftScheduleService
    )
    {
        $this->model = $model;
        $this->shiftScheduleService = $shiftScheduleService;
    }

    public function index($perPage, $search = null, $startDate = null, $endDate = null)
    {
        $query = $this->model
                    ->with([
                        'employeeRequest' => function ($query) {
                            $query->select('id', 'name', 'employment_number');
                        },
                        'employeeTo' => function ($query) {
                            $query->select('id', 'name', 'employment_number');
                        },
                        'shiftScheduleRequest' => function ($query) {
                            $query->select($this->fieldShiftSchedule);
                        },
                        'shiftScheduleTo' => function ($query) {
                            $query->select($this->fieldShiftSchedule);
                        },
                        'exchangeShiftSchedule' => function ($query) {
                            $query->select($this->fieldShiftSchedule);
                        },
                        'userCreated' => function ($query) {
                            $query->select('id', 'name', 'email');
                        },
                        'userUpdated' => function ($query) {
                            $query->select('id', 'name', 'email');
                        },
                    ])
                    ->select($this->field);
        if ($search) {
            $query->where(function ($subquery) use ($search) {
                $subquery->orWhere('employee_id', $search)
                            ->orWhereHas('employee', function ($employeeQuery) use ($search) {
                                $employeeQuery->whereRaw('LOWER(name) LIKE ?', ["%".strtolower($search)."%"])
                                                ->orWhere('employment_number', 'like', '%' . $search . '%');
                            });
            });
        }
        if ($startDate) {
            $query->whereDate('date', '>=', $startDate);
        }
        if ($endDate) {
            $query->whereDate('date', '<=', $endDate);
        }
        return $query->paginate($perPage);
    }

    public function store(array $data)
    {
        // untuk tukar shift butuh shift id
        $shiftIdRequest = $data['shift_id_request'];
        $toShiftId = $data['to_shift_id'] ?? null;

        $shiftScheduleExchange = $this->model->create($data);
        $employeeRequestId = $shiftScheduleExchange->employe_requested_id;
        $employeeToId = $shiftScheduleExchange->to_employee_id;
        $employeeExchangeId = $shiftScheduleExchange->exchange_employee_id;

        if ($shiftScheduleExchange->shift_exchange_type == "LIBUR") {
            // libur
            $dataShiftScheduleRequest['employee_id'] = $shiftScheduleExchange->employe_requested_id;
            $dataShiftScheduleRequest['shift_id'] = $data['shift_id_request'];
            $dataShiftScheduleRequest['date'] = $shiftScheduleExchange->shift_schedule_date_requested;
            $dataShiftScheduleRequest['time_in'] = $shiftScheduleExchange->shift_schedule_time_from_requested;
            $dataShiftScheduleRequest['time_out'] = $shiftScheduleExchange->shift_schedule_time_end_requested;
            $dataShiftScheduleRequest['shift_exchange_id'] = $shiftScheduleExchange->id;
            $dataShiftScheduleRequest['user_exchange_id'] = $shiftScheduleExchange->employeeRequest->user->id;
            $dataShiftScheduleRequest['user_exchange_at'] = now();
            ShiftSchedule::where('id', $data['shift_schedule_request_id'])
                        ->update($dataShiftScheduleRequest);
        } elseif ($shiftScheduleExchange->shift_exchange_type == "TUKAR SHIFT" && $employeeRequestId == $employeeToId) {
            // update shift schedule where tanggal request
            ShiftSchedule::where('id', $shiftScheduleExchange->shift_schedule_request_id)
                        ->update([
                            'shift_exchange_id' => $shiftScheduleExchange->id,
                            'shift_id' => $toShiftId,
                            'time_in' => $shiftScheduleExchange->shift_schedule_date_requested.' '.Carbon::parse($shiftScheduleExchange->to_shift_schedule_time_from)->format('H:i:s'),
                            'time_out' => $shiftScheduleExchange->shift_schedule_date_requested.' '.Carbon::parse($shiftScheduleExchange->to_shift_schedule_time_end)->format('H:i:s'),
                            'user_exchange_id' => $shiftScheduleExchange->employeeRequest->user->id,
                            'user_exchange_at' => now(),
                        ]);
            // update shift schedule where tanggal to
            ShiftSchedule::where('id', $shiftScheduleExchange->to_shift_schedule_id)
                        ->update([
                            'shift_exchange_id' => $shiftScheduleExchange->id,
                            'shift_id' => $shiftIdRequest,
                            'time_in' => $shiftScheduleExchange->to_shift_schedule_date.' '.Carbon::parse($shiftScheduleExchange->shift_schedule_time_from_requested)->format('H:i:s'),
                            'time_out' => $shiftScheduleExchange->to_shift_schedule_date.' '.Carbon::parse($shiftScheduleExchange->shift_schedule_time_end_requested)->format('H:i:s'),
                            'user_exchange_id' => $shiftScheduleExchange->employeeRequest->user->id,
                            'user_exchange_at' => now(),
                        ]);
        }  elseif ($shiftScheduleExchange->shift_exchange_type == "TUKAR SHIFT" && $employeeRequestId !== $employeeToId) {

        }

        return $shiftScheduleExchange;
    }

    public function show($id)
    {
        $shiftscheduleexchange = $this->model
                                    ->with([
                                        'employeeRequest' => function ($query) {
                                            $query->select('id', 'name', 'employment_number');
                                        },
                                        'employeeTo' => function ($query) {
                                            $query->select('id', 'name', 'employment_number');
                                        },
                                        'shiftScheduleRequest' => function ($query) {
                                            $query->select($this->fieldShiftSchedule);
                                        },
                                        'shiftScheduleTo' => function ($query) {
                                            $query->select($this->fieldShiftSchedule);
                                        },
                                        'exchangeShiftSchedule' => function ($query) {
                                            $query->select($this->fieldShiftSchedule);
                                        },
                                        'userCreated' => function ($query) {
                                            $query->select('id', 'name', 'email');
                                        },
                                        'userUpdated' => function ($query) {
                                            $query->select('id', 'name', 'email');
                                        },
                                    ])
                                    ->where('id', $id)
                                    ->first($this->field);
        return $shiftscheduleexchange ? $shiftscheduleexchange : $shiftscheduleexchange = null;
    }

    public function update($id, $data)
    {
        $shiftscheduleexchange = $this->model->find($id);
        if ($shiftscheduleexchange) {
            $shiftscheduleexchange->update($data);
            return $shiftscheduleexchange;
        }
        return null;
    }

    public function destroy($id)
    {
        $shiftscheduleexchange = $this->model->find($id);
        if ($shiftscheduleexchange) {
            $shiftscheduleexchange->delete();
            return $shiftscheduleexchange;
        }
        return null;
    }
}
