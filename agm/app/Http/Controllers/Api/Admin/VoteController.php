<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Api\BaseApiController;
use App\Http\Requests\Admin\GetDataForPrintRequest;
use App\Models\Customer;
use App\Models\Vote;
use App\Models\VoteCustomer;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class VoteController extends BaseApiController
{
    public function getDataForPrintPhieuBauCu(GetDataForPrintRequest $request): JsonResponse
    {
        try {
            $customerId = $request->get('customer_id');
            $customer = Customer::find($customerId);
            if (!$customer) {
                return $this->responseNotFound();
            }

            // Bind customer_id vào route để relationships có thể truy cập
            request()->route()->setParameter('customer_id', $customerId);

            $vote = Vote::with([
                'voteItems.voteCustomer',
                'isCustomerVote'
            ])
            ->where('loai', 'BAU_CU')
            ->first();

            if (!$vote) {
                return $this->responseNotFound('Không tìm thấy phiếu bầu cử');
            }

            foreach ($vote->voteItems as $vote_item) {
                if (isset($vote_item->voteCustomer->customer_id)) {
                    $vote_item->so_phieu_bau = $vote_item->voteCustomer->so_phieu_bau;
                }
            }

            $now = Carbon::now();
            $vote->ngay = $now->day;
            $vote->thang = $now->month;
            $vote->nam = $now->year;

            // Thực hiện count riêng
            $voteCustomersCount = VoteCustomer::where('vote_id', $vote->id)
                ->leftJoin('authority', 'authority.nguoi_uy_quyen', '=', 'vote_customers.customer_id')
                ->where(function($query) use ($customer) {
                    $query->where('authority.nguoi_duoc_uy_quyen', $customer->id)
                          ->orWhere('vote_customers.customer_id', $customer->id);
                })
                ->count();
                
            $vote->vote_customers_count = $voteCustomersCount;

            return $this->responseSuccess($vote);
        } catch (\Exception $e) {
            Log::error('Get data for print phieu bau cu error: ' . $e->getMessage());
            return $this->responseInternalServerError();
        }
    }

    public function getDataForPrintPhieuBieuQuyet(GetDataForPrintRequest $request): JsonResponse
    {
        try {
            $customerId = $request->get('customer_id');
            $customer = Customer::find($customerId);
            if (!$customer) {
                return $this->responseNotFound();
            }

            // Bind customer_id vào route để relationships có thể truy cập
            request()->route()->setParameter('customer_id', $customerId);

            $vote = Vote::with([
                'voteItems.voteCustomer',
                'isCustomerVote'
            ])
            ->where('loai', 'BIEU_QUYET')
            ->first();

            if (!$vote) {
                return $this->responseNotFound('Không tìm thấy phiếu biểu quyết');
            }

            foreach ($vote->voteItems as $vote_item) {
                $vote_item->ket_qua_bieu_quyet = (isset($vote_item->voteCustomer)) ? $vote_item->voteCustomer->ket_qua_bieu_quyet : '';
            }

            $now = Carbon::now();
            $vote->ngay = $now->day;
            $vote->thang = $now->month;
            $vote->nam = $now->year;

            $voteCustomersCount = VoteCustomer::where('vote_id', $vote->id)
                ->leftJoin('authority', 'authority.nguoi_uy_quyen', '=', 'vote_customers.customer_id')
                ->where(function($query) use ($customer) {
                    $query->where('authority.nguoi_duoc_uy_quyen', $customer->id)
                          ->orWhere('vote_customers.customer_id', $customer->id);
                })
                ->count();

            $vote->vote_customers_count = $voteCustomersCount;

            return $this->responseSuccess($vote);
        } catch (\Exception $e) {
            Log::error('Get data for print phieu bieu quyet error: ' . $e->getMessage());
            return $this->responseInternalServerError();
        }
    }
}
