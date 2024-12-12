<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Claims extends Model
{
    use HasFactory;

    /**
     * Các thuộc tính được phép gán giá trị (Mass Assignable).
     */
    protected $table = 'claims';
    protected $fillable = [
        'invoice_id',
        'status',
        'user_id'
    ];
    public $timestamps = false;

    /**
     * Thiết lập mối quan hệ với bảng `doitra_products`.
     */
    public function products()
    {
        return $this->hasMany(ClaimsProduct::class, 'claims_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function invoice()
    {
        return $this->belongsTo(Invoice::class, 'invoice_id');
    }
}
