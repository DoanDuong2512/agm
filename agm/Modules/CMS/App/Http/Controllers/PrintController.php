<?php

namespace Modules\CMS\App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\AgmInfo;
use App\Models\Authority;
use App\Models\Customer;
use App\Models\VoteCustomerImport;
use App\Models\VoteItemCustomerImport;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class PrintController extends Controller
{
    public function showBBKT()
    {
        return view('cms::print.bbkt');
    }

    public function soCp($customerId, $notIn = []) {
        return Customer::where('is_active', 1)->whereIn('id', $customerId)->whereNotIn('id', $notIn)->selectRaw('SUM(
            (CASE
                WHEN co_phan_sau_uy_quyen IS NULL OR co_phan_sau_uy_quyen = 0
                THEN co_phan_so_huu
                ELSE co_phan_sau_uy_quyen
            END) + IFNULL(tong_co_phan_duoc_uy_quyen, 0)
        ) as total')->value('total') ?? 0;
    }

    public function soCp2($customerId) {
        return Customer::where('is_active', 1)->whereIn('id', $customerId)->where('is_checkin', 1)->selectRaw('
        SUM(
            CASE
                WHEN co_dong_noi_bo = 1 THEN IFNULL(tong_co_phan_duoc_uy_quyen_tru_noi_bo, 0)
                ELSE (
                    (CASE
                        WHEN co_phan_sau_uy_quyen IS NULL OR co_phan_sau_uy_quyen = 0
                        THEN co_phan_so_huu
                        ELSE co_phan_sau_uy_quyen
                    END) + IFNULL(tong_co_phan_duoc_uy_quyen, 0)
                )
            END
        ) as total')->value('total') ?? 0;
    }

    public function showBBDH()
    {

        $soNguoiTd =  Customer::where('is_active', 1)->where('is_checkin', 1)->count() + Authority::where('is_shareholder', 1)->count();
        $cpThamdu = Customer::where('is_active', 1)->where('is_checkin', 1)->sum(DB::raw('(CASE
            WHEN co_phan_sau_uy_quyen IS NULL OR co_phan_sau_uy_quyen = 0
            THEN co_phan_so_huu
            ELSE co_phan_sau_uy_quyen
        END) + IFNULL(tong_co_phan_duoc_uy_quyen, 0)'));
        $pcBq = round($cpThamdu / 83290077 * 100, 2);

        // tổng số thẻ biếu quyết phát ra
        $TONGPHIEU = Customer::where('is_active', 1)->where('is_checkin', 1)->count();
        $TONGCP = $cpThamdu;
        $pcCpPhatRa = round($cpThamdu / 83290077 * 100, 2);

        // phiếu thu về
        $PHIEUHL = VoteCustomerImport::where('vote_id', 2)->distinct('customer_id')->count();

        $customerId = VoteItemCustomerImport::where('loai', 'BIEU_QUYET')->distinct('customer_id')->pluck('customer_id')->toArray();
        $CPHOPLE = $this->soCp($customerId);
        $pcSoCoPhanHople = round( (intval($CPHOPLE) / $cpThamdu * 100), 2);

        // nội dung 1
        $customerId = VoteItemCustomerImport::where('vote_item_id', 1)->pluck('customer_id')->toArray();
        $TOTAL1 = $this->soCp($customerId);
        $TOTAL1PC =  round( (intval($TOTAL1) / $TONGCP * 100), 2);
        $customerId = VoteItemCustomerImport::where('vote_item_id', 1)->where('khong_hop_le', 0)->pluck('customer_id')->toArray();
        $TOTALHL1 = $this->soCp($customerId);
        $TOTALHL1PC = round( (intval($TOTALHL1) / $TONGCP * 100), 2);
        $customerId = VoteItemCustomerImport::where('vote_item_id', 1)->where('khong_hop_le', 1)->pluck('customer_id')->toArray();
        $KHL1 = $this->soCp($customerId);
        $KHL1PC = round( (intval($KHL1) / $TONGCP * 100), 2);
        $customerId = VoteItemCustomerImport::where('vote_item_id', 1)->where('ket_qua_bieu_quyet', 'TAN_THANH')->where('khong_hop_le', 0)->pluck('customer_id')->toArray();
        $DY1 = $this->soCp($customerId);
        $DY1PC = round( (intval($DY1) / $TONGCP * 100), 2);
        $customerId = VoteItemCustomerImport::where('vote_item_id', 1)->where('ket_qua_bieu_quyet', 'KHONG_TAN_THANH')->where('khong_hop_le', 0)->pluck('customer_id')->toArray();
        $KDY1 = $this->soCp($customerId);
        $KDY1PC = round( (intval($KDY1) / $TONGCP * 100), 2);
        $customerId = VoteItemCustomerImport::where('vote_item_id', 1)->where('ket_qua_bieu_quyet', 'KHONG_CO_Y_KIEN')->where('khong_hop_le', 0)->pluck('customer_id')->toArray();
        $KYK1 = $this->soCp($customerId);
        $KYK1PC = round( (intval($KYK1) / $TONGCP * 100), 2);

        // nội dung 2
        $customerId = VoteItemCustomerImport::where('vote_item_id', 2)->pluck('customer_id')->toArray();
        $TOTAL2 = $this->soCp($customerId);
        $TOTAL2PC =  round( (intval($TOTAL2) / $TONGCP * 100), 2);
        $customerId = VoteItemCustomerImport::where('vote_item_id', 2)->where('khong_hop_le', 0)->pluck('customer_id')->toArray();
        $TOTALHL2 = $this->soCp($customerId);
        $TOTALHL2PC = round( (intval($TOTALHL2) / $TONGCP * 100), 2);
        $customerId = VoteItemCustomerImport::where('vote_item_id', 2)->where('khong_hop_le', 1)->pluck('customer_id')->toArray();
        $KHL2 = $this->soCp($customerId);
        $KHL2PC = round( (intval($KHL2) / $TONGCP * 100), 2);
        $customerId = VoteItemCustomerImport::where('vote_item_id', 2)->where('ket_qua_bieu_quyet', 'TAN_THANH')->where('khong_hop_le', 0)->pluck('customer_id')->toArray();
        $DY2 = $this->soCp($customerId);
        $DY2PC = round( (intval($DY2) / $TONGCP * 100), 2);
        $customerId = VoteItemCustomerImport::where('vote_item_id', 2)->where('ket_qua_bieu_quyet', 'KHONG_TAN_THANH')->where('khong_hop_le', 0)->pluck('customer_id')->toArray();
        $KDY2 = $this->soCp($customerId);
        $KDY2PC = round( (intval($KDY2) / $TONGCP * 100), 2);
        $customerId = VoteItemCustomerImport::where('vote_item_id', 2)->where('ket_qua_bieu_quyet', 'KHONG_CO_Y_KIEN')->where('khong_hop_le', 0)->pluck('customer_id')->toArray();
        $KYK2 = $this->soCp($customerId);
        $KYK2PC = round( (intval($KYK2) / $TONGCP * 100), 2);

        // nội dung 3
        $customerId = VoteItemCustomerImport::where('vote_item_id', 3)->pluck('customer_id')->toArray();
        $TOTAL3 = $this->soCp($customerId);
        $TOTAL3PC =  round( (intval($TOTAL3) / $TONGCP * 100), 2);
        $customerId = VoteItemCustomerImport::where('vote_item_id', 3)->where('khong_hop_le', 0)->pluck('customer_id')->toArray();
        $TOTALHL3 = $this->soCp($customerId);
        $TOTALHL3PC = round( (intval($TOTALHL3) / $TONGCP * 100), 2);
        $customerId = VoteItemCustomerImport::where('vote_item_id', 3)->where('khong_hop_le', 1)->pluck('customer_id')->toArray();
        $KHL3 = $this->soCp($customerId);
        $KHL3PC = round( (intval($KHL3) / $TONGCP * 100), 2);
        $customerId = VoteItemCustomerImport::where('vote_item_id', 3)->where('ket_qua_bieu_quyet', 'TAN_THANH')->where('khong_hop_le', 0)->pluck('customer_id')->toArray();
        $DY3 = $this->soCp($customerId);
        $DY3PC = round( (intval($DY3) / $TONGCP * 100), 2);
        $customerId = VoteItemCustomerImport::where('vote_item_id', 3)->where('ket_qua_bieu_quyet', 'KHONG_TAN_THANH')->where('khong_hop_le', 0)->pluck('customer_id')->toArray();
        $KDY3 = $this->soCp($customerId);
        $KDY3PC = round( (intval($KDY3) / $TONGCP * 100), 2);
        $customerId = VoteItemCustomerImport::where('vote_item_id', 3)->where('ket_qua_bieu_quyet', 'KHONG_CO_Y_KIEN')->where('khong_hop_le', 0)->pluck('customer_id')->toArray();
        $KYK3 = $this->soCp($customerId);
        $KYK3PC = round( (intval($KYK3) / $TONGCP * 100), 2);

        // nội dung 4
        $customerId = VoteItemCustomerImport::where('vote_item_id', 4)->pluck('customer_id')->toArray();
        $TOTAL4 = $this->soCp($customerId);
        $TOTAL4PC =  round( (intval($TOTAL4) / $TONGCP * 100), 2);
        $customerId = VoteItemCustomerImport::where('vote_item_id', 4)->where('khong_hop_le', 0)->pluck('customer_id')->toArray();
        $TOTALHL4 = $this->soCp($customerId);
        $TOTALHL4PC = round( (intval($TOTALHL4) / $TONGCP * 100), 2);
        $customerId = VoteItemCustomerImport::where('vote_item_id', 4)->where('khong_hop_le', 1)->pluck('customer_id')->toArray();
        $KHL4 = $this->soCp($customerId);
        $KHL4PC = round( (intval($KHL4) / $TONGCP * 100), 2);
        $customerId = VoteItemCustomerImport::where('vote_item_id', 4)->where('ket_qua_bieu_quyet', 'TAN_THANH')->where('khong_hop_le', 0)->pluck('customer_id')->toArray();
        $DY4 = $this->soCp($customerId);
        $DY4PC = round( (intval($DY4) / $TONGCP * 100), 2);
        $customerId = VoteItemCustomerImport::where('vote_item_id', 4)->where('ket_qua_bieu_quyet', 'KHONG_TAN_THANH')->where('khong_hop_le', 0)->pluck('customer_id')->toArray();
        $KDY4 = $this->soCp($customerId);
        $KDY4PC = round( (intval($KDY4) / $TONGCP * 100), 2);
        $customerId = VoteItemCustomerImport::where('vote_item_id', 4)->where('ket_qua_bieu_quyet', 'KHONG_CO_Y_KIEN')->where('khong_hop_le', 0)->pluck('customer_id')->toArray();
        $KYK4 = $this->soCp($customerId);
        $KYK4PC = round( (intval($KYK4) / $TONGCP * 100), 2);

//        $codongnoibo = Customer::where('is_active', 1)->where('co_dong_noi_bo', 1)->pluck('id')->toArray();

        $total = Customer::where('is_active', 1)->where('is_checkin', 1)->selectRaw('
        SUM(
            CASE
                WHEN co_dong_noi_bo = 1 THEN IFNULL(tong_co_phan_duoc_uy_quyen_tru_noi_bo, 0)
                ELSE (
                    (CASE
                        WHEN co_phan_sau_uy_quyen IS NULL OR co_phan_sau_uy_quyen = 0
                        THEN co_phan_so_huu
                        ELSE co_phan_sau_uy_quyen
                    END) + IFNULL(tong_co_phan_duoc_uy_quyen, 0)
                )
            END
        ) as total')->value('total') ?? 0;
        $customerId = VoteItemCustomerImport::where('vote_item_id', 4)->pluck('customer_id')->toArray();
        $TOTAL4b = $this->soCp2($customerId);
        $TOTAL4PCb =  round( (intval($TOTAL4b) / $total * 100), 2);
        $customerId = VoteItemCustomerImport::where('vote_item_id', 4)->where('khong_hop_le', 0)->pluck('customer_id')->toArray();
        $TOTALHL4b = $this->soCp2($customerId);
        $TOTALHL4PCb = round( (intval($TOTALHL4b) / $total * 100), 2);
        $customerId = VoteItemCustomerImport::where('vote_item_id', 4)->where('khong_hop_le', 1)->pluck('customer_id')->toArray();
        $KHL4b = $this->soCp2($customerId);
        $KHL4PCb = round( (intval($KHL4b) / $total * 100), 2);
        $customerId = VoteItemCustomerImport::where('vote_item_id', 4)->where('ket_qua_bieu_quyet', 'TAN_THANH')->where('khong_hop_le', 0)->pluck('customer_id')->toArray();
        $DY4b = $this->soCp2($customerId);
        $DY4PCb = round( (intval($DY4b) / $total * 100), 2);
        $customerId = VoteItemCustomerImport::where('vote_item_id', 4)->where('ket_qua_bieu_quyet', 'KHONG_TAN_THANH')->where('khong_hop_le', 0)->pluck('customer_id')->toArray();
        $KDY4b = $this->soCp2($customerId);
        $KDY4PCb = round( (intval($KDY4b) / $total * 100), 2);
        $customerId = VoteItemCustomerImport::where('vote_item_id', 4)->where('ket_qua_bieu_quyet', 'KHONG_CO_Y_KIEN')->where('khong_hop_le', 0)->pluck('customer_id')->toArray();
        $KYK4b = $this->soCp2($customerId);
        $KYK4PCb = round( (intval($KYK4b) / $total * 100), 2);

        // nội dung 5
        $customerId = VoteItemCustomerImport::where('vote_item_id', 5)->pluck('customer_id')->toArray();
        $TOTAL5 = $this->soCp($customerId);
        $TOTAL5PC =  round( (intval($TOTAL5) / $TONGCP * 100), 2);
        $customerId = VoteItemCustomerImport::where('vote_item_id', 5)->where('khong_hop_le', 0)->pluck('customer_id')->toArray();
        $TOTALHL5 = $this->soCp($customerId);
        $TOTALHL5PC = round( (intval($TOTALHL5) / $TONGCP * 100), 2);
        $customerId = VoteItemCustomerImport::where('vote_item_id', 5)->where('khong_hop_le', 1)->pluck('customer_id')->toArray();
        $KHL5 = $this->soCp($customerId);
        $KHL5PC = round( (intval($KHL5) / $TONGCP * 100), 2);
        $customerId = VoteItemCustomerImport::where('vote_item_id', 5)->where('ket_qua_bieu_quyet', 'TAN_THANH')->where('khong_hop_le', 0)->pluck('customer_id')->toArray();
        $DY5 = $this->soCp($customerId);
        $DY5PC = round( (intval($DY5) / $TONGCP * 100), 2);
        $customerId = VoteItemCustomerImport::where('vote_item_id', 5)->where('ket_qua_bieu_quyet', 'KHONG_TAN_THANH')->where('khong_hop_le', 0)->pluck('customer_id')->toArray();
        $KDY5 = $this->soCp($customerId);
        $KDY5PC = round( (intval($KDY5) / $TONGCP * 100), 2);
        $customerId = VoteItemCustomerImport::where('vote_item_id', 5)->where('ket_qua_bieu_quyet', 'KHONG_CO_Y_KIEN')->where('khong_hop_le', 0)->pluck('customer_id')->toArray();
        $KYK5 = $this->soCp($customerId);
        $KYK5PC = round( (intval($KYK5) / $TONGCP * 100), 2);

        // nội dung 6
        $customerId = VoteItemCustomerImport::where('vote_item_id', 6)->pluck('customer_id')->toArray();
        $TOTAL6 = $this->soCp($customerId);
        $TOTAL6PC =  round( (intval($TOTAL6) / $TONGCP * 100), 2);
        $customerId = VoteItemCustomerImport::where('vote_item_id', 6)->where('khong_hop_le', 0)->pluck('customer_id')->toArray();
        $TOTALHL6 = $this->soCp($customerId);
        $TOTALHL6PC = round( (intval($TOTALHL6) / $TONGCP * 100), 2);
        $customerId = VoteItemCustomerImport::where('vote_item_id', 6)->where('khong_hop_le', 1)->pluck('customer_id')->toArray();
        $KHL6 = $this->soCp($customerId);
        $KHL6PC = round( (intval($KHL6) / $TONGCP * 100), 2);
        $customerId = VoteItemCustomerImport::where('vote_item_id', 6)->where('ket_qua_bieu_quyet', 'TAN_THANH')->where('khong_hop_le', 0)->pluck('customer_id')->toArray();
        $DY6 = $this->soCp($customerId);
        $DY6PC = round( (intval($DY6) / $TONGCP * 100), 2);
        $customerId = VoteItemCustomerImport::where('vote_item_id', 6)->where('ket_qua_bieu_quyet', 'KHONG_TAN_THANH')->where('khong_hop_le', 0)->pluck('customer_id')->toArray();
        $KDY6 = $this->soCp($customerId);
        $KDY6PC = round( (intval($KDY6) / $TONGCP * 100), 2);
        $customerId = VoteItemCustomerImport::where('vote_item_id', 6)->where('ket_qua_bieu_quyet', 'KHONG_CO_Y_KIEN')->where('khong_hop_le', 0)->pluck('customer_id')->toArray();
        $KYK6 = $this->soCp($customerId);
        $KYK6PC = round( (intval($KYK6) / $TONGCP * 100), 2);

        // nội dung 7
        $customerId = VoteItemCustomerImport::where('vote_item_id', 7)->pluck('customer_id')->toArray();
        $TOTAL7 = $this->soCp($customerId);
        $TOTAL7PC =  round( (intval($TOTAL7) / $TONGCP * 100), 2);
        $customerId = VoteItemCustomerImport::where('vote_item_id', 7)->where('khong_hop_le', 0)->pluck('customer_id')->toArray();
        $TOTALHL7 = $this->soCp($customerId);
        $TOTALHL7PC = round( (intval($TOTALHL7) / $TONGCP * 100), 2);
        $customerId = VoteItemCustomerImport::where('vote_item_id', 7)->where('khong_hop_le', 1)->pluck('customer_id')->toArray();
        $KHL7 = $this->soCp($customerId);
        $KHL7PC = round( (intval($KHL7) / $TONGCP * 100), 2);
        $customerId = VoteItemCustomerImport::where('vote_item_id', 7)->where('ket_qua_bieu_quyet', 'TAN_THANH')->where('khong_hop_le', 0)->pluck('customer_id')->toArray();
        $DY7 = $this->soCp($customerId);
        $DY7PC = round( (intval($DY7) / $TONGCP * 100), 2);
        $customerId = VoteItemCustomerImport::where('vote_item_id', 7)->where('ket_qua_bieu_quyet', 'KHONG_TAN_THANH')->where('khong_hop_le', 0)->pluck('customer_id')->toArray();
        $KDY7 = $this->soCp($customerId);
        $KDY7PC = round( (intval($KDY7) / $TONGCP * 100), 2);
        $customerId = VoteItemCustomerImport::where('vote_item_id', 7)->where('ket_qua_bieu_quyet', 'KHONG_CO_Y_KIEN')->where('khong_hop_le', 0)->pluck('customer_id')->toArray();
        $KYK7 = $this->soCp($customerId);
        $KYK7PC = round( (intval($KYK7) / $TONGCP * 100), 2);

        // nội dung 8
        $customerId = VoteItemCustomerImport::where('vote_item_id', 8)->pluck('customer_id')->toArray();
        $TOTAL8 = $this->soCp($customerId);
        $TOTAL8PC =  round( (intval($TOTAL8) / $TONGCP * 100), 2);
        $customerId = VoteItemCustomerImport::where('vote_item_id', 8)->where('khong_hop_le', 0)->pluck('customer_id')->toArray();
        $TOTALHL8 = $this->soCp($customerId);
        $TOTALHL8PC = round( (intval($TOTALHL8) / $TONGCP * 100), 2);
        $customerId = VoteItemCustomerImport::where('vote_item_id', 8)->where('khong_hop_le', 1)->pluck('customer_id')->toArray();
        $KHL8 = $this->soCp($customerId);
        $KHL8PC = round( (intval($KHL8) / $TONGCP * 100), 2);
        $customerId = VoteItemCustomerImport::where('vote_item_id', 8)->where('ket_qua_bieu_quyet', 'TAN_THANH')->where('khong_hop_le', 0)->pluck('customer_id')->toArray();
        $DY8 = $this->soCp($customerId);
        $DY8PC = round( (intval($DY8) / $TONGCP * 100), 2);
        $customerId = VoteItemCustomerImport::where('vote_item_id', 8)->where('ket_qua_bieu_quyet', 'KHONG_TAN_THANH')->where('khong_hop_le', 0)->pluck('customer_id')->toArray();
        $KDY8 = $this->soCp($customerId);
        $KDY8PC = round( (intval($KDY8) / $TONGCP * 100), 2);
        $customerId = VoteItemCustomerImport::where('vote_item_id', 8)->where('ket_qua_bieu_quyet', 'KHONG_CO_Y_KIEN')->where('khong_hop_le', 0)->pluck('customer_id')->toArray();
        $KYK8 = $this->soCp($customerId);
        $KYK8PC = round( (intval($KYK8) / $TONGCP * 100), 2);

        // nội dung 9
        $customerId = VoteItemCustomerImport::where('vote_item_id', 9)->pluck('customer_id')->toArray();
        $TOTAL9 = $this->soCp($customerId);
        $TOTAL9PC =  round( (intval($TOTAL9) / $TONGCP * 100), 2);
        $customerId = VoteItemCustomerImport::where('vote_item_id', 9)->where('khong_hop_le', 0)->pluck('customer_id')->toArray();
        $TOTALHL9 = $this->soCp($customerId);
        $TOTALHL9PC = round( (intval($TOTALHL9) / $TONGCP * 100), 2);
        $customerId = VoteItemCustomerImport::where('vote_item_id', 9)->where('khong_hop_le', 1)->pluck('customer_id')->toArray();
        $KHL9 = $this->soCp($customerId);
        $KHL9PC = round( (intval($KHL9) / $TONGCP * 100), 2);
        $customerId = VoteItemCustomerImport::where('vote_item_id', 9)->where('ket_qua_bieu_quyet', 'TAN_THANH')->where('khong_hop_le', 0)->pluck('customer_id')->toArray();
        $DY9 = $this->soCp($customerId);
        $DY9PC = round( (intval($DY9) / $TONGCP * 100), 2);
        $customerId = VoteItemCustomerImport::where('vote_item_id', 9)->where('ket_qua_bieu_quyet', 'KHONG_TAN_THANH')->where('khong_hop_le', 0)->pluck('customer_id')->toArray();
        $KDY9 = $this->soCp($customerId);
        $KDY9PC = round( (intval($KDY9) / $TONGCP * 100), 2);
        $customerId = VoteItemCustomerImport::where('vote_item_id', 9)->where('ket_qua_bieu_quyet', 'KHONG_CO_Y_KIEN')->where('khong_hop_le', 0)->pluck('customer_id')->toArray();
        $KYK9 = $this->soCp($customerId);
        $KYK9PC = round( (intval($KYK9) / $TONGCP * 100), 2);

        // bầu cử
        $phieuBauCuPhatRa = $TONGPHIEU;
        $cpBauCuPhatRa = $cpThamdu;
        $phieuBauCuHopLe = VoteItemCustomerImport::where('vote_item_id', 10)->where('khong_hop_le', 0)->count();

        $customerId = VoteItemCustomerImport::where('loai', 'BAU_CU')->where('khong_hop_le', 0)->distinct('customer_id')->pluck('customer_id')->toArray();
        $cpBauCuHopLe = $this->soCp($customerId);

        $phieuBauCuKhongHopLe = VoteItemCustomerImport::where('vote_item_id', 10)->where('khong_hop_le', 1)->count();
        $customerId = VoteItemCustomerImport::where('loai', 'BAU_CU')->where('khong_hop_le', 1)->distinct('customer_id')->pluck('customer_id')->toArray();
        $cpBauCuKhongHopLe = $this->soCp($customerId);

        $customerId = VoteItemCustomerImport::where('vote_item_id', 10)->where('khong_hop_le', 0)->distinct('customer_id')->pluck('customer_id')->toArray();
        $phieuBauNVM = $this->soCp($customerId);
        $tyLePhieuBauNVM =  round( (intval($cpBauCuHopLe) / $cpThamdu * 100), 2);

        return view('cms::print.bien_ban_dai_hoi')->with([
            'soNguoiTd' => number_format($soNguoiTd),
            'cpThamdu' => number_format($cpThamdu),
            'pcBq' => $pcBq,
            'TONGPHIEU' => number_format($TONGPHIEU),
            'TONGCP' => number_format($TONGCP),
            'pcCpPhatRa' => $pcCpPhatRa,
            'PHIEUHL' => number_format($PHIEUHL),
            'CPHOPLE' => number_format($CPHOPLE),
            'pcSoCoPhanHople' => $pcSoCoPhanHople,

            'TOTAL1' => number_format($TOTAL1),
            'TOTAL1PC' => $TOTAL1PC,
            'TOTALHL1' => number_format($TOTALHL1),
            'TOTALHL1PC' => $TOTALHL1PC,
            'KHL1' => number_format($KHL1),
            'KHL1PC' => $KHL1PC,
            'DY1' => number_format($DY1),
            'DY1PC' => $DY1PC,
            'KDY1' => number_format($KDY1),
            'KDY1PC' => $KDY1PC,
            'KYK1' => number_format($KYK1),
            'KYK1PC' => $KYK1PC,

            'TOTAL2' => number_format($TOTAL2),
            'TOTAL2PC' => $TOTAL2PC,
            'TOTALHL2' => number_format($TOTALHL2),
            'TOTALHL2PC' => $TOTALHL2PC,
            'KHL2' => number_format($KHL2),
            'KHL2PC' => $KHL2PC,
            'DY2' => number_format($DY2),
            'DY2PC' => $DY2PC,
            'KDY2' => number_format($KDY2),
            'KDY2PC' => $KDY2PC,
            'KYK2' => number_format($KYK2),
            'KYK2PC' => $KYK2PC,

            'TOTAL3' => number_format($TOTAL3),
            'TOTAL3PC' => $TOTAL3PC,
            'TOTALHL3' => number_format($TOTALHL3),
            'TOTALHL3PC' => $TOTALHL3PC,
            'KHL3' => number_format($KHL3),
            'KHL3PC' => $KHL3PC,
            'DY3' => number_format($DY3),
            'DY3PC' => $DY3PC,
            'KDY3' => number_format($KDY3),
            'KDY3PC' => $KDY3PC,
            'KYK3' => number_format($KYK3),
            'KYK3PC' => $KYK3PC,

            'TOTAL4' => number_format($TOTAL4),
            'TOTAL4PC' => $TOTAL4PC,
            'TOTALHL4' => number_format($TOTALHL4),
            'TOTALHL4PC' => $TOTALHL4PC,
            'KHL4' => number_format($KHL4),
            'KHL4PC' => $KHL4PC,
            'DY4' => number_format($DY4),
            'DY4PC' => $DY4PC,
            'KDY4' => number_format($KDY4),
            'KDY4PC' => $KDY4PC,
            'KYK4' => number_format($KYK4),
            'KYK4PC' => $KYK4PC,

            'TOTAL4b' => number_format($TOTAL4b),
            'TOTAL4PCb' => $TOTAL4PCb,
            'TOTALHL4b' => number_format($TOTALHL4b),
            'TOTALHL4PCb' => $TOTALHL4PCb,
            'KHL4b' => number_format($KHL4b),
            'KHL4PCb' => $KHL4PCb,
            'DY4b' => number_format($DY4b),
            'DY4PCb' => $DY4PCb,
            'KDY4b' => number_format($KDY4b),
            'KDY4PCb' => $KDY4PCb,
            'KYK4b' => number_format($KYK4b),
            'KYK4PCb' => $KYK4PCb,

            'TOTAL5' => number_format($TOTAL5),
            'TOTAL5PC' => $TOTAL5PC,
            'TOTALHL5' => number_format($TOTALHL5),
            'TOTALHL5PC' => $TOTALHL5PC,
            'KHL5' => number_format($KHL5),
            'KHL5PC' => $KHL5PC,
            'DY5' => number_format($DY5),
            'DY5PC' => $DY5PC,
            'KDY5' => number_format($KDY5),
            'KDY5PC' => $KDY5PC,
            'KYK5' =>number_format( $KYK5),
            'KYK5PC' => $KYK5PC,

            'TOTAL6' => number_format($TOTAL6),
            'TOTAL6PC' => $TOTAL6PC,
            'TOTALHL6' => number_format($TOTALHL6),
            'TOTALHL6PC' => $TOTALHL6PC,
            'KHL6' => number_format($KHL6),
            'KHL6PC' => $KHL6PC,
            'DY6' => number_format($DY6),
            'DY6PC' => $DY6PC,
            'KDY6' => number_format($KDY6),
            'KDY6PC' => $KDY6PC,
            'KYK6' => number_format($KYK6),
            'KYK6PC' => $KYK6PC,

            'TOTAL7' => number_format($TOTAL7),
            'TOTAL7PC' => $TOTAL7PC,
            'TOTALHL7' => number_format($TOTALHL7),
            'TOTALHL7PC' => $TOTALHL7PC,
            'KHL7' => number_format($KHL7),
            'KHL7PC' => $KHL7PC,
            'DY7' => number_format($DY7),
            'DY7PC' => $DY7PC,
            'KDY7' => number_format($KDY7),
            'KDY7PC' => $KDY7PC,
            'KYK7' => number_format($KYK7),
            'KYK7PC' => $KYK7PC,

            'TOTAL8' => number_format($TOTAL8),
            'TOTAL8PC' => $TOTAL8PC,
            'TOTALHL8' => number_format($TOTALHL8),
            'TOTALHL8PC' => $TOTALHL8PC,
            'KHL8' => number_format($KHL8),
            'KHL8PC' => $KHL8PC,
            'DY8' => number_format($DY8),
            'DY8PC' => $DY8PC,
            'KDY8' => number_format($KDY8),
            'KDY8PC' => $KDY8PC,
            'KYK8' => number_format($KYK8),
            'KYK8PC' => $KYK8PC,

            'TOTAL9' => number_format($TOTAL9),
            'TOTAL9PC' => $TOTAL9PC,
            'TOTALHL9' => number_format($TOTALHL9),
            'TOTALHL9PC' => $TOTALHL9PC,
            'KHL9' => number_format($KHL9),
            'KHL9PC' => $KHL9PC,
            'DY9' => number_format($DY9),
            'DY9PC' => $DY9PC,
            'KDY9' => number_format($KDY9),
            'KDY9PC' => $KDY9PC,
            'KYK9' => number_format($KYK9),
            'KYK9PC' => $KYK9PC,

            'phieuBauCuPhatRa' => number_format($phieuBauCuPhatRa),
            'cpBauCuPhatRa' => number_format($cpBauCuPhatRa),
            'phieuBauCuHopLe' => number_format($phieuBauCuHopLe),
            'cpBauCuHopLe' => number_format($cpBauCuHopLe),
            'phieuBauCuKhongHopLe' => number_format($phieuBauCuKhongHopLe),
            'cpBauCuKhongHopLe' => number_format($cpBauCuKhongHopLe),
            'phieuBauNVM' => number_format($phieuBauNVM),
            'tyLePhieuBauNVM' => $tyLePhieuBauNVM,

            'gioKet' => Carbon::now()->hour,
            'phutKet' => Carbon::now()->minute,

            'ngaySinhNVM' => '1984',
            'diaChiNVM' => '18/40 Trưng Vương, Lê Lợi, Sơn Tây, Hà Nội',
            'trinhDoNVM' => 'Cử nhân chuyên ngành Quản trị kinh doanh',
            'donViNVM' => 'CTCP Chứng khoán Rồng Việt'
        ]);
    }
}
