<?php

namespace App\Http\Controllers\API\Career;

use App\Traits\ResponseAPI;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use App\Notifications\SendPasswordResetLink;
use Illuminate\Support\Facades\{Auth, Validator};
use App\Models\{CandidateAccount, EmailVerificationToken};
use App\Services\EmailVerification\EmailVerificationServiceInterface;

class AuthCareerController extends Controller
{
    use ResponseAPI;
    private $emailVerificationService;

    public function __construct(EmailVerificationServiceInterface $emailVerificationService)
    {
        $this->middleware('auth:candidate', ['except' => ['login', 'register', 'verifyEmail', 'resend']]);
        $this->emailVerificationService = $emailVerificationService;
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|max:150',
            'password' => 'required|string|min:6',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation Errors!',
                'success' => false,
                'code' => 422,
                'data' => $validator->errors()
            ], 422);
        }
        $credentials = $validator->validated();
        if (!Auth::guard('candidate')->attempt($credentials)) {
            return response()->json([
                'message' => 'Unauthorized!',
                'success' => false,
                'code' => 422,
                'data' => ['email' => ['Email or Password Invalid!']]
            ], 422);
        }
        $candidate = Auth::guard('candidate')->user();
        if ($candidate && $candidate->active !== 1) {
            return response()->json([
                'message' => 'Account not active!',
                'success' => false,
                'code' => 422,
                'data' => ['email' => ['Account not active!']]
            ], 422);
        }
        $token = Auth::guard('candidate')->getToken();
        return $this->createNewToken(strval($token));
    }

    public function userProfile()
    {
        $user = Auth::guard('candidate')->user();
        $userWithCandidate = CandidateAccount::with([
            'candidate',
            'candidate.identityType:id,name',
            'candidate.identityType:id,name',
            'candidate.sex:id,name',
            'candidate.maritalStatus:id,name',
            'candidate.religion:id,name',
            'candidate.ethnic:id,name',
            'candidate.emergencyContact:id,candidate_id,relationship_id,name,sex_id,address,phone_number',
            'candidate.emergencyContact.relationship:id,name',
            'candidate.emergencyContact.sex:id,name',
            'candidate.familyInformation:id,candidate_id,relationship_id,name,sex_id,birth_place,birth_date,education_id,job_id',
            'candidate.familyInformation.relationship:id,name',
            'candidate.familyInformation.sex:id,name',
            'candidate.familyInformation.education:id,name',
            'candidate.familyInformation.job:id,name',
            'candidate.educationBackground:id,candidate_id,education_id,institution_name,major,started_year,ended_year,final_score',
            'candidate.educationBackground.education:id,name',
            'candidate.organizationExperience:id,candidate_id,organization_name,position,year,description',
            'candidate.expertiseCertification:id,candidate_id,type_of_expertise,qualification_type,given_by,year,description',
            'candidate.coursesTraining:id,candidate_id,type_of_training,level,organized_by,year,description',
            'candidate.foreignLanguage:id,candidate_id,language,speaking_ability_level,writing_ability_level',
            'candidate.workExperience:id,candidate_id,company,position,location,from_date,to_date,job_description,reason_for_resignation',
            'candidate.hospitalConnection:id,candidate_id,relationship_id,name,department_id,position_id',
            'candidate.hospitalConnection.relationship:id,name',
            'candidate.hospitalConnection.department:id,name',
            'candidate.hospitalConnection.position:id,name',
            'candidate.selfPerspective:id,candidate_id,self_perspective,strengths,weaknesses,successes,failures,career_overview,future_expectations',
            'candidate.additionalInformation:id,candidate_id,physical_condition,severe_diseases,hospitalizations,last_medical_checkup',
        ])
            ->find($user->id);
        return $this->success('User Profile Successfully Retrieved', $userWithCandidate);
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:150',
            'username' => 'required|string|max:150|unique:candidate_accounts,username',
            'email' => 'required|string|email|max:150|unique:candidate_accounts,email',
            'password' => 'required|string|confirmed|min:6',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Unauthorized!',
                'success' => false,
                'code' => 422,
                'data' => $validator->errors()
            ], 422);
        }
        $user = CandidateAccount::create(array_merge(
            $validator->validated(),
            [
                'password' => bcrypt($request->password),
                'email_verified_at' => null, // Mark as unverified
            ]
        ));
        $type = 'Register';
        $this->emailVerificationService->sendVerificationLink($user, $type);
        return $this->success('User Successfully Registered. Verification email sent.', $user);
    }

    public function verifyEmail($token, $email)
    {
        $emailVerificationToken = EmailVerificationToken::where('token', $token)
            ->where('email', $email)
            ->first();
        if (!$emailVerificationToken) {
            return response()->json([
                'message' => 'Invalid Token!',
                'success' => false,
                'code' => 422,
                'data' => []
            ], 400);
        }
        $candidate = CandidateAccount::where('email', $emailVerificationToken->email)->first();
        $candidate->email_verified_at = now();
        $candidate->active = 1;
        $candidate->save();
        return response()->json([
            'message' => 'Email Successfully Verified.!',
            'success' => true,
            'code' => 200,
            'data' => $candidate
        ], 200);
    }

    public function resendVerifyEmail(Request $request)
    {
        $candidate = CandidateAccount::where('email', $request->input('email'))->first();
        if (!$candidate) {
            return response()->json([
                'message' => 'Email not found, please check your email!',
                'success' => false,
                'code' => 422,
                'data' => []
            ], 422);
        }
        if ($candidate->email_verified_at !== null) {
            return response()->json([
                'message' => 'Email is already verified, please login!',
                'success' => false,
                'code' => 422,
                'data' => []
            ], 422);
        }
        $type = 'Resend Email Verification';
        $this->emailVerificationService->sendVerificationLink($candidate, $type);
        return response()->json([
            'message' => 'Verification email resend, check your email!',
            'success' => true,
            'code' => 200,
            'data' => $candidate
        ], 200);
    }

    public function updatePassword(Request $request)
    {
        $user = Auth::guard('candidate')->user();
        $validator = Validator::make($request->all(), [
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:6|different:current_password',
            'confirm_password' => 'required|string|same:new_password',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Unauthorized!',
                'success' => false,
                'code' => 422,
                'data' => $validator->errors()
            ], 422);
        }
        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json([
                'message' => 'Current password is incorrect.',
                'success' => false,
                'code' => 422,
                'data' => ['password' => ['Current password is incorrect.']]
            ], 422);
        }
        $user->password = Hash::make($request->new_password);
        $user->save();
        return response()->json([
            'message' => 'Password updated successfully.',
            'success' => true,
            'code' => 200,
            'data' => $user
        ], 200);
    }

    // public function forgotPassword(Request $request)
    // {
    //     $validator = Validator::make($request->all(), [
    //         'email' => 'required|email|exists:candidate_accounts,email',
    //     ]);
    //     if ($validator->fails()) {
    //         return response()->json(['error' => $validator->errors()], 422);
    //     }
    //     $user = CandidateAccount::where('email', $request->email)->first();
    //     if (!$user) {
    //         return response()->json(['error' => 'We can\'t find a user with that email address.'], 404);
    //     }
    //     $resetLink = Password::broker('candidate_accounts')->sendResetLink($request->only('email'));
    //     $user->notify((new SendPasswordResetLink($resetLink))->onQueue('emails'));
    //     return response()->json(['message' => 'Password reset link queued for sending.'], 200);
    // }

    // public function resetPassword(Request $request)
    // {
    //     $validator = Validator::make($request->all(), [
    //         'email' => 'required|email|exists:candidate_accounts,email',
    //         'token' => 'required|string',
    //         'password' => 'required|string|confirmed|min:8',
    //     ]);
    //     if ($validator->fails()) {
    //         return response()->json([
    //             'message' => 'Validation Errors!',
    //             'success' => false,
    //             'code' => 422,
    //             'data' => $validator->errors()
    //         ], 422);
    //     }
    //     $credentials = $request->only('email', 'password', 'password_confirmation', 'token');
    //     $status = Password::reset($credentials, function ($user, $password) {
    //         $user->password = bcrypt($password);
    //         $user->save();
    //     });
    //     if ($status === Password::PASSWORD_RESET) {
    //         return response()->json(['message' => 'Password reset successfully.'], 200);
    //     } else {
    //         return response()->json(['error' => 'Unable to reset password.'], 500);
    //     }
    // }


    public function logout()
    {
        $user = auth()->user();
        auth()->logout();
        return $this->success('User Successfully Signed Out, Token Revoked!', []);
    }

    public function refresh()
    {
        return $this->createNewToken(auth()->refresh());
    }

    protected function createNewToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 1,
            'user' => Auth::guard('candidate')->user()
        ]);
    }
}
