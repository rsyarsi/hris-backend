<?php
namespace App\Services\EmployeeOrganization;

use Illuminate\Support\Str;
use App\Services\EmployeeOrganization\EmployeeOrganizationServiceInterface;
use App\Repositories\EmployeeOrganization\EmployeeOrganizationRepositoryInterface;

class EmployeeOrganizationService implements EmployeeOrganizationServiceInterface
{
    private $repository;

    public function __construct(EmployeeOrganizationRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function index($perPage, $search)
    {
        return $this->repository->index($perPage, $search);
    }

    public function store(array $data)
    {
        $data['institution_name'] = $this->formatTextTitle($data['institution_name']);
        $data['position'] = $this->formatTextTitle($data['position']);
        return $this->repository->store($data);
    }

    public function show($id)
    {
        return $this->repository->show($id);
    }

    public function update($id, $data)
    {
        $data['institution_name'] = $this->formatTextTitle($data['institution_name']);
        $data['position'] = $this->formatTextTitle($data['position']);
        return $this->repository->update($id, $data);
    }

    public function destroy($id)
    {
        return $this->repository->destroy($id);
    }

    public function formatTextTitle($data)
    {
        return Str::upper($data);
    }

}
