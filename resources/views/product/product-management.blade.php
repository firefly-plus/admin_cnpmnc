@php
    $permissions = session('employee_permissions', collect())->toArray(); 
   
@endphp
@extends('layout.index')
@section('title', 'Danh sách sản phẩm')

@section('css')
    <!-- Thêm CSS tùy chỉnh nếu cần -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <style>
        .wrapper {
            min-height: 100vh;
            padding: 0 30px;
        }

        /* css của row_action */
        .row_action {
            display: flex;
            justify-content: space-between;
            border-bottom: 1px solid #8a8a8a;
            padding: 10px 0;
        }

        .btn_excel {
            background-color: #ffffff;
            border: 2px solid #1ac100;
            color: #1ac100;
            padding: 5px 10px;
            margin-right: 30px;
            border-radius: 10px;
            cursor: pointer;
            transition: all .1s linear;
        }

        .btn_excel:hover {
            background-color: #1ac100;
            color: #ffffff;
            box-shadow: rgba(50, 50, 93, 0.25) 0px 2px 5px -1px, rgba(0, 0, 0, 0.3) 0px 1px 3px -1px;
        }

        .btn_addproduct {
            background-color: #18a103;
            color: #ffffff;
            border: 2px solid #1ac100;
            border-radius: 10px;
            padding: 5px 10px;
            cursor: pointer;
        }

        .btn_addproduct:hover {
            background-color: #ffffff;
            color: #18a103;
            box-shadow: rgba(50, 50, 93, 0.25) 0px 2px 5px -1px, rgba(0, 0, 0, 0.3) 0px 1px 3px -1px;
        }

        .col_showproduct {
            text-align: end;
        }

        .col_showproduct label {
            margin-right: 20px;
            font-size: 1.2rem;
        }

        .col_showproduct select {
            padding: 4px 10px;
        }

        /* css của row search + filter */
        .row__searchFilter {
            margin-top: 20px;
        }

        .form_search .search-container {
            position: relative;
            width: 100%;
        }

        .form_search input {
            width: 100%;
            padding-right: 40px;
            padding-left: 10px;
            height: 40px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 14px;
            box-sizing: border-box;
        }

        .form_search i {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 16px;
            color: #666;
            cursor: pointer;
        }

        .col-filter {
            display: flex;
            align-items: center;
            justify-content: space-around;
        }

        .form_filter select {
            padding: 4px 10px;
        }

        /* khúc này là css của bảng sản phẩm*/
        .row-data {
            margin-top: 40px;
        }

        .header_table {
            background-color: #22009e;
            color: #fff;
        }

        .img_product img {
            width: 40px;
            height: 40px;
        }


        .btn__edit-product,
        .btn__delete-product,
        .btn__view-variant {
            border-radius: 10px;
            padding: 5px 10px;
            cursor: pointer;
            background-color: #fff;
        }

        .btn__edit-product i,
        .btn__delete-product i {
            margin-right: 5px;
        }


        .btn__edit-product {
            border: 1px solid #119000;
            color: #119000;
            margin-right: 20px;
        }

        .btn__delete-product {
            border: 1px solid #ff2929;
            color: #ff2929;
        }

        .btn__view-variant {
            border: 1px solid #ff9900;
            color: #ff9900;
        }

        /* nảy của modal sửa sp */
        .customer__modal-info {
            max-width: 1000px;
            margin-top: -50px;
        }

        .product-des {
            min-height: 200px;
            width: 100%;
        }

        .form__input-product {
            padding: 0 30px;
        }

        .form__input-product input {
            width: 100%;
            margin-bottom: 20px;
        }

        /* này css của cái modal dnh sách biến thể của sp */
        .custom__modal {
            max-width: 1100px;
            margin-top: -50px;
            margin-right: 130px;
        }

        .modal-content {
            padding: 20px 10px;
        }

        .modal__title-detailVariant {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 1px solid #656565;
        }

        .modal__title-detailVariant h5 {
            font-size: 1.2rem;
            font-weight: 400;
        }

        .modal__title-detailVariant span {
            font-size: 1.2rem;
            font-weight: 600;
        }

        .header__table-variant {
            background: #ccc;
            border: 1px solid #ccc;
        }

        .btn__save-variant,
        .btn__edit-variant,
        .btn__add-variant,
        .btn__update-product {
            margin-top: 20px;
            border-radius: 10px;
            padding: 5px 10px;
            color: #fff;
            font-size: 1.1rem;
            cursor: pointer;
            transition: all .1s linear;
        }

        .btn__save-variant {
            margin-left: 20px;
            background-color: rgb(0, 197, 36);
            border: 1px solid rgb(0, 197, 36);
        }

        .btn__save-variant:disabled {
            background-color: #ccc;
            color: #666;
            cursor: not-allowed;
        }

        .btn__add-variant, .btn__update-product {
            margin-left: 20px;
            background-color: rgb(0, 197, 36);
            border: 1px solid rgb(0, 197, 36);
        }

        .btn__save-variant i {
            margin-right: 5px;
        }

        .btn__edit-variant {
            background-color: #3e37ff;
            border: 1px solid #3e37ff;
        }

        .btn__edit-variant:hover {
            background-color: #fff;
            color: #3e37ff;
            box-shadow: rgba(99, 99, 99, 0.2) 0px 2px 8px 0px;
        }

        .btn__close-modal {
            margin-top: 20px;
            text-align: end;
        }

        .table__data-variant {
            max-height: 400px;
            overflow-y: auto;
            border: 1px solid #ccc;
        }

        .table__data-variant thead {
            position: sticky;
            top: 0;
            z-index: 1;
        }

        .btn__detele-variant {
            cursor: pointer;
        }
        #variant-table input[type="number"] {
            max-width: 100px;
        }
        .input-group {
            max-width: 200px;
        }
    </style>

@endsection

@section('content')
    <div class="col-md-12 wrapper">

        {{-- phần thêm sản phẩm + show sản phẩm --}}
        <div class="row row_action">
            <div class="col-md-8">
                <button class="btn_excel"><i class="fa-solid fa-file-excel"></i> Nhập từ Excel</button>
                <button class="btn_excel"><i class="fa-solid fa-file-excel"></i> Xuất ra Excel</button>
                <button class="btn_addproduct"> + Thêm sản phẩm</button>
            </div>

            <div class="col-md-4 col_showproduct">
                <label>Số lượng sản phẩm hiển thị:</label>
                <select id="user-filter" class="dropdown">
                    <option value="10">10</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                </select>
            </div>
        </div>

        {{-- phần search + lọc sản phẩm --}}
        <div class="row row__searchFilter">
            <div class="col-md-6">
                {{-- search --}}
                <div class="form_search">
                    <form>
                        <div class="search-container">
                            <input type="text" placeholder="Tìm kiếm sản phẩm..." />
                            <i class="fa-solid fa-magnifying-glass"></i>
                        </div>
                    </form>
                </div>
                <div class="modal-body">
                    <!-- Tên sản phẩm -->
                    <div class="mb-3">
                        <label for="productName" class="form-label">Tên sản phẩm</label>
                        <input type="text" class="form-control" id="productName" name="productName" required>
                    </div>
                    <div class="mb-3">
                        <label for="productDescription" class="form-label">Mô tả sản phẩm</label>
                        <textarea class="form-control" id="productDescription" name="productDescription" rows="3"></textarea>
                    </div>
                    <!-- Giá -->
                    {{-- <div class="mb-3">
                        <label for="productPrice" class="form-label">Giá</label>
                        <input type="number" class="form-control" id="productPrice" name="productPrice" required>
                    </div>

                    <!-- Số lượng -->
                    <div class="mb-3">
                        <label for="productStock" class="form-label">Số lượng</label>
                        <input type="number" class="form-control" id="productStock" name="productStock" required>
                    </div> --}}

                    <!-- Ảnh sản phẩm -->
                    <div class="mb-3">
                        <label for="productImages" class="form-label">Hình ảnh</label>
                        <input type="file" class="form-control" id="productImages" name="productImages[]" multiple>
                    </div>

                    <!-- Biến thể sản phẩm -->
                    <div class="mb-3">
                        <label class="form-label">Biến thể sản phẩm</label>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Kích thước</th>
                                    <th>Giá</th>
                                    <th>Số lượng</th>
                                    
                                </tr>
                            </thead>
                            <tbody id="variationTableBody">
                                <!-- Các dòng biến thể sẽ được thêm tại đây -->
                            </tbody>
                        </table>
                        {{-- <button type="button" class="btn btn-sm btn-secondary" id="addVariationRow">Thêm biến thể</button> --}}
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
                </div>
            </form>
        </div>
    </div>
</div>


@endsection

@section('js')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    {{-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.0/dist/js/bootstrap.bundle.min.js"></script> --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
       document.querySelectorAll('.edit-btn').forEach(button => {
            button.addEventListener('click', function () {
                const productId = this.dataset.id;
                const productName = this.dataset.name;
                const productPrices = this.dataset.price ? this.dataset.price.split(', ') : [];
                const productStocks = this.dataset.stock ? this.dataset.stock.split(', ') : [];
                const productSizes = this.dataset.size ? this.dataset.size.split(', ') : [];
                const productDescription= this.dataset.description;
                // Gán dữ liệu cơ bản
                document.getElementById('productId').value=productId;
                document.getElementById('productName').value = productName;
                document.getElementById('productDescription').value=productDescription;
                // Hiển thị các biến thể trong bảng
                const variationTableBody = document.getElementById('variationTableBody');
                variationTableBody.innerHTML = ''; // Xóa các dòng cũ

                productSizes.forEach((size, index) => {
                    const newRow = document.createElement('tr');
                    newRow.innerHTML = `
                        <td><input type="text" class="form-control" name="variations[size][]" value="${size}" required></td>
                        <td><input type="number" class="form-control" name="variations[price][]" value="${productPrices[index] || ''}" required></td>
                        <td><input type="number" class="form-control" name="variations[stock][]" value="${productStocks[index] || ''}" required></td>
                       
                    `;
                    variationTableBody.appendChild(newRow);
                });

                // Thêm sự kiện xóa cho các dòng biến thể
                bindRemoveVariationEvent();
            });
        });

        // Thêm biến thể mới
        document.getElementById('addVariationRow').addEventListener('click', function () {
            const variationTableBody = document.getElementById('variationTableBody');
            const newRow = document.createElement('tr');
            newRow.innerHTML = `
                <td><input type="text" class="form-control" name="variations[size][]" placeholder="Kích thước" required></td>
                <td><input type="number" class="form-control" name="variations[price][]" placeholder="Giá" required></td>
                <td><input type="number" class="form-control" name="variations[stock][]" placeholder="Số lượng" required></td>
               
            `;
            variationTableBody.appendChild(newRow);

            // Gắn sự kiện xóa cho dòng mới
            bindRemoveVariationEvent();
        });

        // Gắn sự kiện xóa cho các nút xóa dòng biến thể
        function bindRemoveVariationEvent() {
            document.querySelectorAll('.remove-variation').forEach(button => {
                button.addEventListener('click', function () {
                    this.closest('tr').remove();
                });
            });
        }

    </script>
    <script>
        $(document).on('click', '.edit-btn', function () {
            // // Lấy dữ liệu từ nút
            // const id = $(this).data('id');
            // const name = $(this).data('name');
            // const price = $(this).data('price');
            // const stock = $(this).data('stock');
    
            // // Điền dữ liệu vào modal
            // $('#editProductForm').attr('action', `/capnhatsanpham/${id}`); // Đặt action cho form
            // $('#productName').val(name);
            // $('#productPrice').val(price);
            // $('#productStock').val(stock);
    
            // Hiển thị modal
            $('#editProductModal').modal('show');
        });
    </script>
@endsection
