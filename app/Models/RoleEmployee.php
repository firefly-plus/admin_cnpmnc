<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoleEmployee extends Model
{
    use HasFactory;
    protected $table = 'roles_employees';

    // Các thuộc tính có thể gán
    protected $fillable = [
        'ID_Employees',
        'ID_Role'
    ];

    // Để tránh sử dụng timestamps mặc định
    public $timestamps = false;

    // Khai báo mối quan hệ với bảng 'employees'
    public function employee()
    {
        return $this->belongsTo(Employee::class, 'ID_Employees');
    }

    // Khai báo mối quan hệ với bảng 'roles'
    public function role()
    {
        return $this->belongsTo(Role::class, 'ID_Role');
    }
}
