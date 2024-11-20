<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceDetail extends Model
{
    use HasFactory;
    protected $table = 'invoicedetail';

    // Các thuộc tính có thể gán
    protected $fillable = [
        'ID_Invoice',
        'ID_productVariation',
        'UnitPrice',
        'Amount',
        'Quantity'
    ];

    // Khai báo mối quan hệ với bảng 'invoice'
   
    public function invoice()
    {
        return $this->belongsTo(Invoice::class, 'ID_Invoice');
    }
    
    public function product()
{
    return $this->belongsTo(Product::class, 'ID_productVariation');
}

    

    // Khai báo mối quan hệ với bảng 'productvariation'
    public function productVariation()
    {
        return $this->belongsTo(ProductVariation::class, 'ID_productVariation');
    }
}
