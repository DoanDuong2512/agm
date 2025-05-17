<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property int|null $nguoi_uy_quyen
 * @property int|null $nguoi_duoc_uy_quyen 
 * @property int|null $co_phan_uy_quyen
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property \Carbon\Carbon|null $deleted_at
 * @property int $is_shareholder
 * @property string|null $vn_id
 * @property \Carbon\Carbon|null $vn_id_issue_date
 * @property string|null $address
 * @property string|null $ten_nguoi_duoc_uy_quyen
 */
class Authority extends ModelBase
{
    use SoftDeletes;

    protected $table = 'authority';

    protected $fillable = [
        'nguoi_uy_quyen',
        'nguoi_duoc_uy_quyen',
        'co_phan_uy_quyen',
        'is_shareholder',
        'ten_nguoi_duoc_uy_quyen',
        'vn_id',
        'vn_id_issue_date',
        'address',
    ];

    protected $casts = [
        'is_shareholder' => 'boolean',
        'vn_id_issue_date' => 'date',
        'co_phan_uy_quyen' => 'integer',
    ];

    public function authorizer()
    {
        return $this->belongsTo(Customer::class, 'nguoi_uy_quyen');
    }

    public function authorized()
    {
        return $this->belongsTo(Customer::class, 'nguoi_duoc_uy_quyen');
    }

    protected static function booted()
    {
        parent::booted();
        parent::autoMeta();
    }
}
