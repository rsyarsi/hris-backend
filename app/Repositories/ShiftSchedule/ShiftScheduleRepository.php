<?php

namespace App\Repositories\ShiftSchedule;

use App\Models\ShiftSchedule;
use App\Repositories\ShiftSchedule\ShiftScheduleRepositoryInterface;


class ShiftScheduleRepository implements ShiftScheduleRepositoryInterface
{
    private $model;
    private $field = [
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
    ];

    public function __construct(ShiftSchedule $model)
    {
        $this->model = $model;
    }

    public function index($perPage, $search = null)
    {
        $query = $this->model
                    ->with([
                        'employee' => function ($query) {
                            $query->select('id', 'name');
                        },
                        'shift' => function ($query) {
                            $query->select(
                                'id',
                                'shift_group_id',
                                'code',
                                'name',
                                'in_time',
                                'out_time',
                                'finger_in_less',
                                'finger_in_more',
                                'finger_out_less',
                                'finger_out_more',
                                'night_shift',
                                'active',
                                'user_created_id',
                                'user_updated_id',
                            );
                        },
                        'shiftExcange' => function ($query) {
                            $query->select(
                                'id',
                                'employee_id',
                                'leave_type_id',
                                'from_date',
                                'to_date',
                                'duration',
                                'note',
                                'leave_status_id',
                            );
                        },
                        'userExchange' => function ($query) {
                            $query->select('id', 'name');
                        },
                        'userCreate' => function ($query) {
                            $query->select('id', 'name');
                        },
                        'userUpdate' => function ($query) {
                            $query->select('id', 'name');
                        },
                        'userSetup' => function ($query) {
                            $query->select('id', 'name');
                        },
                    ])
                    ->select($this->field);
        return $query->paginate($perPage);
    }

    public function store(array $data)
    {
        return $this->model->create($data);
    }

    public function show($id)
    {
        $shiftschedule = $this->model
                            ->with([
                                'employee' => function ($query) {
                                    $query->select('id', 'name');
                                },
                                'shift' => function ($query) {
                                    $query->select(
                                        'id',
                                        'shift_group_id',
                                        'code',
                                        'name',
                                        'in_time',
                                        'out_time',
                                        'finger_in_less',
                                        'finger_in_more',
                                        'finger_out_less',
                                        'finger_out_more',
                                        'night_shift',
                                        'active',
                                        'user_created_id',
                                        'user_updated_id',
                                    );
                                },
                                'shiftExcange' => function ($query) {
                                    $query->select(
                                        'id',
                                        'employee_id',
                                        'leave_type_id',
                                        'from_date',
                                        'to_date',
                                        'duration',
                                        'note',
                                        'leave_status_id',
                                    );
                                },
                                'userExchange' => function ($query) {
                                    $query->select('id', 'name');
                                },
                                'userCreate' => function ($query) {
                                    $query->select('id', 'name');
                                },
                                'userUpdate' => function ($query) {
                                    $query->select('id', 'name');
                                },
                                'userSetup' => function ($query) {
                                    $query->select('id', 'name');
                                },
                            ])
                        ->where('id', $id)
                        ->first($this->field);
        return $shiftschedule ? $shiftschedule : $shiftschedule = null;
    }

    public function update($id, $data)
    {
        $shiftschedule = $this->model->find($id);
        if ($shiftschedule) {
            $shiftschedule->update($data);
            return $shiftschedule;
        }
        return null;
    }

    public function destroy($id)
    {
        $shiftschedule = $this->model->find($id);
        if ($shiftschedule) {
            $shiftschedule->delete();
            return $shiftschedule;
        }
        return null;
    }
}
