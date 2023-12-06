<?php

namespace App\Repositories\User;

use App\Models\User;
use App\Repositories\User\UserRepositoryInterface;


class UserRepository implements UserRepositoryInterface
{
    private $model;
    private $field = [
        'id', 
        'name', 
        'email', 
        'user_device_id',
        'firebase_id',
        'imei',
        'ip',
        'username',
        'administrator', 
        'hrd', 
        'manager', 
        'supervisor', 
        'pegawai', 
        'kabag', 
        'staf'
    ];

    public function __construct(User $model)
    {
        $this->model = $model;
    }

    public function index($perPage, $search = null)
    {
        $query = $this->model
                    ->with([
                        'employee' => function ($query) {
                            $query->select(
                                'id',
                                'name',
                                'legal_identity_type_id',
                                'legal_identity_number',
                                'family_card_number',
                                'sex_id',
                                'birth_place',
                                'birth_date',
                                'marital_status_id',
                                'religion_id',
                                'blood_type',
                                'tax_identify_number',
                                'email',
                                'phone_number',
                                'phone_number_country',
                                'legal_address',
                                'legal_postal_code',
                                'legal_province_id',
                                'legal_city_id',
                                'legal_district_id',
                                'legal_village_id',
                                'legal_home_phone_number',
                                'legal_home_phone_country',
                                'current_address',
                                'current_postal_code',
                                'current_province_id',
                                'current_city_id',
                                'current_district_id',
                                'current_village_id',
                                'current_home_phone_number',
                                'current_home_phone_country',
                                'status_employment_id',
                                'position_id',
                                'unit_id',
                                'department_id',
                                'started_at',
                                'employment_number',
                                'resigned_at',
                                'user_id',
                                'supervisor_id',
                                'manager_id',
                            );
                        },
                        'roles' => function ($query) {
                            $query->select('id', 'name', 'guard_name')
                                    ->with('permissions:id,name,guard_name');
                        }
                    ])
                    ->select($this->field);
        if ($search !== null) {
            $query->whereRaw('LOWER(name) LIKE ?', ["%".strtolower($search)."%"]);
        }
        return $query->orderBy('id', 'ASC')->paginate($perPage);
    }

    public function store(array $data)
    {
        return $this->model->create($data);
    }

    public function show($id)
    {
        $user = $this->model
                    ->with([
                        'employee' => function ($query) {
                            $query->select(
                                'id',
                                'name',
                                'legal_identity_type_id',
                                'legal_identity_number',
                                'family_card_number',
                                'sex_id',
                                'birth_place',
                                'birth_date',
                                'marital_status_id',
                                'religion_id',
                                'blood_type',
                                'tax_identify_number',
                                'email',
                                'phone_number',
                                'phone_number_country',
                                'legal_address',
                                'legal_postal_code',
                                'legal_province_id',
                                'legal_city_id',
                                'legal_district_id',
                                'legal_village_id',
                                'legal_home_phone_number',
                                'legal_home_phone_country',
                                'current_address',
                                'current_postal_code',
                                'current_province_id',
                                'current_city_id',
                                'current_district_id',
                                'current_village_id',
                                'current_home_phone_number',
                                'current_home_phone_country',
                                'status_employment_id',
                                'position_id',
                                'unit_id',
                                'department_id',
                                'started_at',
                                'employment_number',
                                'resigned_at',
                                'user_id'
                            );
                        },
                        'roles' => function ($query) {
                            $query->select('id', 'name', 'guard_name')
                                    ->with('permissions:id,name,guard_name');
                        }
                    ])
                    ->where('id', $id)
                    ->first($this->field);
        return $user ? $user : $user = null;
    }

    public function update($id, $data)
    {
        $user = $this->model->find($id);
        if ($user) {
            $user->update($data);
            return $user;
        }
        return null;
    }

    public function destroy($id)
    {
        $user = $this->model->find($id);
        if ($user) {
            $user->delete();
            return $user;
        }
        return null;
    }
}
