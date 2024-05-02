<?php

namespace App\Repositories\User;

use App\Models\User;
use App\Models\Employee;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Repositories\User\UserRepositoryInterface;
use App\Services\Employee\EmployeeServiceInterface;

class UserRepository implements UserRepositoryInterface
{
    private $model;
    private $employeeService;
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
        'staff',
        'active',
    ];

    public function __construct(User $model, EmployeeServiceInterface $employeeService)
    {
        $this->model = $model;
        $this->employeeService = $employeeService;
    }

    public function index($perPage, $search = null, $active = true)
    {
        $status = $active == true ? 1 : 0;
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
            $query->whereRaw('LOWER(name) LIKE ?', ["%" . strtolower($search) . "%"])
                ->orWhere('email', 'LIKE', "%{$search}%");
        }
        // ->where('active', $status)
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

    public function updatePasswordMobile($data)
    {
        // Start the database transaction
        DB::beginTransaction();

        try {
            $employeeId = $data['employee_id'];
            $employee = $this->employeeService->show($employeeId);
            $user = User::where('id', $employee->user_id)->first();

            // Validate the old password
            if (!Hash::check($data['old_password'], $user->password)) {
                return [
                    'message' => 'Old password is incorrect!',
                    'success' => false,
                    'code' => 200,
                    'data' => 'Old password is incorrect!',
                ];
            }

            // Validate the new password (you can add more validation rules if needed)
            if (strlen($data['new_password']) < 6) {
                return [
                    'message' => 'New password must be at least 6 characters long!',
                    'success' => false,
                    'code' => 200,
                    'data' => 'New password must be at least 6 characters long!',
                ];
            }

            // Update the user's password
            $user->password = Hash::make($data['new_password']);
            $user->save();
            // Commit the transaction
            DB::commit();
            return [
                'message' => 'Password updated successfully!',
                'success' => true,
                'code' => 200,
                'data' => 'Password updated successfully!',
            ];
        } catch (\Exception $e) {
            // Rollback the transaction in case of any exception
            DB::rollback();

            // Handle the exception (log, throw, etc.)
            // For now, we'll just rethrow the exception
            throw $e;
        }
    }

    public function termConditionVerified($data)
    {
        $employeeId = $data['employee_id'];
        $employee = Employee::where('id', $employeeId)->first();
        $user = User::where('id', $employee->user_id)->first();
        // Update the user's verified after read the term & condition
        $user->verified = 1;
        $user->save();
        return [
            'message' => 'Account verified successfully!',
            'success' => true,
            'code' => 200,
            'data' => 'Account verified successfully!',
        ];
    }
}
