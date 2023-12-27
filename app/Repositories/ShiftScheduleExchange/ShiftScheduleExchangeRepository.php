<?php

namespace App\Repositories\ShiftScheduleExchange;

use App\Models\ShiftScheduleExchange;
use App\Repositories\ShiftScheduleExchange\ShiftScheduleExchangeRepositoryInterface;
use App\Services\ShiftSchedule\ShiftScheduleServiceInterface;

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
        $shiftScheduleExchange = $this->model->create($data);
        $dataShiftSchedule['employee_id'] = $shiftScheduleExchange->employe_requested_id;
        $dataShiftSchedule['shift_exchange_id'] = $shiftScheduleExchange->id;
        $dataShiftSchedule['date_requested'] = $shiftScheduleExchange->shift_schedule_date_requested;
        $dataShiftSchedule['time_in'] = $shiftScheduleExchange->shift_schedule_time_from_requested;
        $dataShiftSchedule['time_out'] = $shiftScheduleExchange->shift_schedule_time_end_requested;
        $dataShiftSchedule['user_exchange_id'] = $shiftScheduleExchange->user_created_id;
        $dataShiftSchedule['shift_id'] = $data['shift_libur_id'];
        // $dataShiftSchedule = [
        //     // $data['shift_id'] => ,
        //     // $data['date'] => ,
        //     // $data['time_in'] => ,
        //     // $data['time_out'] => ,
        //     // $data['shift_exchange_id'] => ,
        //     // $data['user_exchange_id'] => ,
        //     // $data['user_exchange_at'] => ,
        //     // $data['created_user_id'] => ,
        //     // $data['updated_user_id'] => ,
        // ];
        $this->shiftScheduleService->updateShiftScheduleExchage($dataShiftSchedule);
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
