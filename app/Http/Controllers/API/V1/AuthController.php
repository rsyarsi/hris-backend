<?php

namespace App\Http\Controllers\API\V1;

use App\Models\User;
use App\Traits\ResponseAPI;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;


class AuthController extends Controller
{
    use ResponseAPI;

    private $field =
    [
        'id',
        'name',
        'email',
    ];
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct() {
        $this->middleware('auth:api', ['except' => ['login', 'loginMobileApps', 'register']]);
    }
    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request){
        $validator = Validator::make($request->all(), [
            'username' => 'required|string',
            'password' => 'required|string|min:6',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        if (! $token = auth()->attempt($validator->validated())) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        return $this->createNewToken($token);
    }

    public function loginMobileApps(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|string|max:255',
            'password' => 'required|string|min:6',
            'user_device_id' => 'nullable|string',
            'firebase_id' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $credentials = $validator->validated();

        if (!$token = auth()->attempt([
                                        'username' => $credentials['username'],
                                        'password' => $credentials['password']]
                                    ))
        {
            return response()->json([
                'message' => 'Username atau password salah, Login gagal!',
                'success' => false,
                'code' => 201,
                'data' => []
            ]);
        }

        $user = auth()->user();

        if ($user->user_device_id == null) {
            // Store or update device information
            $user->updateDeviceInfo(
                $request->input('user_device_id'),
                $request->input('firebase_id'),
            );
        }

        if ($user->user_device_id !== $request->input('user_device_id')) {
            Auth::logout();
            return response()->json([
                'message' => 'User telah login di perangkat lain, silahkan hubungi HRD!',
                'success' => false,
                'code' => 401,
                'data' => []
            ]);
        }

        // $this->createNewToken($token);
        return response()->json([
            'message' => 'Login From Mobile App Berhasil!',
            'success' => 'true',
            'code' => 200,
            'data' => [
                'name' => $user->name,
                'email' => $user->email,
                'employee_id' => $user->username,
                'UuidEmployment' => $user->employee->id ?? '01hgtqbn8wa1a1mkdqfn70dqs1',
                'roles' => $user->roles ?? '',
            ],
        ]);
    }

    private function isUserLoggedInFromAnotherDevice($user, $credentials)
    {
        return $user->user_device_id !== null ||
                $user->firebase_id !== null;
    }

    /**
     * Register a User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request) {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|between:2,100',
            'email' => 'required|string|email|max:100|unique:users',
            'password' => 'required|string|confirmed|min:6',
        ]);
        if($validator->fails()){
            // return response()->json($validator->errors());
            return $this->error($validator->errors(), 422);
        }
        $user = User::create(array_merge(
                    $validator->validated(),
                    ['password' => bcrypt($request->password)]
                ));
        return $this->success('User Successfully Registered', $user);
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout() {
        auth()->logout();
        return $this->success('User Successfully Signed Out, Token Revoked!', []);
    }
    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh() {
        return $this->createNewToken(auth()->refresh());
    }
    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function userProfile() {
        $user = auth()->user();
        $user->load([
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
                    'manager_id'
                )->with([
                    'supervisor:id,name,email',
                    'manager:id,name,email',
                ]);
            },
            'roles' => function ($query) {
                $query->select('id', 'name', 'guard_name')
                    ->with('permissions:id,name,guard_name');
            }
        ]);

        if (!$user->employee) {
            // Handle the case where the user does not have an associated Employee record
            return $this->success('User Profile Successfully Retrieved, The user dont have relation with employee', $user);
        }
        return $this->success('User Profile Successfully Retrieved', $user);
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function createNewToken($token){
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 1,
            'user' => auth()->user()
        ]);
    }
}
