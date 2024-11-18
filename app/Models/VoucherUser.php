<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VoucherUser extends Model
{
    use HasFactory;
    protected $table = 'voucher_user';

    // Các thuộc tính có thể gán
    protected $fillable = [
        'ID_User',
        'ID_Voucher',
        'status',
        'createdAt',
        'updatedAt'
    ];

    // Để tránh sử dụng timestamps mặc định
    public $timestamps = false;

    // Khai báo mối quan hệ với bảng 'users'
    public function user()
    {
        return $this->belongsTo(User::class, 'ID_User');
    }

    // Khai báo mối quan hệ với bảng 'voucher'
    public function voucher()
    {
        return $this->belongsTo(Voucher::class, 'ID_Voucher');
    }
}
