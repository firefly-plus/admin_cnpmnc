<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FailedJob extends Model
{
    use HasFactory;
    protected $table = 'failed_jobs';

    // Các thuộc tính có thể gán
    protected $fillable = [
        'uuid',
        'connection',
        'queue',
        'payload',
        'exception',
        'failed_at'
    ];

    // Khai báo kiểu dữ liệu cho trường 'failed_at'
    //protected $dates = ['failed_at'];
}
