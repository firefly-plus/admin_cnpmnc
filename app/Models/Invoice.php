<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class Invoice extends Model
{
    use HasFactory;

    protected $table = 'invoice';
    protected $primaryKey='invoice_id';
    protected $keyType = 'string';
    public $incrementing = false;
    // protected $primaryKey='invoice_id';
    // Các thuộc tính có thể gán
    protected $fillable = [
        'invoice_id',
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

    // const CREATED_AT = 'createdAt';
    // const UPDATED_AT = 'updatedAt';
    public $timestamps = false;

    // Các quan hệ
    public function employee()
    {
        return $this->belongsTo(Employee::class, 'ID_Employeer');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'ID_User');
    }

    public function invoiceDetails()
    {
        return $this->hasMany(InvoiceDetail::class, 'ID_Invoice','invoice_id');
    }




    public static function getAllInvoices(Request $request)
    {
        // Eager load các quan hệ: nhân viên (employee), người dùng (user), chi tiết hóa đơn (invoiceDetails), sản phẩm biến thể (productVariation) và sản phẩm (product)
        $query = self::with('employee', 'user', 'invoiceDetails.productVariation.product');

        // Các điều kiện lọc (như cũ)
        if ($id = $request->input('id')) {
            $query->where('invoice_id', $id);
        }
        if ($customerName = $request->input('customerName')) {
            $query->where('customerName', 'LIKE', '%' . $customerName . '%');
        }
        if ($paymentStatus = $request->input('paymentStatus')) {
            $query->where('paymentStatus', $paymentStatus);
        }
        if ($orderStatus = $request->input('orderStatus') && $request->input('orderStatus') != "all") {
            $query->where('orderStatus', $orderStatus);
          
        }
        if ($totalAmount = $request->input('totalAmount')) {
            $query->where('totalAmount', '>=', $totalAmount);
        }
        if ($receivedDate = $request->input('receivedDate')) {
            $query->whereDate('receivedDate', '=', $receivedDate);
        }
        if ($createdAt = $request->input('createdAt')) {
            $query->whereDate('createdAt', '=', $createdAt);
        }
        if ($phoneNumber = $request->input('phoneNumber')) {
            $query->where('phoneNumber', 'LIKE', '%' . $phoneNumber . '%');
        }
        if ($voucherCode = $request->input('voucherCode')) {
            $query->where('voucherCode', 'LIKE', '%' . $voucherCode . '%');
        }

        return $query->get();
    }
    


}
