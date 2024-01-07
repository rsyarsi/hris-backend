<?php
namespace App\Services\ShiftScheduleExchange;

use App\Services\Shift\ShiftServiceInterface;
use App\Services\Employee\EmployeeServiceInterface;
use App\Services\ShiftSchedule\ShiftScheduleServiceInterface;
use App\Repositories\ShiftScheduleExchange\ShiftScheduleExchangeRepositoryInterface;

class ShiftScheduleExchangeService implements ShiftScheduleExchangeServiceInterface
{
    private $repository;
    private $shiftScheduleService;
    private $shiftService;
    private $employeeService;

    public function __construct(
        ShiftScheduleExchangeRepositoryInterface $repository,
        ShiftScheduleServiceInterface $shiftScheduleService,
        ShiftServiceInterface $shiftService,
        EmployeeServiceInterface $employeeService
    )
    {
        $this->repository = $repository;
        $this->shiftScheduleService = $shiftScheduleService;
        $this->shiftService = $shiftService;
        $this->employeeService = $employeeService;
    }

    public function index($perPage, $search, $startDate, $endDate)
    {
        return $this->repository->index($perPage, $search, $startDate, $endDate);
    }

    public function store(array $data)
    {
        $shiftExchangeType = $data['shift_exchange_type'];
        // request
        $employeRequestedId = $data['employe_requested_id'];
        $shiftScheduleDateRequested = $data['shift_schedule_date_requested'];
        $data['employe_requested_id'] = $employeRequestedId;
        $data['shift_schedule_date_requested'] = $shiftScheduleDateRequested;

        $toEmployeeId = $data['to_employee_id'];
        $toShiftScheduleDate = $data['to_shift_schedule_date'];

        $exchangeEmployeeId = $data['exchange_employee_id'];
        $exchangeDate = $data['exchange_date'];

        // employee service
        $employee = $this->employeeService->show($employeRequestedId);

        // shift service
        $shiftLibur = $this->shiftService->searchShiftLibur($employee->shift_group_id);

        // shift schedule service
        $shiftScheduleRequested = $this->shiftScheduleService->shiftScheduleEmployeeDate($employeRequestedId, $shiftScheduleDateRequested);
        if (!$shiftScheduleRequested) {
            return [
                'message' => 'Validation Error!',
                'success' => false,
                'code' => 422,
                'data' => [
                        'shift_schedule_date_requested' => ['Data Shift Schedule belum ada, silahkan hubungi atasan!']
                    ]
            ];
        }
        // if (!$shiftScheduleRequested) {
        //     return [
        //         'message' => 'Validation Error!',
        //         'success' => false,
        //         'code' => 422,
        //         'data' => [$shiftScheduleRequested]
        //     ];
        // }
        $data['shift_schedule_request_id'] = $shiftScheduleRequested->id;
        $data['shift_id_request'] = $shiftScheduleRequested->shift->id ?? null;

        if ($shiftExchangeType == "LIBUR") {
            $data['shift_id_request'] = $shiftLibur->id;
            $data['shift_schedule_code_requested'] = $shiftLibur->code;
            $data['shift_schedule_name_requested'] = $shiftLibur->name;
            $data['shift_schedule_time_from_requested'] = $shiftScheduleDateRequested. ' 00:00:00';
            $data['shift_schedule_time_end_requested'] = $shiftScheduleDateRequested. ' 00:00:00';

            $data['to_shift_schedule_date'] = null;
            $data['to_employee_id'] = null;
            $data['to_shift_schedule_id'] = null;
            $data['to_shift_schedule_code'] = null;
            $data['to_shift_schedule_name'] = null;
            $data['to_shift_schedule_time_from'] = null;
            $data['to_shift_schedule_time_end'] = null;

            $data['exchange_date'] = null;
            $data['exchange_employee_id'] = null;
            $data['exchange_shift_schedule_id'] = null;
            $data['exchange_shift_schedule_code'] = null;
            $data['exchange_shift_schedule_name'] = null;
            $data['exchange_shift_schedule_time_from'] = null;
            $data['exchange_shift_schedule_time_end'] = null;
        } else {
            $data['shift_id_request'] = $shiftScheduleRequested->shift->id;
            $data['shift_schedule_code_requested'] = $shiftScheduleRequested->shift->code;
            $data['shift_schedule_name_requested'] = $shiftScheduleRequested->shift->name;
            $data['shift_schedule_time_from_requested'] = $shiftScheduleRequested->time_in;
            $data['shift_schedule_time_end_requested'] = $shiftScheduleRequested->time_out;

            // to
            if ($data['to_employee_id'] !== null && $employeRequestedId == $data['to_employee_id']) {
                $shiftScheduleTo = $this->shiftScheduleService->shiftScheduleEmployeeDate($toEmployeeId, $toShiftScheduleDate);
                if (!$shiftScheduleTo) {
                    return [
                        'message' => 'Validation Error!',
                        'success' => false,
                        'code' => 422,
                        'data' => [
                                'to_shift_schedule_date' => ['Data Shift Schedule belum ada, silahkan hubungi atasan!']
                            ]
                    ];
                }
                $data['to_employee_id'] = $toEmployeeId;
                $data['to_shift_schedule_date'] = $toShiftScheduleDate;
                $data['shift_id_to'] = $shiftScheduleTo->shift->id;
                $data['to_shift_schedule_id'] = $shiftScheduleTo->id;
                $data['to_shift_id'] = $shiftScheduleTo->shift->id;
                $data['to_shift_schedule_code'] = $shiftScheduleTo->shift->code;
                $data['to_shift_schedule_name'] = $shiftScheduleTo->shift->name;
                $data['to_shift_schedule_time_from'] = $shiftScheduleTo->time_in;
                $data['to_shift_schedule_time_end'] = $shiftScheduleTo->time_out;
            }

            // exchange
            if ($data['exchange_employee_id'] !== null && $employeRequestedId !== $data['to_employee_id']) {
                $shiftScheduleExchange = $this->shiftScheduleService->shiftScheduleEmployeeDate($exchangeEmployeeId, $exchangeDate);
                if (!$shiftScheduleExchange) {
                    return [
                        'message' => 'Validation Error!',
                        'success' => false,
                        'code' => 422,
                        'data' => [
                                'exchange_date' => ['Data Shift Schedule belum ada, silahkan hubungi atasan!']
                            ]
                    ];
                }
                $data['exchange_employee_id'] = $exchangeEmployeeId;
                $data['exchange_shift_schedule_id'] = $shiftScheduleExchange->id;
                $data['exchange_shift_id'] = $shiftScheduleExchange->shift->id;
                $data['exchange_shift_schedule_code'] = $shiftScheduleExchange->shift->code;
                $data['exchange_shift_schedule_name'] = $shiftScheduleExchange->shift->name;
                $data['exchange_shift_schedule_time_from'] = $shiftScheduleExchange->time_in;
                $data['exchange_shift_schedule_time_end'] = $shiftScheduleExchange->time_out;
            }
        }

        $data['date_created'] = now()->format('Y-m-d');
        $data['date_updated'] = now()->format('Y-m-d');
        $data['user_created_id'] = auth()->id();
        $data['user_updated_id'] = auth()->id();
        return $this->repository->store($data);
    }

    public function show($id)
    {
        return $this->repository->show($id);
    }

    public function update($id, $data)
    {
        $data['user_updated_id'] = auth()->id();
        return $this->repository->update($id, $data);
    }

    public function destroy($id)
    {
        return $this->repository->destroy($id);
    }
}
