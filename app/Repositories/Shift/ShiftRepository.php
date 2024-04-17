<?php

namespace App\Repositories\Shift;

use App\Models\Shift;
use App\Models\GenerateAbsen;
use App\Models\ShiftSchedule;
use App\Repositories\Shift\ShiftRepositoryInterface;


class ShiftRepository implements ShiftRepositoryInterface
{
    private $model;
    private $field = [
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
        'finger_out_less',
        'night_shift',
        'active',
        'user_created_id',
        'user_updated_id',
        'libur',
        'on_call',
        'lepas_dinas',
    ];

    public function __construct(Shift $model)
    {
        $this->model = $model;
    }

    public function index($perPage, $search = null, $groupShiftId = null, $active = 1)
    {
        $query = $this->model
            ->with([
                'shiftGroup' => function ($query) {
                    $query->select('id', 'name', 'hour', 'day', 'type');
                },
                'userCreated' => function ($query) {
                    $query->select('id', 'name', 'email');
                },
                'userUpdated' => function ($query) {
                    $query->select('id', 'name', 'email');
                },
            ])
            ->select($this->field);
        if ($search !== null) {
            $query->whereRaw('LOWER(name) LIKE ?', ["%" . strtolower($search) . "%"]);
        }
        // Add the condition to filter by shift_group_id
        if ($groupShiftId !== null) {
            $query->where('shift_group_id', $groupShiftId);
        }
        if ($active !== null) {
            $query->where('active', $active);
        }
        return $query->paginate($perPage);
    }

    public function store(array $data)
    {
        return $this->model->create($data);
    }

    public function show($id)
    {
        $shift = $this->model
            ->with([
                'shiftGroup' => function ($query) {
                    $query->select('id', 'name', 'hour', 'day', 'type');
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
        return $shift ? $shift : $shift = null;
    }

    public function update($id, $data)
    {
        $date = now()->toDateString();
        $shiftSchedule = ShiftSchedule::where('shift_id', $id)
                                        ->where('date', $date)
                                        ->exists();
        $generateAbsen = GenerateAbsen::where('shift_id', $id)
                                        ->where('date', $date)
                                        ->exists();
        if ($shiftSchedule || $generateAbsen) {
            return [
                'message' => 'Kode shift ini sudah ada Jadwal Shift kerja / Generate Absen. Edit tidak diizinkan.',
                'error' => true,
                'code' => 422,
                'data' => ['code' => ['Kode shift ini sudah ada Jadwal Shift kerja / Generate Absen. Edit tidak diizinkan.']]
            ];
        }
        $shift = $this->model->find($id);
        if ($shift) {
            $shift->update($data);
            return [
                'message' => 'Shift Updated Successfully!',
                'error' => false,
                'code' => 201,
                'data' => $shift
            ];
        }
        return [
            'message' => 'Shift Not found',
            'error' => true,
            'code' => 422,
            'data' => null
        ];
    }

    public function destroy($id)
    {
        $shift = $this->model->find($id);
        if ($shift) {
            $shift->delete();
            return $shift;
        }
        return null;
    }

    public function searchShiftLibur($shiftGroupId)
    {
        $shift = $this->model
            ->where('shift_group_id', $shiftGroupId)
            ->where('code', 'L')
            ->orWhere('name', 'LIBUR')
            ->first($this->field);
        if ($shift) {
            return $shift;
        }
        return null;
    }
}
