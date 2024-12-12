@extends('layout.index')
@section('title', 'Category Management')
@section('css')
<style>
    /* CSS tùy chỉnh nếu cần */
</style>
@endsection

@section('content')

<div class="col-md-12">
    <div class="tile">
        <div class="tile-body">
            <div class="row element-button">
                <div class="col-sm-2">
                    <a class="btn btn-add btn-sm" href="{{ url('form-add-don-hang.html') }}" title="Thêm"><i class="fas fa-plus"></i> Tạo mới đơn hàng</a>
                </div>
                <div class="col-sm-2">
                    <a class="btn btn-delete btn-sm nhap-tu-file" type="button" title="Nhập" onclick="myFunction(this)"><i class="fas fa-file-upload"></i> Tải từ file</a>
                </div>
                <div class="col-sm-2">
                    <a class="btn btn-delete btn-sm print-file" type="button" title="In" onclick="myApp.printTable()"><i class="fas fa-print"></i> In dữ liệu</a>
                </div>
                <div class="col-sm-2">
                    <a class="btn btn-delete btn-sm print-file js-textareacopybtn" type="button" title="Sao chép"><i class="fas fa-copy"></i> Sao chép</a>
                </div>
                <div class="col-sm-2">
                    <a class="btn btn-excel btn-sm" href="" title="In"><i class="fas fa-file-excel"></i> Xuất Excel</a>
                </div>
                <div class="col-sm-2">
                    <a class="btn btn-delete btn-sm pdf-file" type="button" title="In" id="export-selected-pdf"><i class="fas fa-file-pdf"></i> Xuất PDF</a>
                </div>
                <div class="col-sm-2">
                    <a class="btn btn-delete btn-sm" type="button" title="Xóa" onclick="myFunction(this)"><i class="fas fa-trash-alt"></i> Xóa tất cả </a>
                </div>
            </div>

            <div class="table-container">
                <table class="table table-hover table-bordered" id="sampleTable">
                    <thead>
                        <tr>
                            <th width="10"><input type="checkbox" id="all"></th>
                            <th>Mã Phiếu Nhập</th>
                            <th>Nhà Cung Cấp</th>
                            <th>Tạo Bởi</th>
                            <th>Ngày Nhập</th>
                            <th>Payment Method</th>
                            <th>Final Amount</th>
                            <th>Status</th>
                            <th>Func</th>
                        </tr>
                    </thead>
                    <tbody id="ware-body">
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal Chi Tiết Hóa Đơn -->
<div class="modal fade" id="invoiceDetailModal" tabindex="-1" role="dialog" aria-labelledby="invoiceDetailModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="invoiceDetailModalLabel">Chi Tiết Hóa Đơn</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p><strong>Mã Đơn Hàng:</strong> <span id="invoice-id"></span></p>
                <p><strong>Mã Voucher:</strong> <span id="invoice-voucher-code"></span></p>
                <p><strong>Số Điện Thoại:</strong> <span id="invoice-phone"></span></p>
                <p><strong>Địa Chỉ:</strong> <span id="invoice-address"></span></p>
                <p><strong>Phương Thức Thanh Toán:</strong> <span id="invoice-payment-method"></span></p>
                <p><strong>Số Tiền Cuối:</strong> <span id="invoice-final-amount"></span></p>
                <p><strong>Trạng Thái:</strong> <span id="invoice-status"></span></p>

                <table id="invoice-details-table" class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Product ID</th>
                            <th>Price</th>
                            <th>Quantity</th>
                            <th>Amount</th>
                        </tr>
                    </thead>
                    <tbody id="invoice-details-body">
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('js')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function () {
        $.ajax({
            url: 'http://localhost:8017/v1/orderSupplier/GetALL',
            method: 'GET',
            success: function (response) {
                if (response && Array.isArray(response)) {
                    $('#ware-body').empty();

                    response.forEach(item => {
                        const row = `
                            <tr>
                                <td><input type="checkbox"></td>
                                <td>${item.orderSupplier_id}</td>
                                <td>${item.supplier_name} (${item.supplier_phone})</td>
                                <td>${item.employee_name} (${item.employee_phone})</td>
                                <td>${new Date(item.OrderDate).toLocaleDateString()}</td>
                                <td>${item.payment_status}</td>
                                <td>${item.TotalPrice}</td>
                                <td>${item.order_status}</td>
                                <td>
                                    <button class="btn btn-info btn-sm" onclick="viewDetails('${item.orderSupplier_id}')">Xem</button>
                                </td>
                            </tr>
                        `;
                        $('#ware-body').append(row);
                    });
                } else {
                    $('#ware-body').html('<tr><td colspan="8">Không có dữ liệu</td></tr>');
                }
            },
            error: function (error) {
                console.error('Lỗi khi gọi API:', error);
                $('#ware-body').html('<tr><td colspan="8">Lỗi khi tải dữ liệu</td></tr>');
            }
        });
    });

    function viewDetails(orderId) {
        window.location.href = `/warehouse/${orderId}`;
    }
</script>
@endsection
