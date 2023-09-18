<?php

namespace App\Http\Controllers\API\V1;

use App\Traits\ResponseAPI;
use Illuminate\Http\Request;
use App\Http\Requests\UserRequest;
use App\Http\Controllers\Controller;
use App\Services\User\UserServiceInterface;

class UserController extends Controller
{
    use ResponseAPI;

    private $userService;

    public function __construct(UserServiceInterface $userService)
    {
        $this->middleware('auth:api');
        $this->userService = $userService;
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
            return $this->success('User created successfully', $user, 201);
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
}
