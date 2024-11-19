
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <style>
        body 
        {
            font-family: 'DejaVu Sans', sans-serif;
        }

        h1 {
            text-align: center;
        }
        .invoice {
            margin-bottom: 20px;
        }
        .invoice-details {
            margin-left: 20px;
        }
        hr {
            border: 1px solid #ccc;
        }
        
    </style>
</head>

<body>
    <h1>Danh Sách Hóa Đơn</h1>

    @if($invoices && count($invoices) > 0)
        @foreach($invoices as $invoice)
            <div class="invoice">
                <p><strong>Mã Đơn Hàng:</strong> {{ $invoice->id }}</p>
                <p><strong>Mã Voucher:</strong> {{ $invoice->voucherCode ?? 'N/A' }}</p>
                <p><strong>Số Điện Thoại:</strong> {{ $invoice->phoneNumber ?? 'N/A' }}</p>
                <p><strong>Địa Chỉ:</strong> {{ $invoice->shippingAddress ?? 'N/A' }}</p>
                <p><strong>Phương Thức Thanh Toán:</strong> {{ $invoice->paymentMethod ?? 'N/A' }}</p>
                <p><strong>Số Tiền Cuối:</strong> {{ number_format($invoice->finalAmount, 2) ?? '0' }} VNĐ</p>
                <p><strong>Trạng Thái:</strong> {{ $invoice->orderStatus ?? 'Chưa xác định' }}</p>

                <div class="invoice-details">
                    <h3>Chi Tiết Hóa Đơn</h3>
                    @if($invoice->invoiceDetails && count($invoice->invoiceDetails) > 0)
                        @foreach($invoice->invoiceDetails as $detail)
                            <p><strong>ID Sản Phẩm:</strong> {{ $detail->productVariation->product->productName }}</p>
                            <p><strong>Giá:</strong> {{ number_format($detail->UnitPrice, 2) }} VNĐ</p>
                            <p><strong>Số Lượng:</strong> {{ $detail->Quantity }}</p>
                            <p><strong>Thành Tiền:</strong> {{ number_format($detail->Amount, 2) }} VNĐ</p>
                            <hr>
                        @endforeach
                    @else
                        <p>Không có chi tiết hóa đơn.</p>
                    @endif
                </div>
            </div>
        @endforeach
    @else
        <p>Không có hóa đơn nào để hiển thị.</p>
    @endif
</body>
</html>
