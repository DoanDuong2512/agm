<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class VoteItemCustomerImport extends ModelBase
{
    use SoftDeletes;

    protected $table = 'vote_item_customer_import';


    protected static function booted()
    {
        parent::booted();
        parent::autoMeta();
    }
}
