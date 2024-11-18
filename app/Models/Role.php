<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;
    protected $table = 'roles';

    // Các thuộc tính có thể gán
    protected $fillable = [
        'RoleName',
        'description'
    ];
}
