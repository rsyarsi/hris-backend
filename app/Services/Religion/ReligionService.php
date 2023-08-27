<?php
namespace App\Services\Religion;

use Illuminate\Support\Str;
use App\Services\Religion\ReligionServiceInterface;
use App\Repositories\Religion\ReligionRepositoryInterface;

class ReligionService implements ReligionServiceInterface
{
    private $repository;

    public function __construct(ReligionRepositoryInterface $repository)
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
