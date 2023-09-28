<?php
namespace App\Services\Helper;

interface HelperServiceInterface
{
    public function show();
    public function update($id, array $data);
}
