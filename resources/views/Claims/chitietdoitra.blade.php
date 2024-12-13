@extends('layout.index')
@section('title', 'Category Management')

@section('css')
<style>
    .table-container {
        max-height: 400px; /* Điều chỉnh chiều cao của bảng */
        overflow-y: auto;
    }
    .table th, .table td {
        vertical-align: middle;
    }
</style>
@endsection

@section('content')
<div class="container" style="margin-top: 50px">
    <div class="row mb-3">
        <div class="col-md-6">
            <input type="text" id="searchInput" class="form-control" placeholder="Nhập mã biến thể sản phẩm để tìm kiếm...">
        </div>
        <div class="col-md-2">
            <button id="searchButton" class="btn btn-primary">Tìm kiếm</button>
        </div>
        <div class="col-md-2">
            <button id="resetButton" class="btn btn-secondary">Quay lại</button>
        </div>
    </div>

    <div class="table-container">
        <table id="returnsTable" cellpadding="5" class="table table-striped table-bordered">
            <thead id="returnsTableHead">
                <tr>
                    <th>ID</th>
                    <th>Người dùng</th>
                    <th>Email</th>
                    <th>Đơn hàng</th>
                    <th>Trạng thái</th>
                    <th>Sản phẩm</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody id="returnsTableBody">
                @foreach($allReturns as $return)
                    <tr>
                        <td>{{ $return->id }}</td>
                        <td>{{ $return->user->name ?? 'Không xác định' }}</td>
                        <td>{{ $return->user->email ?? 'Không xác định' }}</td>
                        <td>
                            <button class="btn btn-primary view-order-details" data-order-id="{{ $return->invoice_id }}">
                                {{ $return->invoice_id }}
                            </button>
                        </td>
                        <td>{{ ucfirst($return->status) }}</td>
                        <td>
                            <ul>
                                @foreach($return->products as $product)
                                    <li>
                                        <strong>Mã SP cần đổi:</strong> {{ $product->old_product_variant_id }}<br>
                                        <strong>Số Lượng:</strong> {{ $product->quantity }}<br>
                                        <strong>Lý Do:</strong> {{ $product->reason }}
                                    </li>
                                @endforeach
                            </ul>
                        </td>
                        <td>
                            @if($return->status == 'approved')
                                <span class="btn btn-info">Đã xử lý</span>
                            @else
                                <button class="btn btn-success approve-return" data-id="{{ $return->id }}">Duyệt</button>
                                <button class="btn btn-danger reject-return" data-id="{{ $return->id }}">Hủy</button>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="orderDetailsModal" tabindex="-1" aria-labelledby="orderDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="orderDetailsModalLabel">Chi tiết đơn hàng</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="order-details-content">
                    <p>Đang tải...</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Xem chi tiết đơn hàng
        const buttons = document.querySelectorAll('.view-order-details');

        buttons.forEach(button => {
            button.addEventListener('click', function () {
                const orderId = this.getAttribute('data-order-id');
                const modal = document.getElementById('orderDetailsModal');
                const modalContent = document.getElementById('order-details-content');

                // Hiển thị modal
                const bootstrapModal = new bootstrap.Modal(modal);
                bootstrapModal.show();

                // Gửi yêu cầu tới route để lấy chi tiết đơn hàng
                fetch(`/chitietdonhang/${orderId}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            let html = `
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Tên sản phẩm</th>
                                            <th>Kích thước</th>
                                            <th>Giá sản phẩm</th>
                                            <th>Số lượng</th>
                                            <th>Tổng</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                            `;

                            data.data.forEach(item => {
                                html += `
                                    <tr>
                                        <td>${item.product_name}</td>
                                        <td>${item.size}</td>
                                         <td>${item.price_product}</td>
                                        <td>${item?.quantity}</td>
                                        <td>${item.price}</td>
                                    </tr>
                                `;
                            });

                            html += `
                                    </tbody>
                                </table>
                            `;

                            modalContent.innerHTML = html;
                        } else {
                            modalContent.innerHTML = '<p>Không tìm thấy chi tiết đơn hàng.</p>';
                        }
                    })
                    .catch(error => {
                        modalContent.innerHTML = '<p>Có lỗi xảy ra khi tải dữ liệu.</p>';
                        console.error(error);
                    });
            });
        });

        // Duyệt đơn
        document.querySelectorAll('.approve-return').forEach(button => {
            button.addEventListener('click', function () {
                const id = this.getAttribute('data-id');

                if (confirm('Bạn có chắc chắn muốn duyệt yêu cầu đổi trả này không?')) {
                    fetch(`/duyetdoitra/${id}`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert('Yêu cầu đổi trả đã được duyệt và đơn hàng mới đã được tạo.');
                            location.reload();
                        } else {
                            alert('Có lỗi xảy ra: ' + data.message);
                        }
                    })
                    .catch(error => console.error('Error:', error));
                }
            });
        });

        // Hủy đơn
        document.querySelectorAll('.reject-return').forEach(button => {
            button.addEventListener('click', function () {
                const id = this.getAttribute('data-id');

                if (confirm('Bạn có chắc chắn muốn hủy yêu cầu đổi trả này không?')) {
                    fetch(`/huydoitra/${id}`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert('Yêu cầu đổi trả đã bị hủy.');
                            location.reload();
                        } else {
                            alert('Có lỗi xảy ra: ' + data.message);
                        }
                    })
                    .catch(error => console.error('Error:', error));
                }
            });
        });

        // Tìm kiếm yêu cầu đổi trả
        const searchInput = document.getElementById('searchInput');
        const searchButton = document.getElementById('searchButton');
        const resetButton = document.getElementById('resetButton');
        const returnsTableHead = document.getElementById('returnsTableHead');
        const returnsTableBody = document.getElementById('returnsTableBody');

        const originalTableHead = returnsTableHead.innerHTML;
        const originalTableBody = returnsTableBody.innerHTML;

        // Sự kiện tìm kiếm
        searchButton.addEventListener('click', function () {
            const searchQuery = searchInput.value.trim();

            if (searchQuery === '') {
                alert('Vui lòng nhập mã biến thể sản phẩm.');
                return;
            }

            fetch(`/timkiemdoitra?variant_id=${searchQuery}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        returnsTableHead.innerHTML = `
                            <tr>
                                <th>Mã Biến Thể</th>
                                <th>Tên Sản Phẩm</th>
                                <th>Kích Thước</th>
                                <th>Giá</th>
                                <th>Số Lượng Tồn</th>
                                <th>Check số lượng tồn</th>
                            </tr>
                        `;

                        const returnItem = data.data;
                        let status = returnItem.stock_quantity > 0 ? "Đủ số lượng để đổi" : "Không đủ số lượng";
                        let html = `
                            <tr>
                                <td>${returnItem.variant_id}</td>
                                <td>${returnItem.product_name}</td>
                                <td>${returnItem.size}</td>
                                <td>${returnItem.price}</td>
                                <td>${returnItem.stock_quantity}</td>
                                <td>${status}</td>
                            </tr>
                        `;

                        returnsTableBody.innerHTML = html;
                    } else {
                        returnsTableHead.innerHTML = `
                            <tr>
                                <th colspan="6">Kết Quả Tìm Kiếm</th>
                            </tr>
                        `;
                        returnsTableBody.innerHTML = '<tr><td colspan="6">Không tìm thấy sản phẩm nào phù hợp.</td></tr>';
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    returnsTableHead.innerHTML = `
                        <tr>
                            <th colspan="6">Kết Quả Tìm Kiếm</th>
                        </tr>
                    `;
                    returnsTableBody.innerHTML = '<tr><td colspan="6">Có lỗi xảy ra khi tìm kiếm.</td></tr>';
                });
        });

        // Quay lại danh sách yêu cầu đổi trả
        resetButton.addEventListener('click', function () {
            returnsTableHead.innerHTML = originalTableHead;
            returnsTableBody.innerHTML = originalTableBody;
        });
    });
</script>
@endsection
