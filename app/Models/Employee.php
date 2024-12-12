<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Employee extends Authenticatable 
{
    use HasApiTokens, HasFactory, Notifiable;
    
    protected $table = 'employees';
    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
       'id', 'FullName', 'Phone', 'Passwords', 'address', 'isDelete', 'createdAt', 'updatedAt'
    ];

    public $timestamps = false;

    protected $hidden = [
        'Passwords'
    ];

    protected $casts = [
        'Passwords' => 'hashed',
    ];

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'employee_roles', 'employee_id', 'role_id');
    }
}
