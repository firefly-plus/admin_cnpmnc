<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserToken extends Model
{
    use HasFactory;
    protected $table = 'user_tokens';

    // Các thuộc tính có thể gán
    protected $fillable = [
        'user_id',
        'token',
        'expires_at',
        'created_at'
    ];

    // Để tránh sử dụng timestamps mặc định
    public $timestamps = false;

    // Khai báo mối quan hệ với bảng 'users'
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
