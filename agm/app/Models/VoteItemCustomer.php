<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class VoteItemCustomer extends ModelBase
{
    use SoftDeletes;

    protected $table = 'vote_item_customers';


    protected static function booted()
    {
        parent::booted();
        parent::autoMeta();
    }
}
