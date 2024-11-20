{{-- <!-- Modal Sửa Sản Phẩm -->
<div class="modal fade" id="editProductModal" tabindex="-1" aria-labelledby="editProductModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editProductModalLabel">Sửa Sản Phẩm</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editProductForm" method="POST" action="/suasanpham">
                @csrf
               
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="editProductName" class="form-label">Tên Sản Phẩm</label>
                        <input type="text" class="form-control" id="editProductName" name="productName" required>
                    </div>
                    <div class="mb-3">
                        <label for="editSubCategory">Danh mục con</label>
                        <select class="form-control" id="editSubCategory" name="subCategoryID">
                            @foreach($categories as $subCategory)
                                <option value="{{ $subCategory->id }}">{{ $subCategory->SupCategoryName }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="editProductDescription" class="form-label">Mô Tả</label>
                        <textarea class="form-control" id="editProductDescription" name="description" rows="3"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="editProductImage" class="form-label">Hình Ảnh (Tùy chọn)</label>
                        <input type="file" class="form-control" id="editProductImage" name="productImages[]" multiple>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                    
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    document.querySelectorAll('.edit-product-btn').forEach(button => {
    button.addEventListener('click', function () {
        // Lấy dữ liệu từ các data attribute
        const id = this.dataset.id;
        const name = this.dataset.name;
        const subCategory = this.dataset.subCategory;
        const description = this.dataset.description;

        // Điền dữ liệu vào modal
        document.getElementById('editProductName').value = name;
        document.getElementById('editProductDescription').value = description;

        const subCategorySelect = document.getElementById('editSubCategory');
        Array.from(subCategorySelect.options).forEach(option => {
            option.selected = option.value ===  String(subCategory); // So sánh theo value (ID)
        });
        // Cập nhật action URL của form
        const form = document.getElementById('editProductForm');
        // form.action = `/suasanpham/${id}`; // Đường dẫn route RESTful
    });
});




</script> --}}

<!-- Modal Sửa Sản Phẩm -->
<div class="modal fade" id="editProductModal" tabindex="-1" aria-labelledby="editProductModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editProductModalLabel">Sửa Sản Phẩm</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editProductForm" method="POST" action="/suasanpham" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="editProductName" class="form-label">Tên Sản Phẩm</label>
                        <input type="text" class="form-control" id="editProductName" name="productName" required>
                    </div>
                    <div class="mb-3">
                        <label for="editSubCategory">Danh mục con</label>
                        <select class="form-control" id="editSubCategory" name="subCategoryID">
                            @foreach($categories as $subCategory)
                                <option value="{{ $subCategory->id }}">{{ $subCategory->SupCategoryName }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="editProductDescription" class="form-label">Mô Tả</label>
                        <textarea class="form-control" id="editProductDescription" name="description" rows="3"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="editProductImage" class="form-label">Hình Ảnh (Tùy chọn)</label>
                        <input type="file" class="form-control" id="editProductImage" name="productImages[]" multiple>
                    </div>
                </div>
                <input type="hidden" id="productId" name="id"> <!-- Thêm input hidden để lưu ID sản phẩm -->
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.querySelectorAll('.edit-product-btn').forEach(button => {
        button.addEventListener('click', function () {
            // Lấy dữ liệu từ các data attribute
            const id = this.dataset.id;
            const name = this.dataset.name;
            const subCategory = this.dataset.subCategory;
            const description = this.dataset.description;

            // Điền dữ liệu vào modal
            document.getElementById('editProductName').value = name;
            document.getElementById('editProductDescription').value = description;

            const subCategorySelect = document.getElementById('editSubCategory');
            Array.from(subCategorySelect.options).forEach(option => {
                option.selected = option.value === String(subCategory); // So sánh theo value (ID)
            });

            // Cập nhật value cho input hidden chứa ID
            document.getElementById('productId').value = id;

            // Cập nhật action URL của form (nếu cần)
            const form = document.getElementById('editProductForm');
            // Không cần thay đổi URL vì đã giữ `/suasanpham` cho tất cả các sản phẩm
        });
    });
</script>
