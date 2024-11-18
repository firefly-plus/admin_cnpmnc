<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderSupplierDetail extends Model
{
    use HasFactory;
    protected $table = 'ordersupplierdetail';

    // Các thuộc tính có thể gán
    protected $fillable = [
        'ID_OrderSupplier',
        'ID_productVariation',
        'QuantityOrdered',
        'UnitPrice',
        'Amount',
        'ImportQuantity',
        'status',
        'note',
        'createdAt',
        'updatedAt'
    ];

    // Khai báo mối quan hệ với bảng 'ordersupplier'
    public function orderSupplier()
    {
        return $this->belongsTo(OrderSupplier::class, 'ID_OrderSupplier');
    }

    // Khai báo mối quan hệ với bảng 'productvariation'
    public function productVariation()
    {
        return $this->belongsTo(ProductVariation::class, 'ID_productVariation');
    }
}
