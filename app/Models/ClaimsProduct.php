<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClaimsProduct extends Model
{
    use HasFactory;

    /**
     * Các thuộc tính được phép gán giá trị (Mass Assignable).
     */
    protected $table = 'claims_product';
    protected $fillable = [
        'claims_id',
        'old_product_variant_id',
        'new_product_variant_id',
        'quantity',
        'reason',
    ];
    public $timestamps = false;

    /**
     * Thiết lập mối quan hệ với bảng `doitra`.
     */
    public function doitra()
    {
        return $this->belongsTo(Claims::class, 'claims_id');
    }

    public function user()
    {
        return $this->hasOneThrough(User::class, Claims::class, 'id', 'id', 'claims_id', 'user_id');
    }
}
