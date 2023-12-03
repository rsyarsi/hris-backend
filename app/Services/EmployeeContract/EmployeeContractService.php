<?php
namespace App\Services\EmployeeContract;

use App\Services\EmployeeContract\EmployeeContractServiceInterface;
use App\Repositories\EmployeeContract\EmployeeContractRepositoryInterface;
use App\Services\ContractType\ContractTypeServiceInterface;

class EmployeeContractService implements EmployeeContractServiceInterface
{
    private $repository;
    private $contractTypeService;

    public function __construct(EmployeeContractRepositoryInterface $repository, ContractTypeServiceInterface $contractTypeService)
    {
        $this->repository = $repository;
        $this->contractTypeService = $contractTypeService;
    }

    public function index($perPage, $search)
    {
        return $this->repository->index($perPage, $search);
    }

    public function store(array $data)
    {
        $contractType = $this->contractTypeService->show($data['contract_type_id']);
        $data['status_employee'] = $contractType->name;

        $transactionNumber = $this->generateNextTransactionNumber();
        $data['transaction_number'] = $transactionNumber;
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

    public function getLastTransactionNumber()
    {
        return $this->repository->getLastTransactionNumber();
    }

    public function generateNextTransactionNumber()
    {
        $lastTransaction = $this->getLastTransactionNumber();
        if ($lastTransaction) {
            // Extract the last part of the transaction number (e.g., "0006/X/2023" -> "0006")
            $lastNumber = explode('/', $lastTransaction->transaction_number)[0];
            // Convert it to an integer, increment, and pad with zeros to ensure a width of 4 characters
            $nextNumber = str_pad((int) $lastNumber + 1, 4, '0', STR_PAD_LEFT);
        } else {
            // If no previous transactions exist, start with '0001'
            $nextNumber = '0001';
        }
        // Get the current year
        $nowYear = now()->format('Y');
        // Create the next transaction number
        $transactionNumber = "{$nextNumber}/X/{$nowYear}";
        return $transactionNumber;
    }
}
