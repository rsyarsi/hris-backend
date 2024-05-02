<?php

namespace App\Repositories\ShiftScheduleExchange;

use Carbon\Carbon;
use App\Models\Employee;
use Illuminate\Support\Facades\DB;
use App\Models\{Shift, ShiftSchedule, ShiftScheduleExchange};
use App\Repositories\ShiftScheduleExchange\ShiftScheduleExchangeRepositoryInterface;

class ShiftScheduleExchangeRepository implements ShiftScheduleExchangeRepositoryInterface
{
    private $model;
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
        'shift_awal_request_id',
        'to_shift_awal_id',
        'exchange_shift_awal_id',
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

    public function __construct(ShiftScheduleExchange $model)
    {
        $this->model = $model;
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
                'exchangeEmployee' => function ($query) {
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
                'shiftAwalRequest' => function ($query) {
                    $query->select('id', 'name');
                },
                'exchangeShiftTo' => function ($query) {
                    $query->select('id', 'name');
                },
                'exchangeShiftAwal' => function ($query) {
                    $query->select('id', 'name');
                },
            ])
            ->select($this->field);
        if ($search) {
            $query->where(function ($subquery) use ($search) {
                $subquery->orWhere('employe_requested_id', $search)
                    ->orWhereHas('employeeRequest', function ($employeeQuery) use ($search) {
                        $employeeQuery->whereRaw('LOWER(name) LIKE ?', ["%" . strtolower($search) . "%"])
                            ->orWhere('employment_number', 'like', '%' . $search . '%');
                    });
            });
        }
        if ($startDate) {
            $query->whereDate('shift_schedule_date_requested', '>=', $startDate);
        }
        if ($endDate) {
            $query->whereDate('shift_schedule_date_requested', '<=', $endDate);
        }
        return $query->paginate($perPage);
    }

    public function indexSubordinate($perPage, $search = null, $startDate = null, $endDate = null)
    {
        $user = auth()->user();
        if (!$user->employee) {
            return [];
        }
        $queryEmployee = Employee::where(function ($q) use ($user) {
            $q->where('supervisor_id', $user->employee->id)
                ->orWhere('manager_id', $user->employee->id)
                ->orWhere('kabag_id', $user->employee->id);
        })
            ->get();
        $employeeIds = []; // Collect employee IDs in an array
        foreach ($queryEmployee as $item) {
            $employeeIds[] = $item->id;
        }
        $query = $this->model
            ->with([
                'employeeRequest' => function ($query) {
                    $query->select('id', 'name', 'employment_number');
                },
                'employeeTo' => function ($query) {
                    $query->select('id', 'name', 'employment_number');
                },
                'exchangeEmployee' => function ($query) {
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
                'shiftAwalRequest' => function ($query) {
                    $query->select('id', 'name');
                },
                'exchangeShiftTo' => function ($query) {
                    $query->select('id', 'name');
                },
                'exchangeShiftAwal' => function ($query) {
                    $query->select('id', 'name');
                },
            ])
            ->whereIn('employe_requested_id', $employeeIds)
            ->select($this->field);
        if ($search) {
            $query->where(function ($subquery) use ($search) {
                $subquery->orWhere('employe_requested_id', $search)
                    ->orWhereHas('employeeRequest', function ($employeeQuery) use ($search) {
                        $employeeQuery->whereRaw('LOWER(name) LIKE ?', ["%" . strtolower($search) . "%"])
                            ->orWhere('employment_number', 'like', '%' . $search . '%');
                    });
            });
        }
        if ($startDate) {
            $query->whereDate('shift_schedule_date_requested', '>=', $startDate);
        }
        if ($endDate) {
            $query->whereDate('shift_schedule_date_requested', '<=', $endDate);
        }
        return $query->orderBy('shift_schedule_date_requested', 'DESC')->paginate($perPage);
    }

    public function indexSubordinateMobile($employeeId)
    {
        $subordinateIds = Employee::where('supervisor_id', $employeeId)
            ->orWhere('manager_id', $employeeId)
            ->orWhere('kabag_id', $employeeId)
            ->pluck('id');
        return DB::table('shift_schedule_exchanges')
            ->leftJoin('employees AS requested_employee', 'shift_schedule_exchanges.employe_requested_id', '=', 'requested_employee.id')
            ->leftJoin('employees AS to_employee', 'shift_schedule_exchanges.to_employee_id', '=', 'to_employee.id')
            ->leftJoin('employees AS exchange_employee', 'shift_schedule_exchanges.exchange_employee_id', '=', 'exchange_employee.id')
            ->select([
                'shift_schedule_exchanges.id',
                'requested_employee.name AS requested_employee_name',
                'to_employee.name AS to_employee_name',
                'exchange_employee.name AS exchange_employee_name',
            ])
            ->whereIn('shift_schedule_exchanges.employe_requested_id', $subordinateIds)
            ->get();
    }

    public function store(array $data)
    {
        // Start the database transaction
        DB::beginTransaction();

        try {
            // untuk tukar shift butuh shift id
            $shiftIdRequest = $data['shift_id_request'] ?? null;
            $toShiftId = $data['to_shift_id'] ?? null;
            $exchangeShiftId = $data['exchange_shift_id'] ?? null;

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
                $dataShiftScheduleRequest['user_exchange_id'] = $shiftScheduleExchange->employeeRequest->user->id ?? null;
                $dataShiftScheduleRequest['user_exchange_at'] = now();
                ShiftSchedule::where('id', $data['shift_schedule_request_id'])
                    ->update($dataShiftScheduleRequest);
            } elseif ($shiftScheduleExchange->shift_exchange_type == "TUKAR SHIFT" && $employeeRequestId == $employeeToId) {
                // update shift schedule where tanggal request
                ShiftSchedule::where('id', $shiftScheduleExchange->shift_schedule_request_id)
                    ->update([
                        'shift_exchange_id' => $shiftScheduleExchange->id,
                        'shift_id' => $toShiftId,
                        'time_in' => $shiftScheduleExchange->shift_schedule_date_requested . ' ' . Carbon::parse($shiftScheduleExchange->to_shift_schedule_time_from)->format('H:i:s'),
                        'time_out' => $shiftScheduleExchange->shift_schedule_date_requested . ' ' . Carbon::parse($shiftScheduleExchange->to_shift_schedule_time_end)->format('H:i:s'),
                        'user_exchange_id' => $shiftScheduleExchange->employeeRequest->user->id ?? null,
                        'user_exchange_at' => now(),
                    ]);
                // update shift schedule where tanggal to
                ShiftSchedule::where('id', $shiftScheduleExchange->to_shift_schedule_id)
                    ->update([
                        'shift_exchange_id' => $shiftScheduleExchange->id,
                        'shift_id' => $shiftIdRequest,
                        'time_in' => $shiftScheduleExchange->to_shift_schedule_date . ' ' . Carbon::parse($shiftScheduleExchange->shift_schedule_time_from_requested)->format('H:i:s'),
                        'time_out' => $shiftScheduleExchange->to_shift_schedule_date . ' ' . Carbon::parse($shiftScheduleExchange->shift_schedule_time_end_requested)->format('H:i:s'),
                        'user_exchange_id' => $shiftScheduleExchange->employeeRequest->user->id ?? null,
                        'user_exchange_at' => now(),
                    ]);
            } elseif ($shiftScheduleExchange->shift_exchange_type == "TUKAR SHIFT" && $employeeRequestId !== $employeeExchangeId) {
                // update shift schedule where tanggal request
                ShiftSchedule::where('id', $shiftScheduleExchange->shift_schedule_request_id)
                    ->update([
                        'shift_exchange_id' => $shiftScheduleExchange->id,
                        'shift_id' => $exchangeShiftId,
                        'time_in' => $shiftScheduleExchange->shift_schedule_date_requested . ' ' . Carbon::parse($shiftScheduleExchange->exchange_shift_schedule_time_from)->format('H:i:s'),
                        'time_out' => $shiftScheduleExchange->shift_schedule_date_requested . ' ' . Carbon::parse($shiftScheduleExchange->exchange_shift_schedule_time_end)->format('H:i:s'),
                        'user_exchange_id' => $shiftScheduleExchange->employeeRequest->user->id ?? null,
                        'user_exchange_at' => now(),
                    ]);
                // update shift schedule where tanggal exchange
                ShiftSchedule::where('id', $shiftScheduleExchange->exchange_shift_schedule_id)
                    ->update([
                        'shift_exchange_id' => $shiftScheduleExchange->id,
                        'shift_id' => $shiftIdRequest,
                        'time_in' => $shiftScheduleExchange->exchange_date . ' ' . Carbon::parse($shiftScheduleExchange->shift_schedule_time_from_requested)->format('H:i:s'),
                        'time_out' => $shiftScheduleExchange->exchange_date . ' ' . Carbon::parse($shiftScheduleExchange->shift_schedule_time_end_requested)->format('H:i:s'),
                        'user_exchange_id' => $shiftScheduleExchange->employeeRequest->user->id ?? null,
                        'user_exchange_at' => now(),
                    ]);
            }
            // Commit the transaction
            DB::commit();
            return [
                'message' => 'Shift schedule exchange created successfully',
                'success' => true,
                'code' => 201,
                'data' => [$shiftScheduleExchange]
            ];
        } catch (\Exception $e) {
            // Rollback the transaction in case of any exception
            DB::rollback();

            // Handle the exception (log, throw, etc.)
            // For now, we'll just rethrow the exception
            throw $e;
        }
    }

    public function createMobile(array $data)
    {
        // Start the database transaction
        DB::beginTransaction();

        try {
            // untuk tukar shift butuh shift id
            $shiftIdRequest = $data['shift_id_request'] ?? null;
            $toShiftId = $data['to_shift_id'] ?? null;
            $exchangeShiftId = $data['exchange_shift_id'] ?? null;

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
                $dataShiftScheduleRequest['user_exchange_id'] = $shiftScheduleExchange->employeeRequest->user->id ?? null;
                $dataShiftScheduleRequest['user_exchange_at'] = now();
                ShiftSchedule::where('id', $data['shift_schedule_request_id'])
                    ->update($dataShiftScheduleRequest);
            } elseif ($shiftScheduleExchange->shift_exchange_type == "TUKAR SHIFT" && $employeeRequestId == $employeeToId) {
                // update shift schedule where tanggal request
                ShiftSchedule::where('id', $shiftScheduleExchange->shift_schedule_request_id)
                    ->update([
                        'shift_exchange_id' => $shiftScheduleExchange->id,
                        'shift_id' => $toShiftId,
                        'time_in' => $shiftScheduleExchange->shift_schedule_date_requested . ' ' . Carbon::parse($shiftScheduleExchange->to_shift_schedule_time_from)->format('H:i:s'),
                        'time_out' => $shiftScheduleExchange->shift_schedule_date_requested . ' ' . Carbon::parse($shiftScheduleExchange->to_shift_schedule_time_end)->format('H:i:s'),
                        'user_exchange_id' => $shiftScheduleExchange->employeeRequest->user->id ?? null,
                        'user_exchange_at' => now(),
                    ]);
                // update shift schedule where tanggal to
                ShiftSchedule::where('id', $shiftScheduleExchange->to_shift_schedule_id)
                    ->update([
                        'shift_exchange_id' => $shiftScheduleExchange->id,
                        'shift_id' => $shiftIdRequest,
                        'time_in' => $shiftScheduleExchange->to_shift_schedule_date . ' ' . Carbon::parse($shiftScheduleExchange->shift_schedule_time_from_requested)->format('H:i:s'),
                        'time_out' => $shiftScheduleExchange->to_shift_schedule_date . ' ' . Carbon::parse($shiftScheduleExchange->shift_schedule_time_end_requested)->format('H:i:s'),
                        'user_exchange_id' => $shiftScheduleExchange->employeeRequest->user->id ?? null,
                        'user_exchange_at' => now(),
                    ]);
            } elseif ($shiftScheduleExchange->shift_exchange_type == "TUKAR SHIFT" && $employeeRequestId !== $employeeExchangeId) {
                // update shift schedule where tanggal request
                ShiftSchedule::where('id', $shiftScheduleExchange->shift_schedule_request_id)
                    ->update([
                        'shift_exchange_id' => $shiftScheduleExchange->id,
                        'shift_id' => $exchangeShiftId,
                        'time_in' => $shiftScheduleExchange->shift_schedule_date_requested . ' ' . Carbon::parse($shiftScheduleExchange->exchange_shift_schedule_time_from)->format('H:i:s'),
                        'time_out' => $shiftScheduleExchange->shift_schedule_date_requested . ' ' . Carbon::parse($shiftScheduleExchange->exchange_shift_schedule_time_end)->format('H:i:s'),
                        'user_exchange_id' => $shiftScheduleExchange->employeeRequest->user->id ?? null,
                        'user_exchange_at' => now(),
                    ]);
                // update shift schedule where tanggal exchange
                ShiftSchedule::where('id', $shiftScheduleExchange->exchange_shift_schedule_id)
                    ->update([
                        'shift_exchange_id' => $shiftScheduleExchange->id,
                        'shift_id' => $shiftIdRequest,
                        'time_in' => $shiftScheduleExchange->exchange_date . ' ' . Carbon::parse($shiftScheduleExchange->shift_schedule_time_from_requested)->format('H:i:s'),
                        'time_out' => $shiftScheduleExchange->exchange_date . ' ' . Carbon::parse($shiftScheduleExchange->shift_schedule_time_end_requested)->format('H:i:s'),
                        'user_exchange_id' => $shiftScheduleExchange->employeeRequest->user->id ?? null,
                        'user_exchange_at' => now(),
                    ]);
            }
            // Commit the transaction
            DB::commit();
            return [
                'message' => 'Shift schedule exchange created successfully',
                'success' => true,
                'code' => 201,
                'data' => [$shiftScheduleExchange]
            ];
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
        $shiftscheduleexchange = $this->model
            ->with([
                'employeeRequest' => function ($query) {
                    $query->select('id', 'name', 'employment_number');
                },
                'employeeTo' => function ($query) {
                    $query->select('id', 'name', 'employment_number');
                },
                'exchangeEmployee' => function ($query) {
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
                'shiftAwalRequest' => function ($query) {
                    $query->select('id', 'name');
                },
                'exchangeShiftTo' => function ($query) {
                    $query->select('id', 'name');
                },
                'exchangeShiftAwal' => function ($query) {
                    $query->select('id', 'name');
                },
            ])
            ->where('id', $id)
            ->first($this->field);
        return $shiftscheduleexchange ? $shiftscheduleexchange : $shiftscheduleexchange = null;
    }

    public function update($id, $data)
    {
        // Start the database transaction
        DB::beginTransaction();

        try {
            $shiftscheduleexchange = $this->model->find($id);
            if ($shiftscheduleexchange) {
                if ($data['cancel'] == 1 && $shiftscheduleexchange->shift_exchange_type == "LIBUR") {
                    $shiftRequest = Shift::where('id', $shiftscheduleexchange->shift_awal_request_id)->first();
                    ShiftSchedule::where('id', $shiftscheduleexchange->shift_schedule_request_id)
                        ->update([
                            'shift_id' => $shiftscheduleexchange->shift_awal_request_id,
                            'time_in' => $shiftscheduleexchange->shift_schedule_date_requested . ' ' . $shiftRequest->in_time,
                            'time_out' => $shiftscheduleexchange->shift_schedule_date_requested . ' ' . $shiftRequest->out_time,
                            'shift_exchange_id' => null,
                            'user_exchange_id' => null,
                            'user_exchange_at' => null,
                        ]);
                } else if ($data['cancel'] == 1 && $shiftscheduleexchange->shift_exchange_type == "TUKAR SHIFT") {
                    // request
                    $shiftRequest = Shift::where('id', $shiftscheduleexchange->shift_awal_request_id)->first();
                    ShiftSchedule::where('id', $shiftscheduleexchange->shift_schedule_request_id)
                        ->update([
                            'shift_id' => $shiftscheduleexchange->shift_awal_request_id,
                            'time_in' => $shiftscheduleexchange->shift_schedule_date_requested . ' ' . $shiftRequest->in_time,
                            'time_out' => $shiftscheduleexchange->shift_schedule_date_requested . ' ' . $shiftRequest->out_time,
                            'shift_exchange_id' => null,
                            'user_exchange_id' => null,
                            'user_exchange_at' => null,
                        ]);
                    // to
                    if ($shiftscheduleexchange->to_shift_awal_id !== null) {
                        $shiftTo = Shift::where('id', $shiftscheduleexchange->to_shift_awal_id)->first();
                        ShiftSchedule::where('id', $shiftscheduleexchange->to_shift_schedule_id)
                            ->update([
                                'shift_id' => $shiftscheduleexchange->to_shift_awal_id,
                                'time_in' => $shiftscheduleexchange->to_shift_schedule_date . ' ' . $shiftTo->in_time,
                                'time_out' => $shiftscheduleexchange->to_shift_schedule_date . ' ' . $shiftTo->out_time,
                                'shift_exchange_id' => null,
                                'user_exchange_id' => null,
                                'user_exchange_at' => null,
                            ]);
                    }
                    if ($shiftscheduleexchange->exchange_shift_awal_id !== null) {
                        // exchange
                        $shiftExchange = Shift::where('id', $shiftscheduleexchange->exchange_shift_awal_id)->first();
                        ShiftSchedule::where('id', $shiftscheduleexchange->exchange_shift_schedule_id)
                            ->update([
                                'shift_id' => $shiftscheduleexchange->exchange_shift_awal_id,
                                'time_in' => $shiftscheduleexchange->exchange_date . ' ' . $shiftExchange->in_time,
                                'time_out' => $shiftscheduleexchange->exchange_date . ' ' . $shiftExchange->out_time,
                                'shift_exchange_id' => null,
                                'user_exchange_id' => null,
                                'user_exchange_at' => null,
                            ]);
                    }
                }
                $shiftscheduleexchange->update($data);
                // Commit the transaction
                DB::commit();
                return $shiftscheduleexchange;
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
        // Start the database transaction
        DB::beginTransaction();

        try {
            $shiftscheduleexchange = $this->model->find($id);
            if ($shiftscheduleexchange) {
                if ($shiftscheduleexchange->shift_exchange_type == "LIBUR") {
                    $shiftRequest = Shift::where('id', $shiftscheduleexchange->shift_awal_request_id)->first();
                    ShiftSchedule::where('id', $shiftscheduleexchange->shift_schedule_request_id)
                        ->update([
                            'shift_id' => $shiftscheduleexchange->shift_awal_request_id,
                            'time_in' => $shiftscheduleexchange->shift_schedule_date_requested . ' ' . $shiftRequest->in_time,
                            'time_out' => $shiftscheduleexchange->shift_schedule_date_requested . ' ' . $shiftRequest->out_time,
                            'shift_exchange_id' => null,
                            'user_exchange_id' => null,
                            'user_exchange_at' => null,
                        ]);
                } else if ($shiftscheduleexchange->shift_exchange_type == "TUKAR SHIFT") {
                    // request
                    $shiftRequest = Shift::where('id', $shiftscheduleexchange->shift_awal_request_id)->first();
                    ShiftSchedule::where('id', $shiftscheduleexchange->shift_schedule_request_id)
                        ->update([
                            'shift_id' => $shiftscheduleexchange->shift_awal_request_id,
                            'time_in' => $shiftscheduleexchange->shift_schedule_date_requested . ' ' . $shiftRequest->in_time,
                            'time_out' => $shiftscheduleexchange->shift_schedule_date_requested . ' ' . $shiftRequest->out_time,
                            'shift_exchange_id' => null,
                            'user_exchange_id' => null,
                            'user_exchange_at' => null,
                        ]);
                    // to
                    if ($shiftscheduleexchange->to_shift_awal_id !== null) {
                        $shiftTo = Shift::where('id', $shiftscheduleexchange->to_shift_awal_id)->first();
                        ShiftSchedule::where('id', $shiftscheduleexchange->to_shift_schedule_id)
                            ->update([
                                'shift_id' => $shiftscheduleexchange->to_shift_awal_id,
                                'time_in' => $shiftscheduleexchange->to_shift_schedule_date . ' ' . $shiftTo->in_time,
                                'time_out' => $shiftscheduleexchange->to_shift_schedule_date . ' ' . $shiftTo->out_time,
                                'shift_exchange_id' => null,
                                'user_exchange_id' => null,
                                'user_exchange_at' => null,
                            ]);
                    }
                    if ($shiftscheduleexchange->exchange_shift_awal_id !== null) {
                        // exchange
                        $shiftExchange = Shift::where('id', $shiftscheduleexchange->exchange_shift_awal_id)->first();
                        ShiftSchedule::where('id', $shiftscheduleexchange->exchange_shift_schedule_id)
                            ->update([
                                'shift_id' => $shiftscheduleexchange->exchange_shift_awal_id,
                                'time_in' => $shiftscheduleexchange->exchange_date . ' ' . $shiftExchange->in_time,
                                'time_out' => $shiftscheduleexchange->exchange_date . ' ' . $shiftExchange->out_time,
                                'shift_exchange_id' => null,
                                'user_exchange_id' => null,
                                'user_exchange_at' => null,
                            ]);
                    }
                }
                $shiftscheduleexchange->delete();
                // Commit the transaction
                DB::commit();
                return $shiftscheduleexchange;
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
}
