<?php
namespace App\Services\LegalityType;

use Illuminate\Support\Str;
use App\Services\LegalityType\LegalityTypeServiceInterface;
use App\Repositories\LegalityType\LegalityTypeRepositoryInterface;

class LegalityTypeService implements LegalityTypeServiceInterface
{
    private $repository;

    public function __construct(LegalityTypeRepositoryInterface $repository)
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
