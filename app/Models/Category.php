<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;
    protected $table = 'category';

    // Các thuộc tính có thể gán
    protected $fillable = [
        'categoryName',
        'isDelete',
        'createdAt',
        'updatedAt'
    ];
}
