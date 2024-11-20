<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;
    protected $table = 'roles';
    protected $primaryKey='id';
    // Các thuộc tính có thể gán
    protected $fillable = [
        'RoleName',
        'description'
    ];

    public function employees()
    {
        return $this->belongsToMany(
            Employee::class,
            'roles_employees',
            'ID_Role',       
            'ID_Employees'   
        );
    }
}
