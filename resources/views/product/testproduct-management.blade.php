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
            </div>

            {{-- Lọc - ban đầu vô hiệu hóa nút danh mục con, khi chọn danh mục r sẽ load danh mục con theo danh mục cha --}}
            <div class="col-md-6 col-filter">
                <div class="form_filter">
                    <label>Tên danh mục</label>
                    <select id="user-filter" class="dropdown">
                        <option value="">Nhẫn</option>
                        <option value="">Dây chuyền</option>
                        <option value="">Bông tai</option>
                    </select>
                </div>

                <div class="form_filter">
                    <label>Danh mục con</label>
                    <select id="user-filter" class="dropdown">
                        <option value="">Nhẫn trơn</option>
                        <option value="">Nhẫn đính hạt</option>
                        <option value="">Nhẫn gì đó</option>
                    </select>
                </div>
            </div>
        </div>

        {{-- phần bảng sản phẩm --}}
        <div class="row row-data">
            <div class="col-md-12">
                <table>
                    <thead class="header_table">
                        <tr>
                            <th></th>
                            <th>Tên sản phẩm</th>
                            <th>Danh mục</th>
                            <th>Danh mục con</th>
                            <th>Ngày cập nhật</th>
                            <th></th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="img_product text-center">
                                <img src="https://picsum.photos/200/300" alt="img" />
                            </td>
                            <td>Nhẫn gì hok bic</td>
                            <td>Nhẫn</td>
                            <td>Danh mục đây</td>
                            <td>20/11/2024</td>
                            <td class="text-center">
                                <button class="btn__edit-product" data-bs-toggle="modal" data-bs-target="#modalProduct"><i
                                        class="fa-solid fa-pen-to-square"></i>Chỉnh sửa</button>
                                <button class="btn__delete-product"><i class="fa-solid fa-trash"></i>Xóa</button>
                            </td>
                            <td class="text-center">
                                <button class="btn__view-variant" data-bs-toggle="modal" data-bs-target="#modalVariant">Xem
                                    biến thể</button>
                            </td>
                        </tr>
                    </tbody>
                </table>

                {{-- Modal chỉnh sửa thông tin sản phẩm: có tên - danh mục - danh mục con - mô tả - hình ảnh --}}
                <div class="modal fade" id="modalProduct">
                    <div class="modal-dialog customer__modal-info">
                        <div class="modal-content">

                            <!-- Modal body -->
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="row">
                                            <div class="col-md-12 form__input-product">
                                                <label for="productName">Tên sản phẩm</label>
                                                <input id="productName" value="Tên sản phẩm nè"
                                                    placeholder="Tên sản phẩm" />
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6 form__input-product">
                                                <label for="categoryName">Danh mục</label>
                                                <input id="categoryName" value="Tên danh mục nè" placeholder="Danh mục" />
                                            </div>
                                            <div class="col-md-6 form__input-product">
                                                <label for="subCategoryName">Danh mục con</label>
                                                <input id="subCategoryName" value="Têm Danh mục con"
                                                    placeholder="Danh mục con" />
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <label for="productDes">Mô tả sản phẩm</label>
                                            <textarea id="productDes" class="product-des" placeholder="Nhập mô tả sản phẩm" rows="5"></textarea>
                                        </div>
                                    </div>
                                    {{-- cột hình ảnh --}}
                                    <div class="col-md-4">
                                        <input type="file" class="form-control" accept="image/*" multiple
                                            id="imageInput" />
                                        <div id="imagePreview"></div>
                                    </div>
                                </div>
                            </div>

                            <!-- Modal footer -->
                            <div class="modal-footer">
                                <button class="btn__update-product">Cập nhật</button>
                                <button type="button" class="btn btn-danger btn__close-modal"
                                    data-bs-dismiss="modal">Đóng</button>
                            </div>

                        </div>
                    </div>
                </div>

                {{-- modal xem biến thể của sản phẩm nè --}}
                <div class="modal fade" id="modalVariant">
                    <div class="modal-dialog custom__modal">
                        <div class="modal-content">
                            <!-- Modal body -->
                            <div class="modal-body">
                                <div class="modal__title-detailVariant">
                                    <h5>Danh sách biến thể của sản phẩm: <span>Tên sản phẩm</span></h5>
                                </div>
                                <div class="table__data-variant">
                                    <table>
                                        <thead class="header__table-variant">
                                            <tr>
                                                <th>Kích cỡ</th>
                                                <th>Giá (VNĐ)</th>
                                                <th>Số lượng tồn</th>
                                                <th>Trạng thái</th>
                                                <th>Ngày cập nhật</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody id="variantTableBody">
                                            <tr>
                                                <td><input value="L" readonly /></td>
                                                <td><input value="5.000.000" readonly /></td>
                                                <td><input value="100" readonly /></td>
                                                <td><input value="Còn kinh doanh" readonly /></td>
                                                <td><input value="20/11/2024" readonly /></td>
                                                <td><i class="fa-solid fa-trash btn__detele-variant"
                                                        style="color: #ff0000;" data-bs-toggle="tooltip"
                                                        title="Xóa biến thể"></i></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <!-- Modal footer -->
                            <div class="modal-footer">
                                <div class="col-md-8">
                                    <button class="btn__edit-variant">Chỉnh sửa biến thể</button>
                                    <button class="btn__save-variant"><i class="fa-solid fa-floppy-disk"></i>Lưu
                                        thay đổi</button>
                                    <button class="btn__add-variant">+ Thêm biến thể</button>
                                </div>
                                <div class="col-md-4 btn__close-modal">
                                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Đóng</button>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

            </div>
        </div>


    </div>
@endsection

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

    {{-- xử lý btn chỉnh sửa, thêm biến thể với btn lưu  trong modal biến thể sp --}}
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const btnEditVariant = document.querySelector(".btn__edit-variant");
            const btnSaveVariant = document.querySelector(".btn__save-variant");
            const btnAddVariant = document.querySelector(".btn__add-variant"); // Nút Thêm biến thể
            const inputs = document.querySelectorAll(".table__data-variant input");
            const variantTableBody = document.getElementById("variantTableBody"); // tbody để thêm dòng mới

            // Thiết lập các input là readonly khi lần đầu tải trang
            inputs.forEach(input => {
                input.setAttribute("readonly", true);
            });
            btnSaveVariant.disabled = true;

            // Sự kiện chỉnh sửa biến thể
            btnEditVariant.addEventListener("click", () => {
                inputs.forEach(input => {
                    input.removeAttribute("readonly");
                });
                btnSaveVariant.disabled = false;
            });

            // Sự kiện lưu thay đổi
            btnSaveVariant.addEventListener("click", () => {
                inputs.forEach(input => {
                    input.setAttribute("readonly", true);
                });
                btnSaveVariant.disabled = true;
                alert("Thay đổi đã được lưu!");
            });

            // Sự kiện thêm biến thể
            btnAddVariant.addEventListener("click", () => {
                // Tạo dòng mới
                const newRow = document.createElement("tr");

                // Tạo các ô input trong dòng mới
                const sizeCell = document.createElement("td");
                const priceCell = document.createElement("td");
                const quantityCell = document.createElement("td");
                const statusCell = document.createElement("td");
                const updateDateCell = document.createElement("td");
                const deleteCell = document.createElement("td");

                sizeCell.innerHTML = '<input value="" placeholder="Kích cỡ" />';
                priceCell.innerHTML = '<input value="" placeholder="Giá (VNĐ)" />';
                quantityCell.innerHTML = '<input value="" placeholder="Số lượng tồn" />';
                statusCell.innerHTML = '<input value="" placeholder="Trạng thái" />';
                updateDateCell.innerHTML = '<input value="" placeholder="Ngày cập nhật" />';
                deleteCell.innerHTML =
                    '<i class="fa-solid fa-trash btn__detele-variant"style="color: #ff0000;" data-bs-toggle="tooltip" title="Xóa biến thể"></i>'

                // Thêm các ô vào dòng mới
                newRow.appendChild(sizeCell);
                newRow.appendChild(priceCell);
                newRow.appendChild(quantityCell);
                newRow.appendChild(statusCell);
                newRow.appendChild(updateDateCell);
                newRow.appendChild(deleteCell);

                // Thêm dòng mới vào tbody
                variantTableBody.appendChild(newRow);
                btnSaveVariant.disabled = false;
            });
        });
    </script>

    {{-- tooltip cho buttn xó --}}
    <script>
        // Initialize tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        })
    </script>

    {{-- xem trước ảnh lúc upload --}}
    <script>
        document.getElementById("imageInput").addEventListener("change", function(e) {
            const files = e.target.files;
            const preview = document.getElementById("imagePreview");
            preview.innerHTML = "";

            for (let i = 0; i < files.length; i++) {
                const file = files[i];
                const reader = new FileReader();

                reader.onload = function(event) {
                    const img = document.createElement("img");
                    img.src = event.target.result;
                    img.style.width = "100px"; 
                    img.style.margin = "5px";
                    preview.appendChild(img);
                };

                reader.readAsDataURL(file); 
            }
        });
    </script>



@endsection
