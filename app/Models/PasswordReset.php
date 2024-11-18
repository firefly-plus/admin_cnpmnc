<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PasswordReset extends Model
{
    use HasFactory;
    protected $table = 'password_resets';

    // Các thuộc tính có thể gán
    protected $fillable = [
        'email',
        'token',
        'created_at'
    ];

    public $timestamps = false;
}
