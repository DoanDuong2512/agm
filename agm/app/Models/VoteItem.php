<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class VoteItem extends ModelBase
{
    use SoftDeletes;

    protected $table = 'vote_items';


    protected static function booted()
    {
        parent::booted();
        parent::autoMeta();
    }

    public function voteCustomer() {
        return $this->hasOne(VoteItemCustomer::class, 'vote_item_id')->where('customer_id', 
            request()->route('customer_id') ?? 
            Auth::guard('customer')->id()
        );
    }
}
