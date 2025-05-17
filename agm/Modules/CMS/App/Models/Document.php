<?php

namespace Modules\CMS\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Document extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'description',
        'file_path',
        'file_name',
        'mime_type',
        'file_size',
        'is_published', // Thêm trường is_published
    ];

    // Thêm constants để dễ sử dụng
    const PUBLISHED = 1;
    const DRAFT = 0;

    protected $casts = [
        'file_size' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    public function getFormattedFileSizeAttribute()
    {
        $bytes = $this->file_size;
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        
        for ($i = 0; $bytes > 1024; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, 2) . ' ' . $units[$i];
    }

    // Thêm accessor để hiển thị trạng thái
    public function getStatusTextAttribute()
    {
        return $this->is_published == self::PUBLISHED ? 'Đã xuất bản' : 'Bản nháp';
    }
}