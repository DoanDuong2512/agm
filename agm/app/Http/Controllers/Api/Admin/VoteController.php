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

    public function listVotedShareholders($type = 'BAU_CU')
    {
        // Lấy vote_id theo loại phiếu
        $vote = \App\Models\Vote::where('loai', $type)->first();
        if (!$vote) {
            return view('admin.vote-details-list', ['shareholders' => [], 'type' => $type]);
        }
<?php
public function listVotedShareholders()
{
    // Lấy vote_id cho từng loại
    $voteBauCu = \App\Models\Vote::where('loai', 'BAU_CU')->first();
    $voteBieuQuyet = \App\Models\Vote::where('loai', 'BIEU_QUYET')->first();

    // Lấy danh sách cổ đông đã gửi phiếu bầu cử
    $shareholdersBauCu = $voteBauCu
        ? \App\Models\Customer::whereIn('id', function($query) use ($voteBauCu) {
            $query->select('customer_id')
                  ->from('vote_customers')
                  ->where('vote_id', $voteBauCu->id);
        })->get()
        : collect();

    // Lấy danh sách cổ đông đã gửi phiếu biểu quyết
    $shareholdersBieuQuyet = $voteBieuQuyet
        ? \App\Models\Customer::whereIn('id', function($query) use ($voteBieuQuyet) {
            $query->select('customer_id')
                  ->from('vote_customers')
                  ->where('vote_id', $voteBieuQuyet->id);
        })->get()
        : collect();

    return view('admin.vote-details-list', [
        'shareholdersBauCu' => $shareholdersBauCu,
        'shareholdersBieuQuyet' => $shareholdersBieuQuyet,
    ]);
}
        // Lấy danh sách cổ đông đã gửi phiếu
        $shareholders = \App\Models\Customer::whereIn('id', function($query) use ($vote) {
            $query->select('customer_id')
                  ->from('vote_customers')
                  ->where('vote_id', $vote->id);
        })->get();

        return view('admin.vote-details-list', compact('shareholders', 'type'));
    }
}
@extends('cms::layouts.master')

@section('content')
<div class="container-fluid">
    <h1>Danh sách cổ đông đã gửi phiếu</h1>
    <ul class="nav nav-tabs" id="voteTab" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="baucu-tab" data-bs-toggle="tab" data-bs-target="#baucu" type="button" role="tab">Phiếu bầu cử</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="bieuquyet-tab" data-bs-toggle="tab" data-bs-target="#bieuquyet" type="button" role="tab">Phiếu biểu quyết</button>
        </li>
    </ul>
    <div class="tab-content mt-3" id="voteTabContent">
        <!-- Tab Phiếu bầu cử -->
        <div class="tab-pane fade show active" id="baucu" role="tabpanel">
            <h4>Danh sách cổ đông đã gửi phiếu bầu cử</h4>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Tên cổ đông</th>
                        <th>Email</th>
                        <th>Chi tiết</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($shareholdersBauCu as $customer)
                        <tr>
                            <td>{{ $customer->name }}</td>
                            <td>{{ $customer->email }}</td>
                            <td>
                                <a href="{{ route('admin.vote.detail', ['type' => 'BAU_CU', 'customer' => $customer->id]) }}">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <!-- Tab Phiếu biểu quyết -->
        <div class="tab-pane fade" id="bieuquyet" role="tabpanel">
            <h4>Danh sách cổ đông đã gửi phiếu biểu quyết</h4>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Tên cổ đông</th>
                        <th>Email</th>
                        <th>Chi tiết</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($shareholdersBieuQuyet as $customer)
                        <tr>
                            <td>{{ $customer->name }}</td>
                            <td>{{ $customer->email }}</td>
                            <td>
                                <a href="{{ route('admin.vote.detail', ['type' => 'BIEU_QUYET', 'customer' => $customer->id]) }}">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

<?php
Route::middleware(['admin'])->prefix('admin')->group(function () {
    Route::get('vote-details', [VoteController::class, 'listVotedShareholders'])->name('admin.vote.details');
    Route::get('vote-details/{type}/detail/{customer}', [VoteController::class, 'voteDetail'])->name('admin.vote.detail');
});
<li class="nav-item">
    <a class="nav-link" href="{{ route('admin.vote.details') }}">
        <i class="fas fa-list"></i>
        Danh sách chi tiết phiếu
    </a>
</li>
