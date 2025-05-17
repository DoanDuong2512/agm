<?php

namespace App\Models;

use App\Models\Traits\HasSessions;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;

/**
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $phone
 * @property string $gender
 * @property string $address
 * @property string $vn_id
 * @property string $password
 * @property int $is_active
 * @property int $is_checkin
 * @property string $ma_co_dong
 * @property int $co_phan_so_huu
 * @property int $tong_co_phan_duoc_uy_quyen
 * @property int $tong_so_co_dong_uy_quyen
 * @property bool $co_dong_noi_bo
 * @property string $vn_id_issue_date
 * @property int $co_phan_da_uy_quyen số cổ phần đã ủy quyền (tính toán động)
 * @property int $tong_co_phan_duoc_uy_quyen_tru_noi_bo 
 */
class Customer extends Authenticatable implements JWTSubject
{
    use HasFactory, HasSessions, SoftDeletes;
    protected $table = 'customers';
    protected $guarded = ['password'];
    protected $fillable = [
        'name',
        'email',
        'phone',
        'gender',
        'address',
        'password',
        'vn_id',
        'is_active',
        'is_checkin',
        'ma_co_dong',
        'co_phan_so_huu',
        'tong_co_phan_duoc_uy_quyen',
        'tong_so_co_dong_uy_quyen',
        'co_dong_noi_bo',
        'vn_id_issue_date',
        'co_phan_sau_uy_quyen',
        'tong_co_phan_duoc_uy_quyen_tru_noi_bo'
    ];
    protected $hidden = [
        'password',
    ];
    const ACTIVATED = 1;
    const NOT_ACTIVATED = 0;
    const CHECKED_IN = 1;
    const NOT_CHECKED_IN = 0;

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims(): array
    {
        return [
            'model_type' => Customer::class,
            'model_id' => $this->id,
            'guard' => 'customer'
        ];
    }

    public function getStatusTextAttribute()
    {
        return (int)$this->is_active === self::ACTIVATED ? 'Đã kích hoạt' : 'Chưa kích hoạt';
    }

    public function getStatusColorAttribute()
    {
        return (int)$this->is_active === self::ACTIVATED ? 'green' : 'red';
    }
    
    public function getCheckinStatusTextAttribute()
    {
        return (int)$this->is_checkin === self::CHECKED_IN ? 'Đã check-in' : 'Chưa check-in';
    }

    public function getCheckinStatusColorAttribute()
    {
        return (int)$this->is_checkin === self::CHECKED_IN ? 'green' : 'red';
    }

    public function voteCustomers() {
        return $this->hasMany(VoteCustomer::class, 'customer_id', 'id');
    }

    public function authority() {
        return $this->hasOne(Authority::class, 'nguoi_uy_quyen', 'id');
    }

    /**
     * Lấy tổng số cổ phần đã ủy quyền 
     */
    public function getCoPhanDaUyQuyenAttribute()
    {
        // Tính tổng số cổ phần đã ủy quyền
        return Authority::where('nguoi_uy_quyen', $this->id)
            ->sum('co_phan_uy_quyen') ?? 0;
    }

    /**
     * Lấy tất cả các bản ghi ủy quyền của cổ đông này
     */
    public function authoritiesGiven() {
        return $this->hasMany(Authority::class, 'nguoi_uy_quyen', 'id');
    }

    /**
     * Lấy tất cả các bản ghi ủy quyền cho cổ đông này
     */
    public function authoritiesReceived() {
        return $this->hasMany(Authority::class, 'nguoi_duoc_uy_quyen', 'id')
            ->where('is_shareholder', 1);
    }
}
