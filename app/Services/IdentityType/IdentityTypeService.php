<?php
namespace App\Services\IdentityType;

use Illuminate\Support\Str;
use App\Services\IdentityType\IdentityTypeServiceInterface;
use App\Repositories\IdentityType\IdentityTypeRepositoryInterface;

class IdentityTypeService implements IdentityTypeServiceInterface
{
    private $repository;

    public function __construct(IdentityTypeRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function index()
    {
        return $this->repository->index();
    }

    public function store(array $data)
    {
        $data['name'] = $this->formatTextTitle($data['name']);
        return $this->repository->store($data);
    }

    public function show($id)
    {
        return $this->repository->show($id);
    }

    public function update($id, $data)
    {
        $data['name'] = $this->formatTextTitle($data['name']);
        return $this->repository->update($id, $data);
    }

    public function destroy($id)
    {
        return $this->repository->destroy($id);
    }

    public function formatTextTitle($data)
    {
        return Str::title($data);
    }

}
