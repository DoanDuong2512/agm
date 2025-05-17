<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Api\BaseApiController;
use App\Models\AgmInfo;
use App\Models\Customer;
use App\Models\Authority;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PrintController extends BaseApiController
{
    public function getDataForBKKT()
    {
        try {
        $agmInfo = AgmInfo::first();

            // Đếm số người tham dự
//        $soNguoiTd = Customer::where('is_active', 1)->count();
        $soNguoiTd = $agmInfo->so_co_dong_tham_du ?? 0;

        // Đếm số người được ủy quyền
//        $soNguoiUq = Authority::count();
        $soNguoiUq = $agmInfo->so_luong_co_dong_uy_quyen ?? 0;

        // Tính tổng cổ phần có quyền biểu quyết
//        $totalShares = Customer::where('is_active', 1)->sum('co_phan_so_huu');
        $totalShares = $agmInfo->tong_so_co_phan_co_quyen_bieu_quyet ?? 0;

        // Tính tổng cổ phần tham dự (bao gồm ủy quyền)
//        $cpThamdu = Customer::where('is_active', 1)->sum(DB::raw('co_phan_so_huu + tong_co_phan_duoc_uy_quyen'));
        $cpThamdu = $agmInfo->tong_so_co_phan_tham_gia ?? 0;

        // Tính phần trăm biểu quyết
        $pcBq = $totalShares > 0 ? round(($cpThamdu / $totalShares) * 100, 2) : 0;

        // Ngày hiện tại
        $now = Carbon::now();
        $data = [
            'soNguoiTd' => $soNguoiTd,
            'soNguoiUq' => $soNguoiUq,
            'cpThamdu' => number_format($cpThamdu),
            'pcBq' => $pcBq,
            'ngay' => $now->day,
            'thang' => $now->month,
            'nam' => $now->year,
            'gio' => $now->hour,
            'phut' => $now->minute
        ];
        return $this->responseSuccess($data);
        } catch (\Exception $e) {
            Log::error('Get data for printing bkkt error: ' . $e->getMessage());
            return $this->responseInternalServerError();
        }
    }

    /**
     * Lấy thông tin chi tiết của cổ đông theo ID
     */
    public function getDataForPrintPhieuXacNhanThamDu(Request $request)
    {
        try {
            $customerId = $request->input('customer_id');
            if (!$customerId) {
                return $this->responseError('Customer ID is required', 400);
            }

            $customer = Customer::find($customerId);
            if (!$customer) {
                return $this->responseError('Customer not found', 404);
            }

            // Kiểm tra xem người dùng có ủy quyền cho người không phải cổ đông không
            $authority = Authority::where('nguoi_uy_quyen', $customerId)
                ->where('is_shareholder', 0)
                ->first();

            $name = $customer->name;
            $coPhanBieuQuyet = ($customer->co_phan_so_huu - $customer->co_phan_da_uy_quyen) + $customer->tong_co_phan_duoc_uy_quyen;
            // Nếu người dùng ủy quyền cho người không phải cổ đông (is_shareholder=0)
            // thì lấy tên từ ten_nguoi_duoc_uy_quyen trong authority
            if ($authority && !empty($authority->ten_nguoi_duoc_uy_quyen)) {
                $name = $authority->ten_nguoi_duoc_uy_quyen;
                $coPhanBieuQuyet = $customer->co_phan_so_huu;
            }
            
            $data = [
                'id' => $customer->id,
                'name' => $name,
                'vn_id' => $customer->vn_id,
                'ma_co_dong' => $customer->ma_co_dong,
                'co_phan_so_huu' => $customer->co_phan_so_huu ?? 0,
                'tong_co_phan_duoc_uy_quyen' => $customer->tong_co_phan_duoc_uy_quyen ?? 0,
                'co_phan_da_uy_quyen' => $customer->co_phan_da_uy_quyen ? intval($customer->co_phan_da_uy_quyen) : 0,
                'co_phan_bieu_quyet' => $coPhanBieuQuyet,
                'uy_quyen_nguoi_ngoai' => $authority ? 1 : 0
            ];

            return $this->responseSuccess($data);
        } catch (\Exception $e) {
            Log::error('Get customer data error: ' . $e->getMessage());
            return $this->responseInternalServerError();
        }
    }
}
