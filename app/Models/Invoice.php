<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;
    protected $table = 'invoice';

    // Các thuộc tính có thể gán
    protected $fillable = [
        'ID_Employeer',
        'ID_User',
        'totalAmount',
        'discountAmount',
        'finalAmount',
        'voucherCode',
        'paymentStatus',
        'paymentMethod',
        'orderStatus',
        'note',
        'receivedDate',
        'shippingAddress',
        'phoneNumber',
        'customerName'
    ];

    // Khai báo mối quan hệ với bảng 'employees'
    public function employee()
    {
        return $this->belongsTo(Employee::class, 'ID_Employeer');
    }

    // Khai báo mối quan hệ với bảng 'users'
    public function user()
    {
        return $this->belongsTo(User::class, 'ID_User');
    }

    // Khai báo mối quan hệ với bảng 'invoicedetail'
    public function invoiceDetails()
    {
        return $this->hasMany(InvoiceDetail::class, 'ID_Invoice');
    }
}
