<?php

namespace Modules\CMS\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MeetingConfig extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'value',
    ];
}