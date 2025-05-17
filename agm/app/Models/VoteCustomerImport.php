<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class VoteCustomerImport extends ModelBase
{

    use SoftDeletes;

    protected $table = 'vote_customer_import';

    protected static function booted()
    {
        parent::booted();
        parent::autoMeta();
    }
}
