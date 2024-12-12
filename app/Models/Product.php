<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $table = 'product';
    protected $primaryKey='id';
    protected $keyType = 'string';
    public $incrementing = false;
    // Các thuộc tính có thể gán
    protected $fillable = [
        'ID_SupCategory',
        'productName',
        'description',
        'isDelete',
        'createdAt',
        'updatedAt'
    ];
    public function invoices()
    {
        return $this->belongsToMany(Invoice::class, 'invoicedetail', 'ID_productVariation', 'ID_Invoice');
    }
    
    

    public function invoiceDetails()
{
    return $this->hasMany(InvoiceDetail::class, 'ID_productVariation');
}


    // Khai báo mối quan hệ với bảng 'supcategory'
    public function subCategory()
    {
        return $this->belongsTo(SupCategory::class, 'ID_SupCategory');
    }

    
    public function productImages()
    {
        return $this->hasMany(ProductImage::class, 'ProductID');
    }

    // Khai báo mối quan hệ với bảng 'productvariation'
    public function productVariations()
    {
        return $this->hasMany(ProductVariation::class, 'ID_Product');
    }
}
