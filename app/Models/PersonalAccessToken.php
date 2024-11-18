<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PersonalAccessToken extends Model
{
    use HasFactory;
    protected $table = 'personal_access_tokens';

    // Các thuộc tính có thể gán
    protected $fillable = [
        'tokenable_type',
        'tokenable_id',
        'name',
        'token',
        'abilities',
        'last_used_at',
        'created_at',
        'updated_at'
    ];

    // Để không sử dụng timestamps mặc định của Laravel
    public $timestamps = false;
}
