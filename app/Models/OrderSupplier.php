<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderSupplier extends Model
{
    use HasFactory;
    protected $table = 'ordersupplier';

    // Các thuộc tính có thể gán
    protected $fillable = [
        'ID_Supplier',
        'ID_Employeer',
        'TotalPrice',
        'OrderDate',
        'DateOfReceipt'
    ];

    // Khai báo mối quan hệ với bảng 'employees'
    public function employee()
    {
        return $this->belongsTo(Employee::class, 'ID_Employeer');
    }

    // Khai báo mối quan hệ với bảng 'supplier'
    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'ID_Supplier');
    }

    // Khai báo mối quan hệ với bảng 'ordersupplierdetail'
    public function orderSupplierDetails()
    {
        return $this->hasMany(OrderSupplierDetail::class, 'ID_OrderSupplier');
    }
}
