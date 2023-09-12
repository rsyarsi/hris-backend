<?php
namespace App\Services\EmployeeSkill;

use Illuminate\Support\Str;
use App\Services\EmployeeSkill\EmployeeSkillServiceInterface;
use App\Repositories\EmployeeSkill\EmployeeSkillRepositoryInterface;

class EmployeeSkillService implements EmployeeSkillServiceInterface
{
    private $repository;

    public function __construct(EmployeeSkillRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function index($perPage, $search)
    {
        return $this->repository->index($perPage, $search);
    }

    public function store(array $data)
    {
        $data['level'] = $this->formatTextTitle($data['level']);
        return $this->repository->store($data);
    }

    public function show($id)
    {
        return $this->repository->show($id);
    }

    public function update($id, $data)
    {
        $data['level'] = $this->formatTextTitle($data['level']);
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
