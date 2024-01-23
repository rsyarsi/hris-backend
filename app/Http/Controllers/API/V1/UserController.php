<?php

namespace App\Http\Controllers\API\V1;

use App\Traits\ResponseAPI;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Services\Role\RoleServiceInterface;
use App\Services\User\UserServiceInterface;
use App\Services\Employee\EmployeeServiceInterface;
use App\Services\Permission\PermissionServiceInterface;
use App\Http\Requests\{UserRequest, GivePermissionRequest, AssignRoleRequest, UpdatePasswordMobileRequest};

class UserController extends Controller
{
    use ResponseAPI;

    private $userService;
    private $roleService;
    private $permissionService;
    private $employeeService;

    public function __construct(
        UserServiceInterface $userService,
        RoleServiceInterface $roleService,
        PermissionServiceInterface $permissionService,
        EmployeeServiceInterface $employeeService
        )
    {
        $this->middleware('auth:api');
        $this->userService = $userService;
        $this->roleService = $roleService;
        $this->permissionService = $permissionService;
        $this->employeeService = $employeeService;
    }

    public function index(Request $request)
    {
        try {
            $perPage = $request->input('per_page', 10);
            $search = $request->input('search');
            $users = $this->userService->index($perPage, $search);
            return $this->success('Users retrieved successfully', $users);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function store(UserRequest $request)
    {
        try {
            $data = $request->validated();
            $user = $this->userService->store($data);

            // update user_id in the table employee
            $employeeId = $request->input('employee_id');
            $dataEmployee = $user->id;
            $this->employeeService->updateUserId($employeeId, $dataEmployee);

            // asign role to user
            $roles = $request->input('role');
            if (!$user) {
                return $this->error('User not exists', 404);
            }
            if ($roles) {
                foreach ($roles as $role) {
                    if (!$user->hasRole($role)) {
                        $user->assignRole($role);
                    } else {
                        return $this->error('Role Exists', 404);
                    }
                }
            }
            return $this->success('User created successfully', $user, 201);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function updatePasswordMobile(Request $request)
    {
        try {
            $rules = [
                'employee_id' => 'required|exists:employees,id',
                'old_password' => 'required|string',
                'new_password' => 'required|string|min:6',
            ];
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json([
                    'message' => 'Validation Error',
                    'success' => false,
                    'code' => 200, // Use a more appropriate HTTP status code
                    'data' => $validator->errors(),
                ], 200);
            }
            $user = $this->userService->updatePasswordMobile($request->all());
            return response()->json([
                'message' => $user['message'],
                'success' => $user['success'],
                'code' => $user['code'],
                'data' => $user['data']
            ], $user['code']);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function show($id)
    {
        try {
            $user = $this->userService->show($id);
            if (!$user) {
                return $this->error('User not found', 404);
            }
            return $this->success('User retrieved successfully', $user);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function update(UserRequest $request, $id)
    {
        try {
            $data = $request->validated();
            $user = $this->userService->update($id, $data);
            if (!$user) {
                return $this->error('User not found', 404);
            }
            // update user_id in the table employee
            $employeeId = $request->input('employee_id');
            $dataEmployee = $user->id;
            $this->employeeService->updateUserId($employeeId, $dataEmployee);
            // asign role to user
            $roles = $request->input('role');
            if ($roles) {
                $user->syncRoles($roles); // Use syncRoles to sync the roles
            }
            return $this->success('User updated successfully', $user, 201);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function destroy($id)
    {
        try {
            $user = $this->userService->destroy($id);
            if (!$user) {
                return $this->error('User not found', 404);
            }
            return $this->success('User deleted successfully, id : '.$user->id, []);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function assignRole(AssignRoleRequest $request, $id)
    {
        try {
            $roles = $request->input('role');
            $user = $this->userService->show($id);
            if (!$user) {
                return $this->error('User not exists', 404);
            }
            foreach ($roles as $role) {
                if (!$user->hasRole($role)) {
                    $user->assignRole($role);
                } else {
                    return $this->error('Role Exists', 404);
                }
            }
            return $this->success('Role assigned in user '.$user->name, []);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function removeRole($id, $role)
    {
        try {
            // Check if the user exists
            $user = $this->userService->show($id);
            if (!$user) {
                return $this->error('User not exists', 404);
            }

            // Check if the role exists
            $role = $this->roleService->show($role);
            if (!$role) {
                return $this->error('Role not exists', 404);
            }

            if ($user->hasRole($role)) {
                $user->removeRole($role);
                return $this->success('Role Removed', []);
            }
            return $this->error('Role not exists', 404);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function givePermission(GivePermissionRequest $request, $id)
    {
        try {
            $permissions = $request->input('permission');
            $user = $this->userService->show($id);
            if (!$user) {
                return $this->error('user not exists', 404);
            }
            foreach ($permissions as $permission) {
                if (!$user->hasPermissionTo($permission)) {
                    $user->givePermissionTo($permission);
                } else {
                    return $this->error('Permission Exists', 404);
                }
            }
            return $this->success('Permission added in user '.$user->name, []);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function revokePermission($id, $permission)
    {
        try {
            // Check if the User exists
            $user = $this->userService->show($id);
            if (!$user) {
                return $this->error('User not exists', 404);
            }

            // Check if the permission exists
            $permission = $this->permissionService->show($permission);
            if (!$permission) {
                return $this->error('Permission not exists', 404);
            }

            if ($user->hasPermissionTo($permission)) {
                $user->revokePermissionTo($permission->name);
                return $this->success('Permission revoke from user '.$user->name, []);
            }
            return $this->error('Permission not exists', 404);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }
}
