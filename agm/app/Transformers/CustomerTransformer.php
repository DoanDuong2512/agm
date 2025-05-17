<?php

namespace App\Transformers;

use App\Models\Customer;
use League\Fractal\TransformerAbstract;
class CustomerTransformer extends TransformerAbstract
{
    /**
     * List of resources possible to include
     *
     * @var array
     */
    protected array $availableIncludes = [];

    public function transform(Customer $customer): array
    {
        return [
            'id' => $customer->id,
            'vn_id' => $customer->vn_id,
            'name' => $customer->name,
            'email' => $customer->email,
            'gender' => $customer->gender,
            'phone' => $customer->phone,
            'ma_co_dong' => $customer->ma_co_dong,
            'co_phan_so_huu' => $customer->co_phan_so_huu,
            'tong_co_phan_duoc_uy_quyen' => $customer->tong_co_phan_duoc_uy_quyen ?? 0,
            'tong_so_co_dong_uy_quyen' => $customer->tong_so_co_dong_uy_quyen ?? 0,
            'co_dong_noi_bo' => $customer->co_dong_noi_bo,
            'co_phan_bieu_quyet' => ($customer->co_phan_so_huu - $customer->co_phan_da_uy_quyen) + $customer->tong_co_phan_duoc_uy_quyen,
            'so_phieu_bau_toi_da' => ($customer->co_phan_so_huu - $customer->co_phan_da_uy_quyen) + $customer->tong_co_phan_duoc_uy_quyen,
            'so_bieu_quyet_da_uy_quyen' => $customer->co_phan_da_uy_quyen ? intval($customer->co_phan_da_uy_quyen) : 0,
            'co_phan_sau_uy_quyen' => $customer->co_phan_sau_uy_quyen ?? ($customer->co_phan_so_huu - ($customer->co_phan_da_uy_quyen ? intval($customer->co_phan_da_uy_quyen) : 0)),
            'created_at' => $customer->created_at ? $customer->created_at->timestamp : null,
            'updated_at' => $customer->updated_at ? $customer->updated_at->timestamp : null,
        ];
    }
}
