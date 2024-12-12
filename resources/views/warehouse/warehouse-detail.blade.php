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
                    <th>Số lượng đặt hàng</th>
                    <th>Số lượng nhập</th>
                    <th>Đơn giá</th>
                    <th>Số tiền</th>
                    <th>Trạng thái</th>
                    <th>Ghi chú</th>
                    <th>Cập nhật lần cuối</th>
                </tr>
            </thead>
            <tbody>
                @if (!empty($order))
                    @foreach ($order as $detail)
                        <tr>
                            <td>{{ $detail['order_detail_id'] }}</td>
                            <td>{{ $detail['product_name'] }}</td>
                            <td>{{ $detail['product_size'] }}</td>
                            <td>
                                <img src="{{ $detail['product_image_url'] }}" alt="Hình ảnh" style="width: 100px; height: auto;">
                            </td>
                            <td>{{ $detail['QuantityOrdered'] }}</td>
                            <td>{{ $detail['ImportQuantity'] ?? 'Chưa nhập' }}</td>
                            <td>{{ $detail['UnitPrice'] ?? 'Chưa có' }}</td>
                            <td>{{ $detail['Amount'] ?? 'Chưa có' }}</td>
                            <td>{{ $detail['status'] ?? 'Chưa cập nhật' }}</td>
                            <td>{{ $detail['note'] ?? 'Không có ghi chú' }}</td>
                            <td>{{ \Carbon\Carbon::parse($detail['updatedAt'])->format('d/m/Y H:i:s') }}</td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="11">Không có dữ liệu để hiển thị.</td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
@endsection
