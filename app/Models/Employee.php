<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;
    protected $table = 'employees';

    // Các thuộc tính có thể gán
    protected $fillable = [
        'FullName',
        'Phone',
        'Passwords',
        'address',
        'isDelete',
        'createdAt',
        'updatedAt'
    ];
}
