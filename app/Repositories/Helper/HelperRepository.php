<?php

namespace App\Repositories\Helper;

use App\Models\Helper;
use App\Repositories\Helper\HelperRepositoryInterface;


class HelperRepository implements HelperRepositoryInterface
{
    private $model;
    private $field =
    [
        'id',
        'employment_number',
        'telephone_invitation_interview',
        'email_invitation_interview'
    ];

    public function __construct(Helper $model)
    {
        $this->model = $model;
    }
    public function show()
    {
        $helper = $this->model->first($this->field);
        return $helper ? $helper : $helper = null;
    }

    public function update($id, $data)
    {
        $helper = $this->model->find($id);
        if ($helper) {
            $helper->update($data);
            return $helper;
        }
        return null;
    }
}
