<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $table = 'product';

    // Các thuộc tính có thể gán
    protected $fillable = [
        'ID_SupCategory',
        'productName',
        'description',
        'isDelete',
        'createdAt',
        'updatedAt'
    ];

    // Khai báo mối quan hệ với bảng 'supcategory'
    public function subCategory()
    {
        return $this->belongsTo(SupCategory::class, 'ID_SupCategory');
    }

    // Khai báo mối quan hệ với bảng 'productimage'
    public function productImages()
    {
        return $this->hasMany(ProductImage::class, 'ProductID');
    }

    // Khai báo mối quan hệ với bảng 'productvariation'
    public function productVariations()
    {
        return $this->hasMany(ProductVariation::class, 'ID_Product');
    }
}
