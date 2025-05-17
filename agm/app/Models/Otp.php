<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Otp
 * @package App\Models
 *
 * @property integer id
 * @property string receiver_address
 * @property string digit_code
 * @property string sent_through
 * @property Carbon expire_at
 * @property Carbon verified_at
 * @property Carbon created_at
 * @property Carbon updated_at
 * @property Carbon deleted_at
 */
class Otp extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'otp';

    protected $fillable = [
        'receiver_address',
        'digit_code',
        'sent_through',
        'expire_at',
        'verified_at'
    ];
}
