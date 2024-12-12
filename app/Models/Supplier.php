<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory;

    protected $table = 'supplier';

    protected $fillable = [
        'SupplierName',
        'address',
        'phoneNumber',
        'Email',
        'contactPerson',
        'isDelete',  
        'createdAt',
        'updatedAt',
    ];

    const CREATED_AT = 'createdAt';
    const UPDATED_AT = 'updatedAt';


    public $timestamps = false; 
}
