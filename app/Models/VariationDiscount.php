<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VariationDiscount extends Model
{
    use HasFactory;
    protected $table = 'variation_discount';

    // Các thuộc tính có thể gán
    protected $fillable = [
        'ID_Variation',
        'ID_Discount',
        'StartDate',
        'EndDate',
        'status'
    ];

    public $timestamps = false;

    // Khai báo mối quan hệ với bảng 'discount'
    public function discount() 
    {
        return $this->belongsTo(Discount::class, 'ID_Discount');
    }

    // Khai báo mối quan hệ với bảng 'productvariation'
    public function productVariation()
    {
        return $this->belongsTo(ProductVariation::class, 'ID_Variation');
    }
}
