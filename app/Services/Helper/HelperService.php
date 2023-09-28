<?php
namespace App\Services\Helper;

use App\Services\Helper\HelperServiceInterface;
use App\Repositories\Helper\HelperRepositoryInterface;

class HelperService implements HelperServiceInterface
{
    private $repository;

    public function __construct(HelperRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function show()
    {
        return $this->repository->show();
    }

    public function update($id, $data)
    {
        return $this->repository->update($id, $data);
    }
}
