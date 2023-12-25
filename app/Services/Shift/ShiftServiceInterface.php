<?php
namespace App\Services\Shift;

interface ShiftServiceInterface
{
    public function index($perPage, $search, $groupShiftId, $active);
    public function store(array $data);
    public function show($id);
    public function update($id, array $data);
    public function destroy($id);
}
