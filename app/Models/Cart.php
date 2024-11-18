<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;
    protected $table = 'carts';

    // Các thuộc tính có thể gán
    protected $fillable = [
        'ID_User', 
        'ID_ProductVariation', 
        'quantity'
    ];

    // Khai báo mối quan hệ với bảng 'users'
    public function user()
    {
        return $this->belongsTo(User::class, 'ID_User');
    }

    // Khai báo mối quan hệ với bảng 'productvariation'
    public function productVariation()
    {
        return $this->belongsTo(ProductVariation::class, 'ID_ProductVariation');
    }
}
