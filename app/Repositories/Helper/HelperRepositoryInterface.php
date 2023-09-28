<?php
namespace App\Repositories\Helper;

Interface HelperRepositoryInterface{
    public function show();
    public function update($id, array $data);
}
