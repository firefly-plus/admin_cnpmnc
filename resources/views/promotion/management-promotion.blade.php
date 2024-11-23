@extends('layout.index')
@section('title','Management Promotion')
@section('css')
<style>
    .promotion-info {
        margin-bottom: 30px;
    }
    .promotion-info label {
        font-weight: bold;
    }
    .product-list h3 {
        margin-top: 20px;
        font-size: 20px;
    }
</style>
@endsection

@section('content')
<div class="container">
    <!-- Phần thông tin giảm giá -->
    <div class="promotion-info">
        <h3>Promotion Information</h3>
        <form id="promotion-form">
            <div class="form-group">
                <label for="discount">Discount Percentage (%):</label>
                <input type="number" id="discount" name="discount" class="form-control" placeholder="Enter discount percentage" min="1" max="100" required>
            </div>
            <div class="form-group">
                <label for="start_date">Start Date:</label>
                <input type="date" id="start_date" name="start_date" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="end_date">End Date:</label>
                <input type="date" id="end_date" name="end_date" class="form-control" required>
            </div>
        </form>
    </div>

    <!-- Phần danh sách sản phẩm -->
    <div class="product-list">
        <h3>Product List</h3>
        <table class="table">
            <thead>
                <tr>
                    <th><input type="checkbox" id="select-all"> Select All</th>
                    <th>Product Name</th>
                    <th>Price</th>
                </tr>
            </thead>
            <tbody id="product-table-body">
               
                    <tr>
                        <td><input type="checkbox" class="product-checkbox" data-product-id=""></td>
                        <td>1</td>
                        <td>1</td>
                    </tr>
                    <tr>
                        <td><input type="checkbox" class="product-checkbox" data-product-id=""></td>
                        <td>1</td>
                        <td>1</td>
                    </tr>
                    <tr>
                        <td><input type="checkbox" class="product-checkbox" data-product-id=""></td>
                        <td>1</td>
                        <td>1</td>
                    </tr>
                    <tr>
                        <td><input type="checkbox" class="product-checkbox" data-product-id=""></td>
                        <td>1</td>
                        <td>1</td>
                    </tr>
              
            </tbody>
        </table>
        <button type="button" id="apply-discount" class="btn btn-success">Apply Discount</button>
    </div>
</div>
@endsection

@section('js')
<script>
    $(document).ready(function() {
        // Chọn tất cả sản phẩm
        $('#select-all').on('click', function() {
            $('.product-checkbox').prop('checked', this.checked);
        });

        // Bắt sự kiện khi nhấn nút "Apply Discount"
        $('#apply-discount').on('click', function() {
            let discount = $('#discount').val();
            let startDate = $('#start_date').val();
            let endDate = $('#end_date').val();
            let selectedProducts = [];

            // Lấy các sản phẩm đã chọn
            $('.product-checkbox:checked').each(function() {
                selectedProducts.push($(this).data('product-id'));
            });

            // Kiểm tra các trường nhập liệu
            if (selectedProducts.length === 0) {
                alert("Please select at least one product.");
                return;
            }
            if (!discount || !startDate || !endDate) {
                alert("Please fill in all fields.");
                return;
            }

            // Thực hiện gửi AJAX hoặc xử lý tiếp
            alert("Discount applied to selected products.");
        });
    });
</script>
@endsection
