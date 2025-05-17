<?php

namespace Modules\CMS\App\Http\Controllers;

use App\Http\Controllers\Api\BaseApiController;
use App\Models\AgmInfo;
use App\Models\Authority;
use App\Models\Customer;
use App\Models\Vote;
use App\Models\VoteCustomerImport;
use App\Models\VoteItem;
use App\Models\VoteItemCustomerImport;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Modules\CMS\App\Models\Document;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;

class VoteCmsController extends BaseApiController
{
    public function index(Request $request)
    {
        return view('cms::vote.index');
    }

    public function printResult(Request $request)
    {
        return view('cms::vote.print');
    }


    public function customer(Request $request) {
        $macodong = $request->get('ma_co_dong');
        $customer = Customer::where('ma_co_dong', 'like', '%'.$macodong.'%')->get();
        return $this->responseSuccess($customer);
    }

    public function baucu(Request $request) {
        $loai = $request->get('loai');
        $vote = Vote::with(['voteItems.voteCustomer'])
            ->where('loai', $loai)
            ->first();
        return $this->responseSuccess($vote->voteItems);
    }

    public function importCustomer(Request $request) {
        $macodong = $request->get('ma_co_dong');
        $baucu = $request->get('bau_cu');
        $loai = $request->get('loai');
        $bieuquyet = $request->get('bieu_quyet');

        $customer = Customer::where('ma_co_dong', $macodong)->first();
        if (!$customer) {
            return $this->responseNotFound('Không tìm thấy cổ đông');
        }

        try {
            DB::beginTransaction();
            if ($loai === 'BAU_CU') {
                VoteCustomerImport::where('customer_id', $customer->id)->where('vote_id', 1)->delete();
                VoteItemCustomerImport::where('customer_id', $customer->id)->where('loai', 'BAU_CU')->delete();
                $voteCustomer = new VoteCustomerImport();
                $voteCustomer->vote_id = 1;
                $voteCustomer->customer_id = $customer->id;
                $voteCustomer->save();

                foreach ($baucu as $item) {
                    $voteItemCustomerImport = new VoteItemCustomerImport();
                    $voteItemCustomerImport->customer_id = $customer->id;
                    $voteItemCustomerImport->vote_item_id = $item['id'];
                    $voteItemCustomerImport->loai = 'BAU_CU';
                    $voteItemCustomerImport->bau_don_phieu = $item['bau_don_phieu'];

                    if ($voteItemCustomerImport->bau_don_phieu) {
                        $voteItemCustomerImport->so_phieu_bau = $customer->tong_so_co_dong_uy_quyen + 1;
                    } else {
                        if (!$item['so_phieu_bau']) {
                            DB::rollBack();
                            return $this->responseNotFound('Số phiếu bầu không hợp lệ');
                        }
                        $voteItemCustomerImport->so_phieu_bau = $item['so_phieu_bau'];
                    }
                    $voteItemCustomerImport->khong_hop_le = $item['khong_hop_le'];
                    $voteItemCustomerImport->save();
                }
            } else {
                VoteCustomerImport::where('customer_id', $customer->id)->where('vote_id', 2)->delete();
                VoteItemCustomerImport::where('customer_id', $customer->id)->where('loai', 'BIEU_QUYET')->delete();
                $voteCustomer = new VoteCustomerImport();
                $voteCustomer->vote_id = 2;
                $voteCustomer->customer_id = $customer->id;
                $voteCustomer->save();

                foreach ($bieuquyet as $item) {
                    $voteItemCustomerImport = new VoteItemCustomerImport();
                    $voteItemCustomerImport->customer_id = $customer->id;
                    $voteItemCustomerImport->vote_item_id = $item['id'];
                    $voteItemCustomerImport->loai = 'BIEU_QUYET';
                    $voteItemCustomerImport->ket_qua_bieu_quyet = $item['ket_qua_bieu_quyet'];
                    $voteItemCustomerImport->co_phan_bieu_quyet = $customer->co_phan_so_huu + $customer->tong_co_phan_duoc_uy_quyen;
                    $voteItemCustomerImport->khong_hop_le = $item['khong_hop_le'];
                    $voteItemCustomerImport->save();
                }

            }

            DB::commit();
            return $this->responseSuccess();
        } catch (\Exception $exception) {
            DB::rollBack();
            return $this->responseErrors();
        }
    }

    public function storeAgmInfo(Request $request) {
        $params = $request->all();
        AgmInfo::query()->delete();
        $agmInfo = new AgmInfo();
        $agmInfo->so_co_dong_tham_du = $params['so_co_dong_tham_du'];
        $agmInfo->so_luong_co_dong_uy_quyen = $params['so_luong_co_dong_uy_quyen'];
        $agmInfo->tong_so_co_phan_tham_gia = $params['tong_so_co_phan_tham_gia'];
        $agmInfo->tong_so_co_phan_co_quyen_bieu_quyet = $params['tong_so_co_phan_co_quyen_bieu_quyet'];
        $agmInfo->ti_le = $params['ti_le'];
        $agmInfo->save();
        return $this->responseSuccess();
    }

    public function getAgmInfo() {
        $agmInfo = new AgmInfo();
        $agmInfo->so_co_dong_tham_du = Customer::where('is_active', 1)->where('is_checkin', 1)->count() + Authority::where('authority.is_shareholder', 1)
                ->join('customers', 'customers.id', '=', 'authority.nguoi_duoc_uy_quyen')
                ->where('customers.is_checkin', 1)
                ->count();
        $agmInfo->so_luong_co_dong_uy_quyen = Authority::count();
        $agmInfo->tong_so_co_phan_tham_gia = Customer::where('is_active', 1)->where('is_checkin', 1)->sum(DB::raw('(CASE
            WHEN co_phan_sau_uy_quyen IS NULL OR co_phan_sau_uy_quyen = 0
            THEN co_phan_so_huu
            ELSE co_phan_sau_uy_quyen
        END) + IFNULL(tong_co_phan_duoc_uy_quyen, 0)'));
        $agmInfo->tong_so_co_phan_co_quyen_bieu_quyet = 83290077;
        $agmInfo->ti_le = $agmInfo->tong_so_co_phan_co_quyen_bieu_quyet > 0 ? round(($agmInfo->tong_so_co_phan_tham_gia / $agmInfo->tong_so_co_phan_co_quyen_bieu_quyet) * 100, 2) : 0;
        return $this->responseSuccess($agmInfo);
    }
}
