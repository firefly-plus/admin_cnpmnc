<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductImage extends Model
{
    use HasFactory;
    protected $table = 'productimage';
    
    // Các thuộc tính có thể gán
    protected $fillable = [
        'ProductID',
        'IMG_URL'
    ];
    public $timestamps = false;
    // Khai báo mối quan hệ với bảng 'product'
    public function product()
    {
        return $this->belongsTo(Product::class, 'ProductID','id');
    }
}
