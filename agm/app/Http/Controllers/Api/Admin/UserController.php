<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Api\BaseApiController;
use App\Models\User;
use App\Repositories\Criteria\UserCriteriaRequest;
use App\Repositories\UserRepository;
use App\Transformers\UserTransformer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class UserController extends BaseApiController
{
    protected $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function listUser(Request $request): JsonResponse
    {
        try {
            $users = $this->userRepository
                ->pushCriteria(new UserCriteriaRequest($request))
                ->paginate($request->input('per_page', 10));
            $users = $this->transform($users, UserTransformer::class);
            return $this->responseSuccess($users);
        } catch (\Exception $e) {
            Log::error('Get list user error: ' . $e->getMessage());
            return $this->responseInternalServerError();
        }
    }

    public function me(): JsonResponse
    {
        try {
            $data = $this->transform(auth('admin')->user(), UserTransformer::class);
            return $this->responseSuccess($data);
        } catch (\Exception $e) {
            Log::error('Get user (me) information error: ' . $e->getMessage());
            return $this->responseInternalServerError();
        }
    }

    /**
     * @param $id
     * @return JsonResponse
     */
    public function delete($id): JsonResponse
    {
        try {
            $user = User::find($id);
            if (!$user) {
                return $this->responseNotFound();
            }
            $user->delete();
            return $this->responseSuccess();
        } catch (\Exception $e) {
            Log::error('Delete user error: ' . $e->getMessage());
            return $this->responseInternalServerError();
        }
    }
}
