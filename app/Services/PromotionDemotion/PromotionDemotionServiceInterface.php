<?php
namespace App\Services\PromotionDemotion;

interface PromotionDemotionServiceInterface
{
    public function index($perPage, $search);
    public function promotionDemotionEmployee($perPage, $search);
    public function store(array $data);
    public function show($id);
    public function update($id, array $data);
    public function destroy($id);
}
