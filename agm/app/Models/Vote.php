<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Vote extends ModelBase
{
    use SoftDeletes;

    protected $table = 'votes';


    protected static function booted()
    {
        parent::booted();
        parent::autoMeta();
    }

    public function voteItems(): HasMany
    {
        return $this->hasMany(VoteItem::class);
    }

    public function voteCustomers(): HasMany
    {
        return $this->hasMany(VoteCustomer::class, 'vote_id');
    }
    public function isCustomerVote(): HasOne
    {
        return $this->hasOne(VoteCustomer::class, 'vote_id')->where('customer_id',
            request()->route('customer_id') ?? 
            Auth::guard('customer')->id()
        );
    }

}
