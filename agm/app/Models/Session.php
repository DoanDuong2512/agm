<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int id
 * @property int sessionable_id
 * @property string sessionable_type
 * @property string agent
 * @property string token
 * @property string ip_address
 * @property Carbon|null invalidated_at
 * @property Carbon|null updated_at
 * @property Carbon|null created_at
 */
class Session extends Model
{
    use HasFactory;

    protected $fillable = [
        'sessionable_id',
        'sessionable_type',
        'token_id',
        'ip_address',
        'agent',
        'token',
        'invalidated_at'
    ];

    protected $casts = [
        'invalidated_at' => 'date'
    ];

    /**
     * @param Builder $builder
     * @param boolean $isInvalidate
     * @return Builder
     */
    public function scopeInvalidate(Builder $builder, bool $isInvalidate = true): Builder
    {
        if (!$isInvalidate) {
            return $builder->whereNull('invalidated_at');
        }

        return $builder->whereNotNull('invalidated_at');
    }

    /**
     * @return boolean
     */
    public function isInvalidated(): bool
    {
        return (bool) $this->invalidated_at;
    }

    /**
     * @return boolean
     */
    public function invalidate(): bool
    {
        return $this->update([
            'invalidated_at' => now()
        ]);
    }
}
