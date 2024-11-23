@extends('layout.index')
@section('title', 'Voucher Management')

@section('css')

@endsection

@section('content')
<div class="container mt-4">
    <div class="card mb-4">
        <div class="card-header">##</div>
        <div class="card-body">
            <form id="add-voucher-form">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="voucherCode" class="form-label">Code Voucher</label>
                        <input type="text" id="voucherCode" class="form-control" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="description" class="form-label">Description</label>
                        <input type="text" id="description" class="form-control" required>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="discountValue" class="form-label">Value (%)</label>
                        <input type="number" id="discountValue" class="form-control" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="minOrderValue" class="form-label">Minimun Value</label>
                        <input type="number" id="minOrderValue" class="form-control" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="maxDiscountAmount" class="form-label">Maximum Value</label>
                        <input type="number" id="maxDiscountAmount" class="form-control" required>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="startDate" class="form-label">Start Date</label>
                        <input type="datetime-local" id="startDate" class="form-control" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="endDate" class="form-label">End Date</label>
                        <input type="datetime-local" id="endDate" class="form-control" required>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">Add Voucher</button>
            </form>
        </div>
    </div>

    <!-- Danh sách Voucher -->
    <div class="card">
        <div class="card-header">List Voucher</div>
        <div class="card-body">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Code Voucher</th>
                        <th>Description</th>
                        <th>Value (%)</th>
                        <th>Minimum Value</th>
                        <th>Used</th>
                        <th>Thời gian</th>
                        <th>Trạng thái</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody id="voucher-list">
                    <!-- Dữ liệu được load bằng JavaScript -->
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
    // Dữ liệu JSON mẫu
    const vouchers = [
        {
            "id": 1,
            "voucherCode": "VOUCHER001",
            "description": "Giảm giá 10% cho đơn hàng từ 200.000 VNĐ",
            "discountValue": 10,
            "minOrderValue": "200000.00",
            "maxUses": 100,
            "usedCount": 0,
            "startDate": "2024-11-01 00:00:00",
            "endDate": "2024-11-30 23:59:59",
            "isActive": 1,
            "createdAt": "2024-11-16 16:31:03",
            "updatedAt": "2024-11-16 16:31:03",
            "max_discount_amount": "300000.00"
        },
        {
            "id": 2,
            "voucherCode": "VOUCHER002",
            "description": "Giảm giá 20% cho sản phẩm A",
            "discountValue": 20,
            "minOrderValue": "100000.00",
            "maxUses": 50,
            "usedCount": 19,
            "startDate": "2024-11-01 00:00:00",
            "endDate": "2024-11-30 23:59:59",
            "isActive": 1,
            "createdAt": "2024-11-16 16:31:03",
            "updatedAt": "2024-11-16 16:31:03",
            "max_discount_amount": "40000.00"
        }
    ];

    // Hàm load dữ liệu voucher
    function loadVouchers() {
        const voucherList = document.getElementById('voucher-list');
        voucherList.innerHTML = '';

        vouchers.forEach((voucher, index) => {
            voucherList.innerHTML += `
                <tr>
                    <td>${index + 1}</td>
                    <td>${voucher.voucherCode}</td>
                    <td>${voucher.description}</td>
                    <td>${voucher.discountValue}%</td>
                    <td>${Number(voucher.minOrderValue).toLocaleString()} VNĐ</td>
                    <td>${voucher.usedCount}/${voucher.maxUses}</td>
                    <td>${voucher.startDate} - ${voucher.endDate}</td>
                    <td>${voucher.isActive ? 'Hoạt động' : 'Không hoạt động'}</td>
                    <td>
                        <button class="btn btn-sm btn-warning" onclick="editVoucher(${voucher.id})">Sửa</button>
                        <button class="btn btn-sm btn-danger" onclick="deleteVoucher(${voucher.id})">Xóa</button>
                    </td>
                </tr>
            `;
        });
    }

    // Hàm sửa voucher
    function editVoucher(id) {
        alert(`Sửa voucher với ID: ${id}`);
        // Logic sửa voucher
    }

    // Hàm xóa voucher
    function deleteVoucher(id) {
        if (confirm('Bạn có chắc chắn muốn xóa voucher này?')) {
            alert(`Xóa voucher với ID: ${id}`);
            // Logic xóa voucher
        }
    }

    // Load dữ liệu khi trang sẵn sàng
    document.addEventListener('DOMContentLoaded', loadVouchers);
</script>
@endsection
