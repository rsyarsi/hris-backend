<?php

namespace App\Repositories\ShiftScheduleExchange;

use App\Models\ShiftSchedule;
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
        $dataShiftScheduleRequest['employee_id'] = $shiftScheduleExchange->employe_requested_id;
        $dataShiftScheduleRequest['shift_id'] = $shiftScheduleExchange->shift_schedule_request_id;
        $dataShiftScheduleRequest['date'] = $shiftScheduleExchange->shift_schedule_date_requested;
        $dataShiftScheduleRequest['time_in'] = $shiftScheduleExchange->shift_schedule_time_from_requested;
        $dataShiftScheduleRequest['time_out'] = $shiftScheduleExchange->shift_schedule_time_end_requested;
        $dataShiftScheduleRequest['shift_exchange_id'] = $shiftScheduleExchange->id;
        $dataShiftScheduleRequest['user_exchange_id'] = auth()->id();
        $dataShiftScheduleRequest['user_exchange_at'] = now();

        $dataShiftScheduleTo['employee_id'] = $shiftScheduleExchange->employe_requested_id;
        $dataShiftScheduleTo['shift_id'] = '';
        $dataShiftScheduleTo['date'] = '';
        $dataShiftScheduleTo['time_in'] = '';
        $dataShiftScheduleTo['time_out'] = '';
        $dataShiftScheduleTo['shift_exchange_id'] = '';
        $dataShiftScheduleTo['user_exchange_id'] = '';
        $dataShiftScheduleTo['user_exchange_at'] = '';
        
        $dataShiftScheduleExchange['employee_id'] = $shiftScheduleExchange->employe_requested_id;
        $dataShiftScheduleExchange['shift_id'] = '';
        $dataShiftScheduleExchange['date'] = '';
        $dataShiftScheduleExchange['time_in'] = '';
        $dataShiftScheduleExchange['time_out'] = '';
        $dataShiftScheduleExchange['shift_exchange_id'] = '';
        $dataShiftScheduleExchange['user_exchange_id'] = '';
        $dataShiftScheduleExchange['user_exchange_at'] = '';
        
        // $this->shiftScheduleService->updateShiftScheduleExchage($dataShiftScheduleRequest);
        // $this->shiftScheduleService->updateShiftScheduleExchage($dataShiftScheduleTo);
        // $this->shiftScheduleService->updateShiftScheduleExchage($dataShiftScheduleExchange);

        // libur & to
        $shiftScheduleRequest = ShiftSchedule::where('employee_id', $shiftScheduleExchange->employe_requested_id)
                                                ->where('date', $shiftScheduleExchange->shift_schedule_date_requested)
                                                ->update($dataShiftScheduleRequest);
        // exchange
        $shiftScheduleExchange = ShiftSchedule::where('employee_id', $shiftScheduleExchange->employe_requested_id)
                                                ->where('date', $shiftScheduleExchange->shift_schedule_date_requested)
                                                ->update($dataShiftScheduleRequest);
        
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
