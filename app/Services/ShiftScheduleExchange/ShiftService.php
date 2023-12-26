<?php
namespace App\Services\ShiftScheduleExchange;

use App\Services\ShiftScheduleExchange\ShiftScheduleExchangeServiceInterface;
use App\Repositories\ShiftScheduleExchange\ShiftScheduleExchangeRepositoryInterface;

class ShiftScheduleExchangeService implements ShiftScheduleExchangeServiceInterface
{
    private $repository;

    public function __construct(ShiftScheduleExchangeRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function index($perPage, $search, $startDate, $endDate)
    {
        return $this->repository->index($perPage, $search, $startDate, $endDate);
    }

    public function store(array $data)
    {
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
