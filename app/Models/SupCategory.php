<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupCategory extends Model
{
    use HasFactory;
    protected $table = 'supcategory';
    public $timestamps = false;
    const CREATED_AT = 'createdAt';
    const UPDATED_AT = 'updatedAt';
    protected $casts = [
        'id' => 'string',  // Ép kiểu id thành chuỗi
    ];

    // Các thuộc tính có thể gán
    protected $fillable = [
        'SupCategoryName',
        'categoryId',
        'isDelete',
        'createdAt',
        'updatedAt'
    ];

    // Khai báo mối quan hệ với bảng 'category'
    public function category()
    {
        return $this->belongsTo(Category::class, 'categoryId');
    }



    // Relationship with Product
    public function products()
{
    return $this->hasMany(Product::class, 'ID_SupCategory');
}


}
