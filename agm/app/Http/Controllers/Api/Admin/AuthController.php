<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Api\BaseApiController;
use App\Http\Requests\ChangePasswordRequest;
use App\Http\Requests\LoginRequest;
use App\Repositories\UserRepository;
use App\Transformers\UserTransformer;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class AuthController extends BaseApiController
{
    protected $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @param LoginRequest $request
     * @return JsonResponse
     */
    public function login(LoginRequest $request)
    {
        try {
            $credentials = $request->only([
                'email',
                'password'
            ]);
            if (!$token = auth('admin')->attempt($credentials)) {
                return $this->responseErrors(400, 'Sai tài khoản hoặc mật khẩu');

            }
            $user = auth('admin')->user();
            $response = [
                'token' => $token,
                'user' => $this->transform($user, UserTransformer::class)
            ];
            return $this->responseSuccess($response);
        } catch (\Exception $e) {
            Log::error('Login admin error: ' . $e->getMessage());
            return $this->responseInternalServerError();
        }
    }

    /**
     * @return JsonResponse
     */
    public function logout()
    {
        auth('admin')->logout();
        return $this->responseSuccess([], 200, 'Logged out successfully');
    }

    /**
     * @param LoginRequest $request
     * @return JsonResponse
     */
    public function register(LoginRequest $request)
    {
        try {
            $data = $request->only([
                'name',
                'email',
                'password',
            ]);
            $data['password'] = Hash::make($data['password']);
            $user = $this->userRepository->create($data);
            return $this->responseSuccess($this->transform($user, UserTransformer::class));
        } catch (\Exception $e) {
            Log::error('Register user admin error: ' . $e->getMessage());
            return $this->responseInternalServerError();
        }
    }

    /**
     * @return JsonResponse
     */
    public function authenticate()
    {
        if (auth('admin')->check()) {
            return $this->responseSuccess();
        }
        return $this->responseErrors(400, 'Vui lòng đăng nhập');
    }

    /**
     * @return JsonResponse
     */
    public function userProfile()
    {
        $user = auth('admin')->user();
        return $this->responseSuccess($this->transform($user, UserTransformer::class));
    }

    /**
     * @param ChangePasswordRequest $request
     * @return JsonResponse
     */
    public function changePassword(ChangePasswordRequest $request): JsonResponse
    {
        $user = auth('admin')->user();
        $data = $request->only([
            'current_password',
            'new_password',
        ]);
        try {
            if (!Hash::check($data['current_password'], $user->password)) {
                return $this->responseErrors(400, "Mật khẩu cũ không trùng khớp.");
            }
            if ($data['new_password'] == $data['current_password']) {
                return $this->responseErrors(400, "Mật khẩu mới trùng với mật khẩu cũ.");
            }
            $user->password = Hash::make($data['new_password']);
            $user->save();
            return $this->responseSuccess($this->transform($user, UserTransformer::class));
        } catch (\Exception $e) {
            Log::error('Change password user error: ' . $e->getMessage());
            return $this->responseInternalServerError();
        }
    }
}
