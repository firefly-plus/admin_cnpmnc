@extends('layout.index')
@section('title', 'Quản lý ưu đãi')

@section('css')

@endsection

@section('content')
    <div class="container mt-4">
        <div class="card mb-4">
            <div class="card-header">Thêm voucher mới</div>
            <div class="card-body">
                <form id="add-voucher-form" action="/addvoucher" method="POST">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="voucherCode" class="form-label">Mã giảm giá</label>
                            <input type="text" id="voucherCode" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="description" class="form-label">Mô tả</label>
                            <input type="text" id="description" class="form-control" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="discountValue" class="form-label">Phần trăm giảm (%)</label>
                            <input type="number" id="discountValue" class="form-control" min="1" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="minOrderValue" class="form-label">Đơn tối thiểu</label>
                            <input type="number" id="minOrderValue" class="form-control" min="1" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="maxDiscountAmount" class="form-label">Giảm tối đa</label>
                            <input type="number" id="maxDiscountAmount" class="form-control" min="1" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-2">
                            <label for="maxUses" class="form-label">Số lượng</label>
                            <input type="number" id="maxUses" class="form-control" min="1" required>
                        </div>
                        <div class="col-md-5 mb-3">
                            <label for="startDate" class="form-label">Ngày bắt đầu</label>
                            <input type="datetime-local" id="startDate" class="form-control" required>
                        </div>
                        <div class="col-md-5 mb-3">
                            <label for="endDate" class="form-label">Ngày kết thúc</label>
                            <input type="datetime-local" id="endDate" class="form-control" required>
                        </div>
                    </div>
                    <div class="text-center"><button type="submit" class="btn btn-primary">Thêm voucher</button></div>
                </form>
            </div>
        </div>

        <!-- Danh sách Voucher -->
        <div class="card">
            <div class="card-header">Danh sách voucher</div>
            <div class="card-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Mã voucher</th>
                            <th>Mô tả</th>
                            <th>Phần trăm (%)</th>
                            <th>Giá tối thiểu</th>
                            <th>Giảm tối đa</th>
                            <th>Đã sử dụng</th>
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

        <!-- Modal để sửa voucher -->
        <div class="modal" id="editVoucherModal">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Sửa Voucher</h5>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <form id="edit-voucher-form">
                            <input type="hidden" id="editVoucherId" />
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="editVoucherCode" class="form-label">Mã giảm giá</label>
                                    <input type="text" id="editVoucherCode" class="form-control" required />
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="editDescription" class="form-label">Mô tả</label>
                                    <input type="text" id="editDescription" class="form-control" required />
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label for="editDiscountValue" class="form-label">Phần trăm giảm (%)</label>
                                    <input type="number" id="editDiscountValue" class="form-control" min="1" required />
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="editMinOrderValue" class="form-label">Đơn tối thiểu</label>
                                    <input type="number" id="editMinOrderValue" class="form-control"  min="1" required />
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="editMaxDiscountAmount" class="form-label">Giảm tối đa</label>
                                    <input type="number" id="editMaxDiscountAmount" class="form-control"  min="1" required />
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-2">
                                    <label for="editMaxUses" class="form-label">Số lượng</label>
                                    <input type="number" id="editMaxUses" class="form-control"  min="1" required />
                                </div>
                                <div class="col-md-5 mb-3">
                                    <label for="editStartDate" class="form-label">Ngày bắt đầu</label>
                                    <input type="datetime-local" id="editStartDate" class="form-control" required />
                                </div>
                                <div class="col-md-5 mb-3">
                                    <label for="editEndDate" class="form-label">Ngày kết thúc</label>
                                    <input type="datetime-local" id="editEndDate" class="form-control" required />
                                </div>
                            </div>
                            <div class="text-center"><button type="submit" class="btn btn-primary">Cập nhật
                                    voucher</button></div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection

@section('js')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
    <script>
        //thêm voucher mới
        document.getElementById("add-voucher-form").addEventListener("submit", async function(e) {
            e.preventDefault(); // Ngăn form submit mặc định

            // Lấy dữ liệu từ form
            const voucherCode = document.getElementById("voucherCode").value;
            const description = document.getElementById("description").value;
            const discountValue = parseFloat(document.getElementById("discountValue").value);
            const minOrderValue = parseFloat(document.getElementById("minOrderValue").value);
            const maxDiscountAmount = parseFloat(document.getElementById("maxDiscountAmount").value);
            const maxUses = parseInt(document.getElementById("maxUses").value, 10);
            const startDate = document.getElementById("startDate").value;
            const endDate = document.getElementById("endDate").value;

            // Kiểm tra ngày kết thúc không nhỏ hơn ngày bắt đầu
            if (new Date(endDate) < new Date(startDate)) {
                alert("Ngày kết thúc không được nhỏ hơn ngày bắt đầu!");
                return;
            }
            if (maxDiscountAmount < minOrderValue) {
                alert("Giảm tối đa không được nhỏ hơn đơn tối thiểu!");
                return;
            }

            const formData = {
                voucherCode,
                description,
                discountValue,
                minOrderValue,
                maxDiscountAmount,
                maxUses,
                startDate,
                endDate
            };

            try {
                // Gửi dữ liệu qua API
                const response = await fetch("/addvoucher", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                    },
                    body: JSON.stringify(formData),
                });

                if (!response.ok) {
                    throw new Error("Có lỗi xảy ra! Vui lòng kiểm tra lại.");
                }

                const result = await response.json();
                alert(result.message); // Hiển thị thông báo thành công
                loadVouchers();
            } catch (error) {
                console.error(error);
                alert("Thêm voucher thất bại. Vui lòng thử lại.");
            }
        });

        //hàm edit voucher
        function editVoucher(id) {
            fetch(`/getvoucher/${id}`)
                .then(response => response.json()) // Parse JSON nếu phản hồi thành công
                .then(voucher => {
                    // Điền dữ liệu vào form sửa
                    document.getElementById('editVoucherId').value = voucher.id;
                    document.getElementById('editVoucherCode').value = voucher.voucherCode;
                    document.getElementById('editDescription').value = voucher.description;
                    document.getElementById('editDiscountValue').value = voucher.discountValue;
                    document.getElementById('editMinOrderValue').value = voucher.minOrderValue;
                    document.getElementById('editMaxDiscountAmount').value = voucher.max_discount_amount;
                    document.getElementById('editMaxUses').value = voucher.maxUses;
                    document.getElementById('editStartDate').value = voucher.startDate;
                    document.getElementById('editEndDate').value = voucher.endDate;

                    // Hiển thị modal sửa
                    $('#editVoucherModal').modal('show');
                })
                .catch(error => {
                    console.error('Lỗi khi lấy dữ liệu voucher:', error);
                    alert('Không thể tải dữ liệu voucher. Vui lòng thử lại sau.');
                });
        }

        // Hàm xử lý submit form sửa voucher
        document.getElementById("edit-voucher-form").addEventListener("submit", async function(e) {
            e.preventDefault();

            const voucherId = document.getElementById("editVoucherId").value;
            const formData = {
                voucherCode: document.getElementById("editVoucherCode").value,
                description: document.getElementById("editDescription").value,
                discountValue: parseFloat(document.getElementById("editDiscountValue").value.replace(/\./g, ''), 10),
                minOrderValue: parseFloat(document.getElementById("editMinOrderValue").value.replace(/\./g, ''), 10),
                maxDiscountAmount: parseFloat(document.getElementById("editMaxDiscountAmount").value),
                maxUses: parseInt(document.getElementById("editMaxUses").value, 10),
                startDate: document.getElementById("editStartDate").value,
                endDate: document.getElementById("editEndDate").value
            };

            if (new Date(formData.endDate) < new Date(formData.startDate)) {
                alert("Ngày kết thúc không được nhỏ hơn ngày bắt đầu!");
                return;
            }

            if (formData.maxDiscountAmount < formData.minOrderValue) {
                alert("Giảm tối đa không được nhỏ hơn đơn tối thiểu!");
                return;
            }

            try {
                const response = await fetch(`/editvoucher/${voucherId}`, {
                    method: "PUT",
                    headers: {
                        "Content-Type": "application/json",
                    },
                    body: JSON.stringify(formData),
                });

                if (!response.ok) {
                    throw new Error("Có lỗi xảy ra! Vui lòng kiểm tra lại.");
                }

                const result = await response.json();
                alert(result.message);
                loadVouchers();
                $('#editVoucherModal').modal('hide');
            } catch (error) {
                console.error(error);
                alert("Cập nhật voucher thất bại. Vui lòng thử lại.");
            }
        });

        //xóa voucher
        function deleteVoucher(id) {
            const confirmDelete = window.confirm("Bạn có chắc chắn muốn xóa voucher này?");

            if (confirmDelete) {
                fetch(`/deletevoucher/${id}`, {
                        method: "DELETE",
                    })
                    .then(response => response.json())
                    .then(result => {
                        alert(result.message);
                        loadVouchers();
                    })
                    .catch(error => {
                        console.error('Lỗi khi xóa voucher:', error);
                        alert('Không thể xóa voucher. Vui lòng thử lại.');
                    });
            } else {
                console.log("Xóa voucher đã bị hủy.");
            }
        }

        // Hàm load dữ liệu voucher
        function loadVouchers() {
            const voucherList = document.getElementById('voucher-list');
            voucherList.innerHTML = '';

            fetch('/getvoucher')
                .then(response => response.json())
                .then(vouchers => {
                    vouchers.forEach((voucher, index) => {
                        voucherList.innerHTML += `
                        <tr>
                            <td>${index + 1}</td>
                            <td>${voucher.voucherCode}</td>
                            <td>${voucher.description}</td>
                            <td>${voucher.discountValue}%</td>
                            <td>${Number(voucher.minOrderValue).toLocaleString()} VNĐ</td>
                            <td>${Number(voucher.max_discount_amount).toLocaleString()} VNĐ</td>
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
                })
                .catch(error => {
                    console.error('Lỗi khi lấy dữ liệu voucher:', error);
                    alert('Không thể tải dữ liệu voucher. Vui lòng thử lại sau.');
                });
        }

        // Load dữ liệu khi trang sẵn sàng
        document.addEventListener('DOMContentLoaded', loadVouchers);
    </script>

    <script></script>
@endsection
