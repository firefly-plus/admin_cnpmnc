@extends('layout.index')
@section('title','Category Management')
@section('css')
<style>
    .table-container {
        max-height: 400px;
        overflow-y: auto;
    }

    .table th, .table td {
        vertical-align: middle;
    }
</style>
@endsection

@section('content')
<div class="col-md-12">
    <div class="container">
        <!-- Dòng trạng thái đơn hàng -->
       

        <!-- Các chức năng lọc và tìm kiếm -->
        <div class="row">
            <div class="col-md-4">
                <input type="date" class="form-control" id="filter-date">
            </div>

            <div class="col-md-4">
                <select class="form-control" id="filter-status">
                    <option value="">Chọn trạng thái</option>
                    <option value="all">Tất Cả</option>
                    <option value="chothanhtoan">Chờ Thanh Toán</option>
                    <option value="choxacnhan">Chờ Xác Nhận</option>
                    <option value="cholayhang">Chờ Lấy Hàng</option>
                    <option value="danggiahang">Đang Giao Hàng</option>
                    <option value="trahang">Trả Hàng</option>
                    <option value="duocgiao">Được Giao</option>
                    <option value="dahuy">Đã Hủy</option>
                </select>
            </div>

            <div class="col-md-4">
                <select class="form-control" id="filter-price">
                    <option value="">Chọn giá lớn hơn</option>
                    <option value="100000">Lớn hơn 100.000 đ</option>
                    <option value="500000">Lớn hơn 500.000 đ</option>
                    <option value="1000000">Lớn hơn 1.000.000 đ</option>
                    <option value="5000000">Lớn hơn 5.000.000 đ</option>
                </select>
            </div>
        </div>

        <div class="row" style="margin-top: 10px">
            <div class="col-md-8">
                <input type="text" class="form-control" id="search-invoice" placeholder="Tìm kiếm mã đơn hàng">
            </div>
            <div class="col-md-4">
                <button class="btn btn-primary w-100" id="search-button">Tìm kiếm</button>
            </div>
        </div>
    </div>
</div>

<div class="col-md-12">
    <div class="tile">
        <div class="tile-body">
            <div class="row element-button">
                <div class="col-sm-2">
                    <a class="btn btn-add btn-sm" href="form-add-don-hang.html" title="Thêm"><i class="fas fa-plus"></i> Tạo mới đơn hàng</a>
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
                            <th>Code Invoice</th>
                            <th>Code Voucher</th>
                            <th>Phone</th>
                            <th>Address</th>
                            <th>Payment Method</th>
                            <th>Final Amount</th>
                            <th>Status</th>
                            <th>Func</th>
                        </tr>
                    </thead>
                    <tbody id="invoice-body">
                        
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
                    <tbody>
                        <!-- Chi tiết hóa đơn sẽ được hiển thị tại đây -->
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
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="libs/jsPDF/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
@section('js')
<script>
  $(document).ready(function() {
    // Hàm hiển thị chi tiết hóa đơn
    function showInvoiceDetail(invoiceId) {
        console.log("Fetching invoice details for ID:", invoiceId);

        $.ajax({
            url: '/invoice?id=' + invoiceId, // Lấy chi tiết hóa đơn từ API
            method: 'GET',
            success: function(response) {
                console.log("Invoice details fetched:", response);

                if (response && response.length > 0) {
                    // Lấy thông tin hóa đơn từ API
                    let invoiceData = response[0];

                    // Cập nhật các trường thông tin vào modal
                    $('#invoice-id').text(invoiceData.id);
                    $('#invoice-voucher-code').text(invoiceData.voucherCode || 'N/A');
                    $('#invoice-phone').text(invoiceData.phoneNumber || 'N/A');
                    $('#invoice-address').text(invoiceData.shippingAddress || 'N/A');
                    $('#invoice-payment-method').text(invoiceData.paymentMethod || 'N/A');
                    $('#invoice-final-amount').text(invoiceData.finalAmount || '0');
                    $('#invoice-status').text(invoiceData.orderStatus || 'Chưa xác định');

                    // Hiển thị chi tiết các sản phẩm trong hóa đơn
                    if (invoiceData.invoice_details && Array.isArray(invoiceData.invoice_details)) {
                        let invoiceDetailsHtml = '';
                        invoiceData.invoice_details.forEach(function(detail) {
                            invoiceDetailsHtml += `
                                <tr>
                                    <td>${detail.ID_productVariation}</td>
                                    <td>${detail.UnitPrice}</td>
                                    <td>${detail.Quantity}</td>
                                    <td>${detail.Amount}</td>
                                </tr>
                            `;
                        });
                        $('#invoice-details-table tbody').html(invoiceDetailsHtml);
                    } else {
                        $('#invoice-details-table tbody').html('<tr><td colspan="4">No product details available.</td></tr>');
                    }

                    // Mở modal để hiển thị chi tiết hóa đơn
                    $('#invoiceDetailModal').modal('show');
                }
            },
            error: function(xhr, status, error) {
                console.log("Error fetching invoice details:", error);
            }
        });
    }

    // Hàm lấy danh sách các hóa đơn và hiển thị trên bảng
    function getInvoices(filters = {}) {
        $.ajax({
            url: '/invoice',
            method: 'GET',
            data: filters,
            success: function(response) {
                var tableBody = $("#invoice-body");
                tableBody.empty(); // Làm sạch bảng trước khi thêm dữ liệu mới
                if (Array.isArray(response)) {
                    response.forEach(function(invoice) {
                        var row = `
                            <tr>
                                <td><input type='checkbox' name='check1' value='${invoice.id}'></td>
                                <td>${invoice.id}</td>
                                <td>${invoice.voucherCode || 'N/A'}</td>
                                <td>${invoice.phoneNumber || 'N/A'}</td>
                                <td>${invoice.address || 'N/A'}</td>
                                <td>${invoice.paymentMethod || 'N/A'}</td>
                                <td>${invoice.totalAmount || '0'}</td>
                                <td>${invoice.orderStatus || '0'}</td>
                                <td>
                                    <button class='btn btn-primary btn-sm view-detail' type='button' data-id='${invoice.id}' title='Xem Chi Tiết'><i class='fas fa-eye'></i></button>
                                </td>
                            </tr>`;
                        tableBody.append(row);
                    });

                    // Gắn sự kiện cho nút Xem Chi Tiết
                    $(".view-detail").on("click", function() {
                        var invoiceId = $(this).data("id");
                        showInvoiceDetail(invoiceId); // Gọi hàm để hiển thị chi tiết hóa đơn
                    });
                } else {
                    console.log("Data is not in expected format", response);
                }
            },
            error: function(xhr, status, error) {
                console.log("Error fetching invoices:", error);
            }
        });
    }

    // Lấy giá trị bộ lọc
    function getFilterValues() {
        return {
            createdAt: $('#filter-date').val(),
            orderStatus: $('#filter-status').val(),
            totalAmount: $('#filter-price').val()
        };
    }

    // Lọc dữ liệu khi thay đổi bộ lọc
    $("#filter-date, #filter-status, #filter-price").on("change", function() {
        const filters = getFilterValues();
        getInvoices(filters);
    });

    // Tìm kiếm theo mã đơn hàng
    $("#search-button").on("click", function() {
        const searchValue = $("#search-invoice").val().trim();
        if (searchValue) {
            getInvoices({ search: searchValue });
        } else {
            alert("Vui lòng nhập mã đơn hàng để tìm kiếm!");
        }
    });

    // Lấy danh sách hóa đơn khi tải trang
    getInvoices();
});

</script>


<script>
$('#export-selected-pdf').click(function() {
    var selectedInvoices = [];
    $('input[name="check1"]:checked').each(function() {
        var invoiceId = $(this).val();
        selectedInvoices.push(invoiceId);
    });

    if (selectedInvoices.length === 0) {
        alert('Vui lòng chọn ít nhất một hóa đơn!');
        return;
    }

    // Gửi danh sách hóa đơn dưới dạng mảng đến API
    $.ajax({
    url: '/export-pdf',
    method: 'GET',
    data: { invoices: selectedInvoices },
    success: function(response) {
        // Nếu xuất PDF thành công, có thể chuyển hướng hoặc làm gì đó
        if (response.pdfPath) {
                window.location.href = response.pdfPath;  // Chuyển hướng tới file PDF đã lưu
            }  // Chỉnh sửa theo đường dẫn thật của file PDF
    },
    error: function(xhr, status, error) {
        // Khi có lỗi xảy ra, ta sẽ log thông tin lỗi vào console và hiển thị thông báo lỗi
        console.log('Error: ' + error);  // Log thông báo lỗi ra console
        console.log('Status: ' + status);  // Log tình trạng của lỗi (ví dụ: timeout, error, v.v.)
        console.log('Response: ' + xhr.responseText);  // Log thông tin phản hồi từ server (nếu có)

        alert('Có lỗi xảy ra khi xuất PDF: ' + error);  // Hiển thị thông báo lỗi chi tiết cho người dùng
    }
});

});

</script>



@endsection
