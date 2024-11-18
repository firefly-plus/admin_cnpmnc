<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MigrationModel extends Model
{
    use HasFactory;
    protected $table = 'migrations';

    // Các thuộc tính có thể gán
    protected $fillable = [
        'migration',
        'batch'
    ];

    public $timestamps = false;
}
