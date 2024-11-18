<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductVariation extends Model
{
    use HasFactory;
    protected $table = 'productvariation';

    // Các thuộc tính có thể gán
    protected $fillable = [
        'ID_Product',
        'size',
        'Price',
        'stock',
        'isDelete',
        'createdAt',
        'updatedAt'
    ];

    // Khai báo mối quan hệ với bảng 'product'
    public function product()
    {
        return $this->belongsTo(Product::class, 'ID_Product');
    }
}