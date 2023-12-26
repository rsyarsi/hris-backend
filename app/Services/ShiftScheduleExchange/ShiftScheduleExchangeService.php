<?php
namespace App\Services\ShiftScheduleExchange;

use App\Services\ShiftSchedule\ShiftScheduleServiceInterface;
use App\Repositories\ShiftScheduleExchange\ShiftScheduleExchangeRepositoryInterface;

class ShiftScheduleExchangeService implements ShiftScheduleExchangeServiceInterface
{
    private $repository;
    private $shiftScheduleService;

    public function __construct(ShiftScheduleExchangeRepositoryInterface $repository, ShiftScheduleServiceInterface $shiftScheduleService)
    {
        $this->repository = $repository;
        $this->shiftScheduleService = $shiftScheduleService;
    }

    public function index($perPage, $search, $startDate, $endDate)
    {
        return $this->repository->index($perPage, $search, $startDate, $endDate);
    }

    public function store(array $data)
    {
        $shiftExchangeType = $data['shift_exchange_type'];
        // request
        $shiftScheduleDateRequested = $data['shift_schedule_date_requested'];
        $employeRequestedId = $data['employe_requested_id'];
        $shiftScheduleRequested = $this->shiftScheduleService->shiftScheduleEmployeeDate($employeRequestedId, $shiftScheduleDateRequested);
        // return $shiftScheduleRequested;
        $data['shift_schedule_date_requested'] = $shiftScheduleDateRequested;
        $data['employe_requested_id'] = $employeRequestedId;
        $data['shift_schedule_request_id'] = $shiftScheduleRequested->id;
        $data['shift_schedule_code_requested'] = $shiftScheduleRequested->shift->code;
        $data['shift_schedule_name_requested'] = $shiftScheduleRequested->shift->name;
        $data['shift_schedule_time_from_requested'] = $shiftScheduleRequested->time_in;
        $data['shift_schedule_time_end_requested'] = $shiftScheduleRequested->time_out;

        // to
        $toEmployeeId = $data['to_employee_id'];
        $toShiftScheduleDate = $data['to_shift_schedule_date'];
        $shiftScheduleTo = $this->shiftScheduleService->shiftScheduleEmployeeDate($toEmployeeId, $toShiftScheduleDate);
        // return $shiftScheduleTo;
        $data['to_shift_schedule_date'] = $toShiftScheduleDate;
        $data['to_employee_id'] = $toEmployeeId;
        $data['to_shift_schedule_id'] = $shiftScheduleTo->id;
        $data['to_shift_schedule_code'] = $shiftScheduleTo->shift->code;
        $data['to_shift_schedule_name'] = $shiftScheduleTo->shift->name;
        $data['to_shift_schedule_time_from'] = $shiftScheduleTo->time_in;
        $data['to_shift_schedule_time_end'] = $shiftScheduleTo->time_out;

        // exchange
        $exchangeEmployeeId = $data['exchange_employee_id'];
        $exchangeDate = $data['exchange_date'];
        $shiftScheduleExchange = $this->shiftScheduleService->shiftScheduleEmployeeDate($exchangeEmployeeId, $exchangeDate);
        $data['exchange_date'] = $exchangeDate;
        $data['exchange_employee_id'] = $exchangeEmployeeId;
        $data['exchange_shift_schedule_id'] = $shiftScheduleExchange->id;
        $data['exchange_shift_schedule_code'] = $shiftScheduleExchange->shift->code;
        $data['exchange_shift_schedule_name'] = $shiftScheduleExchange->shift->name;
        $data['exchange_shift_schedule_time_from'] = $shiftScheduleExchange->time_in;
        $data['exchange_shift_schedule_time_end'] = $shiftScheduleExchange->time_out;
        return $shiftScheduleExchange;

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
