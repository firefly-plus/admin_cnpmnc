<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Voucher extends Model
{
    use HasFactory;
    protected $table = 'voucher';

    // Các thuộc tính có thể gán
    protected $fillable = [
        'voucherCode',
        'description',
        'discountValue',
        'minOrderValue',
        'maxUses',
        'usedCount',
        'startDate',
        'endDate',
        'isActive',
        'createdAt',
        'updatedAt',
        'max_discount_amount'
    ];

    // Để tránh sử dụng timestamps mặc định
    public $timestamps = false;

    // Khai báo mối quan hệ với bảng 'voucher_user'
    public function voucherUsers()
    {
        return $this->hasMany(VoucherUser::class, 'ID_Voucher');
    }
}
