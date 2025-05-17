<?php
namespace App\Http\Controllers\Api\Customer;

use App\Http\Controllers\Api\BaseApiController;
use App\Models\Customer;
use App\Repositories\Criteria\CustomerCriteria;
use App\Repositories\CustomerRepository;
use App\Transformers\CustomerTransformer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CustomerController extends BaseApiController
{
    protected CustomerRepository $customerRepository;

    /**
     * @param CustomerRepository $customerRepository
     */
    public function __construct(CustomerRepository $customerRepository)
    {
        $this->customerRepository = $customerRepository;
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function list(Request $request)
    {
        try {
            $data = $this->customerRepository
                ->pushCriteria(new CustomerCriteria($request))
                ->paginate($request->input('per_page', 10));
            $data = $this->transform($data, CustomerTransformer::class);
            return $this->responseSuccess($data);
        } catch (\Exception $e) {
            Log::error('Get list customer error: ' . $e->getMessage());
            return $this->responseInternalServerError();
        }
    }

    /**
     * @return JsonResponse
     */
    public function me(): JsonResponse
    {
        try {
            $data = $this->transform(auth('customer')->user(), CustomerTransformer::class);
            return $this->responseSuccess($data);
        } catch (\Exception $e) {
            Log::error('Get customer (me) information error: ' . $e->getMessage());
            return $this->responseInternalServerError();
        }
    }

    /**
     * @param $id
     * @return JsonResponse
     */
    public function show($id): JsonResponse
    {
        try {
            $customer = Customer::find($id);
            if (!$customer) {
                return $this->responseNotFound();
            }
            return $this->responseSuccess($this->transform($customer, CustomerTransformer::class));
        } catch (\Exception $e) {
            Log::error('Get customer by ID error: ' . $e->getMessage());
            return $this->responseInternalServerError();
        }
    }

    /**
     * @return JsonResponse
     */
    public function delete(): JsonResponse
    {
        try {
            $customer = auth('customer')->user();
            $customer->delete();
            return $this->responseSuccess();
        } catch (\Exception $e) {
            Log::error('Delete customer error: ' . $e->getMessage());
            return $this->responseInternalServerError();
        }
    }
}
