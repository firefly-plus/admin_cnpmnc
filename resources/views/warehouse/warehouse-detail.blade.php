@extends('layout.index')

@section('title', 'Chi tiết đơn hàng')

@section('content')
    <div class="container">
        <h2>Chi tiết đơn hàng</h2>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID Chi tiết đơn hàng</th>
                    <th>Tên sản phẩm</th>
                    <th>Kích thước sản phẩm</th>
                    <th>Hình ảnh sản phẩm</th>
                    <th>Số lượng chưa nhập</th>
                    <th>Số lượng đã nhập</th>
                    <th>Đơn giá</th>
                    <th>Số tiền</th>
                    <th>Cập nhật lần cuối</th>
                    <th>Nhập số lượng</th>
                </tr>
            </thead>
            <tbody>
                @if (!empty($order))
                    @foreach ($order as $detail)
                        <tr id="row-{{ $detail['order_detail_id'] }}">
                            <td>{{ $detail['order_detail_id'] }}</td>
                            <td>{{ $detail['product_name'] }}</td>
                            <td>{{ $detail['product_size'] }}</td>
                            <td>
                                <img src="{{ $detail['product_image_url'] }}" alt="Hình ảnh" style="width: 100px; height: auto;">
                            </td>
                            <td>{{ $detail['QuantityOrdered'] }}</td>
                            <td id="import-quantity-{{ $detail['order_detail_id'] }}">{{ $detail['ImportQuantity'] ?? 'Chưa nhập' }}</td>
                            <td>{{ $detail['UnitPrice'] ?? 'Chưa có' }}</td>
                            <td>{{ $detail['Amount'] ?? 'Chưa có' }}</td>
                            <td>{{ \Carbon\Carbon::parse($detail['updatedAt'])->format('d/m/Y H:i:s') }}</td>
                            <td>
                                <div class="d-flex">
                                    <input type="number" name="import_quantity" class="form-control" style="width: 80px; margin-right: 5px;"
                                        value="{{ $detail['QuantityOrdered'] ?? 0 }}" min="0" max="{{ $detail['QuantityOrdered'] ?? 0 }}" required>
                                    <button class="btn btn-primary btn-sm update-btn"
                                        data-order-id="{{ $detail['order_detail_id'] }}"
                                        data-variation-id="{{ $detail['variation_id'] }}">Update</button>
                                </div>
                                <input type="hidden" name="ID_Variation" value="{{ $detail['variation_id'] }}">
                                <input type="hidden" name="orderID" value="{{ $detail['order_detail_id'] }}">

                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="12">Không có dữ liệu để hiển thị.</td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>

    <script>
        document.querySelectorAll('.update-btn').forEach(button => {
            button.addEventListener('click', async  function() {
                const orderId = this.getAttribute('data-order-id');

                const variationId = this.getAttribute('data-variation-id');

              // Lấy giá trị nhập từ input ngay trước button
                const importQuantityInput = this.previousElementSibling;  // Lấy input ngay trước button
                let importQuantity = parseInt(importQuantityInput.value, 10);

                // Kiểm tra xem số lượng nhập có hợp lệ không
                if (isNaN(importQuantity) || importQuantity <= 0) {
                    alert('Số lượng nhập phải là số dương.');
                    return;
                }
                alert('ID Chi tiết đơn hàng: ' + orderId + '\n' +
                      'Số lượng nhập: ' + importQuantity + '\n' +
                      'ID Biến thể: ' + variationId);

                      // Tạo đối tượng dữ liệu gửi tới API
                const data = {
                    ID_Variation: variationId,
                    newStock: importQuantity,
                    orderID: orderId
                };

                try {
                    // Gửi yêu cầu POST tới API
                    const response = await fetch('http://localhost:8017/v1/product/update-stock', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify(data) // Chuyển đối tượng data thành JSON
                    });

                    // Kiểm tra phản hồi từ API
                    if (response.ok) {
                        const result = await response.json();
                        alert('Cập nhật thành công: ' + result.message);
                        location.reload();
                        const updatedImportQuantity = importQuantity; // hoặc lấy từ response nếu API trả về dữ liệu mới
                        const importQuantityElement = document.getElementById('import-quantity-' + orderId);
                        if (importQuantityElement) {
                            importQuantityElement.innerText = updatedImportQuantity; // Cập nhật số lượng nhập mới
                        }
                    } else {
                        const error = await response.json();
                        alert('Lỗi: ' + error.message);
                    }
                } catch (error) {
                    alert('Có lỗi xảy ra: ' + error.message);
                }
            });
        });
    </script>
@endsection
