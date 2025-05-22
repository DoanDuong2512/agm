<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class VoteItem extends ModelBase
{
    use SoftDeletes;

    protected $table = 'vote_items';

    protected $fillable = [
        'vote_id',
        'noi_dung',
        'vi_tri_ung_cu',
        'ti_le_chap_thuan',
        'tong_co_phan_bieu_quyet',
        'tong_so_nguoi_bieu_quyet'
    ];

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
    
    public function vote() {
        return $this->belongsTo(Vote::class, 'vote_id');
    }
}

