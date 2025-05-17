<?php

namespace App\Http\Controllers\Api\Customer;

use App\Events\CustomerLoggedIn;
use App\Events\CustomerLoggedOut;
use App\Helpers\OtpHelper;
use App\Helpers\RedisHelper;
use App\Http\Controllers\Api\BaseApiController;
use App\Http\Requests\ChangePasswordRequest;
use App\Http\Requests\Customer\ChangePasswordFirstLoginRequest;
use App\Http\Requests\Customer\ForgotPasswordRequest;
use App\Http\Requests\Customer\RegisterCustomerRequest;
use App\Http\Requests\Customer\ResetPasswordRequest;
use App\Http\Requests\Customer\ValidateOtpRequest;
use App\Http\Requests\LoginRequest;
use App\Jobs\SendOtpJob;
use App\Jobs\SendResetPasswordMailJob;
use App\Models\Customer;
use App\Models\Otp;
use App\Repositories\CustomerRepository;
use App\Services\AuthService;
use App\Services\TempTokenService;
use App\Transformers\CustomerTransformer;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Password;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends BaseApiController
{
    protected CustomerRepository $customerRepository;
    public function __construct(CustomerRepository $customerRepository)
    {
        $this->customerRepository = $customerRepository;
    }
    public function preLogin(LoginRequest $request): JsonResponse
    {
        try {
            $credentials = $request->only([
                'vn_id',
                'password'
            ]);
            if (!AuthService::validateCredentials($credentials)) {
                return $this->responseErrors(400, 'Sai tài khoản hoặc mật khẩu');
            }
            $user = Customer::where('vn_id', $credentials['vn_id'])->orderBy('vn_id_issue_date', 'desc')->first();
            $userEmail = $user->email;
            if (!$userEmail) {
                return $this->responseErrors(400, 'Tài khoản không có email, vui lòng liên hệ quản trị viên');
            }
            $latestOtp = Otp::where([
                'receiver_address' => $userEmail,
                'sent_through' => 'email',
                'verified_at' => null
            ])->latest()->first();
            if ($latestOtp) {
                $timeBetweenEachRequest = config('otp.time_between_requests');
                $periodTime = Carbon::now()->diffInRealSeconds($latestOtp->created_at);
                if ($periodTime < $timeBetweenEachRequest) {
                    $periodTime = $timeBetweenEachRequest-$periodTime;
                    return $this->responseErrors(400, "Vui lòng đợi $periodTime giây trước khi gửi yêu cầu tiếp theo.");
                }
            }
            if (!$tempToken = TempTokenService::create($credentials['vn_id'], config('otp.temp_token_expire_time'))) {
                return $this->responseErrors();
            }
            $otpExpireTime = (int)config('otp.expire_time', 5)*60;
            $response = [
                'message' => 'Mã OTP đã được gửi đến email của bạn.',
                'temp_token' => $tempToken,
                'email' => $userEmail,
                'expire_time' => $otpExpireTime
            ];
            dispatch(new SendOtpJob($userEmail));
            return $this->responseSuccess($response);
        } catch (\Exception $e) {
            Log::error('Login error: ' . $e->getMessage());
            return $this->responseInternalServerError();
        }
    }

    public function logout(Request $request): JsonResponse
    {
        try {
            $customer = auth('customer')->user();
            auth('customer')->logout();
            event(new CustomerLoggedOut($customer, $request->bearerToken()));
            return $this->responseSuccess();
        } catch (\Exception $e) {
            Log::error('Logout error: ' . $e->getMessage());
            return $this->responseInternalServerError();
        }
    }

    /**
     * @param RegisterCustomerRequest $request
     * @return JsonResponse
     */
    public function register(RegisterCustomerRequest $request)
    {
        try {
            $data = $request->only([
                'name',
                'email',
                'password',
            ]);
            $data['password'] = Hash::make($data['password']);
            $customer = $this->customerRepository->create($data);
            return $this->responseSuccess($this->transform($customer, CustomerTransformer::class));
        } catch (\Exception $e) {
            Log::error('Register customer error:' . $e->getMessage());
            return $this->responseInternalServerError();
        }
    }

    /**
     * @return JsonResponse
     */
    public function authenticate()
    {
        if (auth('customer')->check()) {
            return $this->responseSuccess();
        }
        return $this->responseErrors(400, 'Vui lòng đăng nhập');
    }

    public function changePassword(ChangePasswordRequest $request): JsonResponse
    {
        try {
            $data = $request->only([
                'current_password',
                'new_password',
            ]);
            $user = auth('customer')->user();
            if (!Hash::check($data['current_password'], $user->password)) {
                return $this->responseErrors(400, 'Mật khẩu cũ không đúng');
            }
            if ($data['new_password'] == $data['current_password']) {
                return $this->responseErrors(400, "Mật khẩu mới trùng với mật khẩu cũ.");
            }
            $user->password = Hash::make($data['new_password']);
            $user->save();
            return $this->responseSuccess();
        } catch (\Exception $exception) {
            Log::error('Customer change password error: ' . $exception->getMessage());
            return $this->responseInternalServerError();
        }
    }

    public function validateOtp(ValidateOtpRequest $request): JsonResponse
    {
        try {
            $vnID = $request->input('vn_id');
            $attemptsFailedKey = OtpHelper::getKeyAttemptsFailed($vnID);
            $lockAccountKey = OtpHelper::getKeyLocked($vnID);
            if (Cache::has($lockAccountKey)) {
                $remainingTime = RedisHelper::getTTL($lockAccountKey);
                return $this->responseErrors(400,
                    "Tài khoản của bạn đã bị khóa. Vui lòng thử lại sau $remainingTime giây.",
                    [
                    'error_key' => 'locked_account'
                    ]
                );
            }
            $digitCode = $request->input('digit_code');
            $tempToken = $request->input('temp_token');
            $storedTempTokenKey = "temp_token_$vnID";
            $storedTempToken = Cache::get($storedTempTokenKey);
            if (!$storedTempToken || $storedTempToken !== $tempToken) {
                return $this->responseErrors(400, 'Token tạm thời không hợp lệ');
            }
            $customer = Customer::where('vn_id', $vnID)->orderBy('vn_id_issue_date', 'desc')->first();
            $userEmail = $customer->email;
            $otp = Otp::where([
                'receiver_address' => $userEmail,
                'digit_code' => $digitCode,
                'verified_at' => null
            ])->where('expire_at', '>', Carbon::now())
                ->latest('expire_at')
                ->first();
            if (!$otp) {
                $attemptsFailed = (int) Cache::get($attemptsFailedKey, 0);
                $attemptsFailed += 1;
                $attemptsFailedTime = config('otp.attempts_failed_time');
                $attemptsFailedDuration = config('otp.attempts_failed_duration')*60;
                if ($attemptsFailed >= $attemptsFailedTime) {
                    Cache::forget($attemptsFailedKey);
                    $accountLockedTime = config('otp.account_locked_time');
                    Cache::put($lockAccountKey, '', $accountLockedTime*60);
                    return $this->responseErrors(400,
                        "Mã xác thực không đúng hoặc đã hết hạn. Tài khoản của bạn đã bị khóa. Vui lòng thử lại sau $attemptsFailedDuration phút",
                        [
                            'error_key' => 'locked_account'
                        ]
                    );
                }
                Cache::put($attemptsFailedKey, $attemptsFailed, $attemptsFailedDuration*60);
                return $this->responseErrors(400, "Mã xác thực không đúng hoặc đã hết hạn. Bạn đã nhập sai $attemptsFailed lần. Nếu nhập sai $attemptsFailedTime lần. Tài khoản của bạn sẽ bị khóa trong $attemptsFailedDuration phút");
            }
            $otp->verified_at = Carbon::now();
            $otp->save();
            if ($customer->is_active == Customer::ACTIVATED) {
                $token = JWTAuth::fromUser($customer);
                Cache::forget($attemptsFailedKey);
                Cache::forget($storedTempTokenKey);
                event(new CustomerLoggedIn($customer, $token));
                return $this->responseSuccess([
                    'is_active' => Customer::ACTIVATED,
                    'access_token' => $token,
                    'customer' => $this->transform($customer, CustomerTransformer::class)
                ]);
            } else {
                if (!$tempToken = TempTokenService::create("first_login_$vnID", config('otp.temp_token_first_login_expire_time'))) {
                    return $this->responseErrors();
                }
                return $this->responseSuccess([
                    'is_active' => Customer::NOT_ACTIVATED,
                    'temp_token_first_login' => $tempToken,
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Validate otp error: ' . $e->getMessage());
            return $this->responseInternalServerError();
        }
    }

    public function forgetPassword(ForgotPasswordRequest $request): JsonResponse
    {
        try {
            $vnID = $request->input('vn_id');
            $email = $request->input('email');
            $customer = Customer::where([
                'vn_id' => $vnID,
                'email' => $email
            ])->first();
            if (!$customer) {
                return $this->responseErrors(400, 'Thông tin không hợp lệ');
            }
            $token = Password::createToken($customer);
            $resetLink = config('app.fe_domain') . "/reset-password" . "?token=$token&email=" . urlencode($email);
            dispatch(new SendResetPasswordMailJob($resetLink, $customer->email));
            return $this->responseSuccess('Đã gửi email reset mật khẩu đến bạn');
        } catch (\Exception $e) {
            Log::error('Forgot password error: ' . $e->getMessage());
            return $this->responseInternalServerError();
        }
    }

    public function resetPassword(ResetPasswordRequest $request): JsonResponse
    {
        try {
            $status = Password::broker('customers')->reset(
                $request->only('email', 'password', 'password_confirmation', 'token'),
                function ($customer, $password) {
                    $customer->forceFill(['password' => Hash::make($password)])->save();
                }
            );
            if ($status === Password::PASSWORD_RESET) {
                return $this->responseSuccess();
            } else {
                Log::error('Reset password error: status of response password reset: ' . $status);
                return $this->responseErrors(400, 'Đã có lỗi xảy ra');
            }
        } catch (\Exception $e) {
            Log::error('Reset password error: ' . $e->getMessage());
            return $this->responseInternalServerError();
        }
    }

    public function changeDefaultPassword(ChangePasswordFirstLoginRequest $request): JsonResponse
    {
        try {
            $tempToken = $request->input('temp_token');
            $vnID = $request->input('vn_id');
            $storedTempTokenKey = "temp_token_first_login_$vnID";
            $storedTempToken = Cache::get($storedTempTokenKey);
            if (!$storedTempToken || $storedTempToken !== $tempToken) {
                return $this->responseErrors(400, 'Token tạm thời cho lần đăng nhập đầu không hợp lệ');
            }
            $password = $request->input('password');
            $customer = Customer::where('vn_id', $vnID)->orderBy('vn_id_issue_date', 'desc')->first();
            if ($customer->is_active === Customer::ACTIVATED) {
                return $this->responseErrors(400, 'User đã đăng nhập lần đầu');
            }
            if (Hash::check($password, $customer->password)) {
                return $this->responseErrors(400, 'Mật khẩu mới không hợp lệ');
            }
            $customer->password = Hash::make($password);
            $customer->is_active = Customer::ACTIVATED;
            $customer->save();
            Cache::forget($storedTempTokenKey);
            $token = JWTAuth::fromUser($customer);
            return $this->responseSuccess([
                'is_active' => Customer::ACTIVATED,
                'access_token' => $token,
                'customer' => $this->transform($customer, CustomerTransformer::class)
            ]);
        } catch (\Exception $e) {
            Log::error('Change password first time error: ' . $e->getMessage());
            return $this->responseInternalServerError();
        }
    }
}
