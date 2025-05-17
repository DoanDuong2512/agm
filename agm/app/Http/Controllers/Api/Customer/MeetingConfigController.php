<?php

namespace App\Http\Controllers\Api\Customer;

use App\Http\Controllers\Api\BaseApiController;
use App\Http\Requests\Customer\GetConfigRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Modules\CMS\App\Models\MeetingConfig;

class MeetingConfigController extends BaseApiController
{
    /**
     * Lấy cấu hình theo key
     * 
     * @param Request $request
     * @return JsonResponse
     */
    public function getConfigByKey(GetConfigRequest $request): JsonResponse
    {
        try {
            $key = $request->input('key');
            $config = MeetingConfig::where('key', $key)->first();
            if (!$config) {
                return $this->responseNotFound('Configuration not found');
            }
            return $this->responseSuccess([
                'key' => $config->key,
                'value' => $config->value
            ]);
        } catch (\Exception $e) {
            Log::error('Get config by key error: ' . $e->getMessage());
            return $this->responseInternalServerError();
        }
    }
}