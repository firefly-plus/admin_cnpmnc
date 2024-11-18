<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory;
    protected $table = 'supplier';

    // Các thuộc tính có thể gán
    protected $fillable = [
        'SupplierName',
        'address',
        'phoneNumber',
        'Email',
        'contactPerson',
        'isDelete',
        'createdAt',
        'updatedAt'
    ];
}
