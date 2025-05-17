<?php

namespace App\Http\Controllers\Api\Customer;

use App\Http\Controllers\Api\BaseApiController;
use App\Models\Authority;
use App\Models\Customer;
use App\Models\Vote;
use App\Models\VoteCustomer;
use App\Models\VoteItemCustomer;
use App\Repositories\Criteria\DocumentCriteriaRequest;
use App\Repositories\DocumentRepository;
use App\Transformers\CustomerTransformer;
use App\Transformers\DocumentTransformer;
use App\Transformers\VoteTransformer;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class VoteController extends BaseApiController
{
    public function authorizedPerson(Request $request)
    {
        try {
            $customer = Auth::guard('customer')->user();
            $author = Customer::whereHas('authority', function ($query) use ($customer) {
                $query->where('nguoi_duoc_uy_quyen', $customer->id);
            })->with('voteCustomers')->get();

            $data = (new VoteTransformer())->transformAuthorizes($author);
            return $this->responseSuccess($data);
        } catch (\Exception $e) {
            Log::error('Authorized person error: ' . $e->getMessage());
            return $this->responseInternalServerError();
        }
    }

    public function votes()
    {
        try {
            $customer = Auth::guard('customer')->user();

            $votes = Vote::with([
                'voteItems.voteCustomer',
                'isCustomerVote'
            ])
//            ->withCount(['voteCustomers' => function($q) use ($customer) {
//                $q->leftJoin('authority', 'authority.nguoi_uy_quyen', '=', 'vote_customers.customer_id')
//                    ->where(function($query) use ($customer) {
//                        $query->where('authority.nguoi_duoc_uy_quyen', $customer->id)
//                            ->orWhere('vote_customers.customer_id', $customer->id);
//                    });
//            }])
            ->orderBy('created_at', 'ASC')
            ->get();

            $itemVote = [];
            foreach ($votes as $vote) {
                $vote->vote_customers_count = 0;
                if ($vote->loai === 'BAU_CU') {
                    foreach ($vote->voteItems as $vote_item) {
                        if (isset($vote_item->voteCustomer->customer_id)) {
                            $vote_item->so_phieu_bau = $vote_item->voteCustomer->so_phieu_bau;
                            $vote->vote_customers_count = $vote_item->voteCustomer->so_phieu_bau;
                            array_push($itemVote, (isset($vote_item->voteCustomer->vote_id) ? $vote_item->voteCustomer->vote_id : null));
                        }
                    }
                }
                if ($vote->loai === 'BIEU_QUYET') {
                    foreach ($vote->voteItems as $vote_item) {
                        if (isset($vote_item->voteCustomer)) {
                            $vote_item->ket_qua_bieu_quyet = $vote_item->voteCustomer->ket_qua_bieu_quyet;
                        } else {
                            $vote_item->ket_qua_bieu_quyet = 'TAN_THANH';
                        }
                    }
                }
            }

            foreach ($votes as $vote) {
                $vote->vote_all = true;
                $vote->item_vote = $itemVote;
                $vote->vote_customer = [];
                $vote->is_voted = (bool)$vote->isCustomerVote;
            }

            return $this->responseSuccess($votes);
        } catch (\Exception $e) {
            Log::error('Get votes error: ' . $e->getMessage());
            return $this->responseInternalServerError();
        }
    }

    public function store(Request $request)
    {
        try {
            $params = $request->only([
                'vote_id',
                'is_vote_all',
                'vote_customer_id',
                'vote_item_id',
                'vote_item_status'
            ]);
            $customer = Auth::guard('customer')->user();

            $vote = Vote::where('id', $params['vote_id'])->first();
            if (!$vote) {
                return $this->responseBadRequest('Phiếu không đúng');
            }
            if ($vote->trang_thai !== 'DANG_MO') {
                return $this->responseBadRequest('Phiếu chưa mở hoặc đã kết thúc');
            }
            $checkUyQuyen = Authority::where('nguoi_uy_quyen', $customer->id)->count();
            if ($checkUyQuyen) {
                return $this->responseBadRequest('Bạn đã ủy quyền cho một cổ đông khác');
            }

            if ($params['is_vote_all']) {
                $listCoDong = Authority::where('nguoi_duoc_uy_quyen', $customer->id)->pluck('nguoi_uy_quyen')->toArray();
            } else {
                $listCoDong = $params['vote_customer_id'];
            }
            array_push($listCoDong, $customer->id);

            $filterVote = VoteCustomer::where('vote_id', $vote->id)->whereIn('customer_id', $listCoDong)->pluck('customer_id')->toArray();

            $listCoDong = array_diff($listCoDong, $filterVote);

            $voteCustomer = [];
            foreach ($listCoDong as $coDong) {
                array_push($voteCustomer, [
                    'customer_id' => $coDong,
                    'vote_id' => $vote->id,
                    'created_by' => $customer->id,
                    'updated_by' => $customer->id,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
            }
            DB::table('vote_customers')->insert($voteCustomer);

            $batchData = [];
            if ($vote->loai === 'BAU_CU') {
                foreach ($listCoDong as $coDong) {
                    foreach ($params['vote_item_status'] as $voteItemStatus) {
                        array_push($batchData, [
                            'customer_id' => $coDong,
                            'vote_item_id' => $voteItemStatus['id'],
                            'vote_id' => $vote->id,
                            'loai' => $vote->loai,
                            'so_phieu_bau' => $customer->id === $coDong ? $voteItemStatus['so_phieu_bau'] : null,
                            'created_by' => $customer->id,
                            'updated_by' => $customer->id,
                            'created_at' => Carbon::now(),
                            'updated_at' => Carbon::now(),
                        ]);
                    }
                }
            }
            if ($vote->loai === 'BIEU_QUYET') {
                foreach ($listCoDong as $coDong) {
                    foreach ($params['vote_item_status'] as $voteItemStatus) {
                        array_push($batchData, [
                            'customer_id' => $coDong,
                            'vote_item_id' => $voteItemStatus['id'],
                            'vote_id' => $vote->id,
                            'loai' => $vote->loai,
                            'ket_qua_bieu_quyet' => $voteItemStatus['ket_qua_bieu_quyet'],
                            'created_by' => $customer->id,
                            'updated_by' => $customer->id,
                            'created_at' => Carbon::now(),
                            'updated_at' => Carbon::now(),
                        ]);
                    }
                }
            }
            DB::table('vote_item_customers')->insert($batchData);

            return $this->responseSuccess();
        } catch (\Exception $e) {
            Log::error('Store vote error: ' . $e->getMessage());
            return $this->responseInternalServerError();
        }
    }

    public function reStore(Request $request)
    {
        try {
            $params = $request->only([
                'vote_id'
            ]);
            $customer = Auth::guard('customer')->user();
            $vote = Vote::where('id', $params['vote_id'])->first();
            if (!$vote) {
                return $this->responseBadRequest('Phiếu không đúng');
            }
            $checkVoted = VoteCustomer::where('vote_id', $vote->id)->where('customer_id', $customer->id)->count();
            if (!$checkVoted) {
                return $this->responseBadRequest('Cổ đông chưa bỏ phiếu');
            }
            $authority = Authority::where('nguoi_duoc_uy_quyen', $customer->id)->pluck('nguoi_uy_quyen')->toArray();
            array_push($authority, $customer->id);
            VoteCustomer::where('vote_id', $vote->id)->whereIn('customer_id', $authority)->delete();
            VoteItemCustomer::where('vote_id', $vote->id)->whereIn('customer_id', $authority)->delete();
            return $this->responseSuccess();
        } catch (\Exception $e) {
            Log::error('Restore vote error: ' . $e->getMessage());
            return $this->responseInternalServerError();
        }
    }


    public function printBauCu(Request $request) {
        $macodong = $request->get('ma_co_dong');
        if (!$macodong) {
            $customer = Auth::guard('customer')->user();
        } else {
            $customer = Customer::where('ma_co_dong', $macodong)->first();
        }

        $vote = Vote::with(['voteItems.voteCustomer', 'isCustomerVote'])
        ->withCount(['voteCustomers' => function($q) use ($customer) {
            $q->leftJoin('authority', 'authority.nguoi_uy_quyen', '=', 'vote_customers.customer_id')
                ->where('authority.nguoi_duoc_uy_quyen', $customer->id)
                ->orWhere('vote_customers.customer_id', $customer->id);
        }])
        ->where('loai', 'BAU_CU')
        ->first();
        if (!$vote) {
            return $this->responseNotFound('Không tìm thấy phiếu bầu cử');
        }

        foreach ($vote->voteItems as $vote_item) {
            if (!$macodong) {
                if (isset($vote_item->voteCustomer->customer_id)) {
                    $vote_item->so_phieu_bau = $vote_item->voteCustomer->so_phieu_bau;
                }
            }
        }

        $now = Carbon::now();
        $vote->ngay = $now->day;
        $vote->thang = $now->month;
        $vote->nam = $now->year;
        return $this->responseSuccess($vote);
    }

    public function printBieuQuyet(Request $request) {
        $macodong = $request->get('ma_co_dong');
        if (!$macodong) {
            $customer = Auth::guard('customer')->user();
        } else {
            $customer = Customer::where('ma_co_dong', $macodong)->first();
        }
        $vote = Vote::with(['voteItems.voteCustomer', 'isCustomerVote'])
            ->withCount(['voteCustomers' => function($q) use ($customer) {
                $q->leftJoin('authority', 'authority.nguoi_uy_quyen', '=', 'vote_customers.customer_id')
                    ->where('authority.nguoi_duoc_uy_quyen', $customer->id)
                    ->orWhere('vote_customers.customer_id', $customer->id);
            }])
            ->where('loai', 'BIEU_QUYET')
            ->first();

        if (!$macodong) {
            foreach ($vote->voteItems as $vote_item) {
                if (isset($vote_item->voteCustomer)) {
                    $vote_item->ket_qua_bieu_quyet = $vote_item->voteCustomer->ket_qua_bieu_quyet;
                } else {
                    $vote_item->ket_qua_bieu_quyet = 'TAN_THANH';
                }
            }
        }

        $now = Carbon::now();
        $vote->ngay = $now->day;
        $vote->thang = $now->month;
        $vote->nam = $now->year;
        return $this->responseSuccess($vote);
    }

    public function customer(Request $request) {
        try {
            $macodong = $request->get('ma_co_dong');
            $customer = Customer::where('ma_co_dong', $macodong)->first();

            $authority = Authority::where('nguoi_uy_quyen', $customer->id)->where('is_shareholder', 0)->first();
            if ($authority) {
                $customer->name = $authority->ten_nguoi_duoc_uy_quyen;
                $customer->co_phan_so_huu = 0;
                $customer->tong_co_phan_duoc_uy_quyen =  $authority->co_phan_uy_quyen;
            }

            $data = $this->transform($customer, CustomerTransformer::class);
            return $this->responseSuccess($data);
        } catch (\Exception $e) {
            Log::error('Get customer (me) information error: ' . $e->getMessage());
            return $this->responseInternalServerError();
        }
    }
}
