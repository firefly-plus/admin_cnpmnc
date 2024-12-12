<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;
    protected $table = 'category';

    public $timestamps = false;
    // Các thuộc tính có thể gán
    const CREATED_AT = 'createdAt';
    const UPDATED_AT = 'updatedAt';
    protected $fillable = [
        'categoryName',
        'isDelete',
        'createdAt',
        'updatedAt',
    ];
    

    public function subCategories()
    {
        return $this->hasMany(SupCategory::class, 'categoryId');
    }

    // To get products through SupCategory
    public function products()
    {
        return $this->hasMany(Product::class, 'categoryId');
    }

   
}
