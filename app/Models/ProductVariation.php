<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductVariation extends Model
{
    use HasFactory;
    protected $table = 'productvariation';
    protected $primaryKey='id';
    public $timestamps = false;
    protected $keyType = 'string';
    public $incrementing = false;
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

    public function variationdiscount()
    {
        return $this->hasMany(VariationDiscount::class, 'ID_Variation');  
    }
    
}
