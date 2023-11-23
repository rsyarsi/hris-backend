<?php
namespace App\Services\LogFingerTemp;

use Carbon\Carbon;
use App\Services\Employee\EmployeeServiceInterface;
use App\Services\LogFingerTemp\LogFingerTempServiceInterface;
use App\Repositories\LogFingerTemp\LogFingerTempRepositoryInterface;

class LogFingerTempService implements LogFingerTempServiceInterface
{
    private $repository;
    private $employeeRepository;

    public function __construct(LogFingerTempRepositoryInterface $repository, EmployeeServiceInterface $employeeRepository)
    {
        $this->repository = $repository;
        $this->employeeRepository = $employeeRepository;
    }

    public function index($perPage, $search, $startDate, $endDate)
    {
        return $this->repository->index($perPage, $search, $startDate, $endDate);
    }

    public function store(array $data)
    {
        $pin = $data['nopin'];
        $employee = $this->employeeRepository->employeeWherePin($pin);
        $datetime = Carbon::parse($data['datetime']);
        $data['date_log'] = $datetime->toDateString();
        $data['employee_id'] = $employee->id;
        $data['function'] = $data['function'];
        $data['snfinger'] = 22;
        $data['absen'] = $data['datetime'];
        $data['manual'] = 0;
        $data['user_manual'] = 1;
        $data['manual_date'] = $datetime->toDateString();
        $data['pin'] = $pin;
        return $this->repository->store($data);
    }

    public function show($id)
    {
        return $this->repository->show($id);
    }

    public function update($id, $data)
    {
        return $this->repository->update($id, $data);
    }

    public function destroy($id)
    {
        return $this->repository->destroy($id);
    }

    public function logFingerTempUser($perPage, $startDate, $endDate)
    {
        return $this->repository->logFingerTempUser($perPage, $startDate, $endDate);
    }
}
