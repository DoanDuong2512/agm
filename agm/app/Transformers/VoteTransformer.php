<?php

namespace App\Transformers;

use App\Models\Customer;
use League\Fractal\TransformerAbstract;

class VoteTransformer extends TransformerAbstract
{

    public function transformAuthorize(Customer $customer): array
    {
        return [
            'id' => $customer->id,
            'ma_co_dong' => $customer->ma_co_dong,
            'votes' => $customer->voteCustomers->pluck('vote_id')->toArray()
        ];
    }

    public function transformAuthorizes($items)
    {
        $list = [];

        foreach ($items as $item) {
            array_push($list, $this->transformAuthorize($item));
        }
        return $list;
    }
}
