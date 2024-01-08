<?php
namespace App\Services\PromotionDemotion;

use App\Services\PromotionDemotion\PromotionDemotionServiceInterface;
use App\Repositories\PromotionDemotion\PromotionDemotionRepositoryInterface;

class PromotionDemotionService implements PromotionDemotionServiceInterface
{
    private $repository;

    public function __construct(PromotionDemotionRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function index($perPage, $search)
    {
        return $this->repository->index($perPage, $search);
    }

    public function promotionDemotionEmployee($perPage, $search)
    {
        return $this->repository->promotionDemotionEmployee($perPage, $search);
    }

    public function store(array $data)
    {
        $data['user_created_id'] = auth()->id();
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
}
