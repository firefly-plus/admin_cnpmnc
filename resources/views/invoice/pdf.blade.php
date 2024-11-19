<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Danh Sách Hóa Đơn</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            margin: 20px;
        }

        h1 {
            text-align: center;
        }

        .invoice {
            margin-bottom: 20px;
            padding: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .invoice p {
            margin: 5px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        table th, table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        table th {
            background-color: #f2f2f2;
        }

        .invoice-details h3 {
            margin-top: 20px;
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
                <p><strong>Tên Khách Hàng:</strong> {{ $invoice->user->FullName ?? 'N/A' }}</p>
                <p><strong>Số Điện Thoại:</strong> {{ $invoice->phoneNumber ?? 'N/A' }}</p>
                <p><strong>Địa Chỉ:</strong> {{ $invoice->shippingAddress ?? 'N/A' }}</p>
                <p><strong>Phương Thức Thanh Toán:</strong> {{ $invoice->paymentMethod ?? 'N/A' }}</p>
                <p><strong>Số Tiền Cuối:</strong> {{ number_format($invoice->finalAmount, 2) ?? '0' }} VNĐ</p>
                <p><strong>Trạng Thái:</strong> {{ $invoice->orderStatus ?? 'Chưa xác định' }}</p>

                <div class="invoice-details">
                    <h3>Chi Tiết Hóa Đơn</h3>
                    @if($invoice->invoiceDetails && count($invoice->invoiceDetails) > 0)
                        <table>
                            <thead>
                                <tr>
                                    <th>ID Sản Phẩm</th>
                                    <th>Giá</th>
                                    <th>Số Lượng</th>
                                    <th>Thành Tiền</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($invoice->invoiceDetails as $detail)
                                    <tr>
                                        <td>{{ $detail->productVariation->product->productName }}</td>
                                        <td>{{ number_format($detail->UnitPrice, 2) }} VNĐ</td>
                                        <td>{{ $detail->Quantity }}</td>
                                        <td>{{ number_format($detail->Amount, 2) }} VNĐ</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
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
