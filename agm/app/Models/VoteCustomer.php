<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class VoteCustomer extends ModelBase
{
    use SoftDeletes;

    protected $table = 'vote_customers';


    protected static function booted()
    {
        parent::booted();
        parent::autoMeta();
    }


}
