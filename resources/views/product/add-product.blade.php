{{-- <!-- Modal Thêm Sản Phẩm -->
<div class="modal fade" id="addProductModal" tabindex="-1" aria-labelledby="addProductModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="addProductForm" method="POST" enctype="multipart/form-data" action="/themsanpham">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="addProductModalLabel">Thêm Sản Phẩm</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Chọn danh mục -->
                    <div class="mb-3">
                        <label for="ID_SupCategory" class="form-label fw-bold">Danh mục sản phẩm</label>
                        <select name="ID_SupCategory" id="ID_SupCategory" class="form-select" required>
                            <option value="" selected>Chọn danh mục</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->SupCategoryName }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Tên sản phẩm -->
                    <div class="mb-3">
                        <label for="productName" class="form-label">Tên sản phẩm</label>
                        <input type="text" class="form-control" id="productName" name="productName" required maxlength="255">
                    </div>

                    <!-- Mô tả sản phẩm -->
                    <div class="mb-3">
                        <label for="description" class="form-label">Mô tả</label>
                        <textarea class="form-control" id="description" name="description" rows="4"></textarea>
                    </div>

                    <!-- Ảnh sản phẩm -->
                    <div class="mb-3">
                        <label for="productImages" class="form-label fw-bold">Ảnh sản phẩm</label>
                        <input type="file" class="form-control" id="productImages" name="productImages" accept="image/*" multiple>
                        <small class="form-text text-muted">Bạn có thể chọn nhiều ảnh (định dạng: jpeg, png, jpg, gif).</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Thêm sản phẩm</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                    
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        // Sự kiện khi người dùng nhấn nút "Thêm sản phẩm"
        $('#addProductForm').submit(function(e) {
            e.preventDefault(); // Ngừng form gửi bình thường

            // Tạo đối tượng FormData để gửi dữ liệu form bao gồm cả file
            var formData = new FormData(this);

            // Gửi AJAX
            $.ajax({
                url: '/themsanpham', // Địa chỉ controller xử lý form
                type: 'POST', // Phương thức gửi dữ liệu
                data: formData,
                contentType: false, // Để AJAX tự xử lý content-type
                processData: false, // Để không chuyển đổi dữ liệu
                success: function(response) {
                    // Hiển thị thông báo thành công
                    alert(response.message); // Giả sử bạn trả về thông báo trong response
                    $('#addProductModal').modal('hide'); // Đóng modal
                    $('#addProductForm')[0].reset(); // Reset form
                },
                error: function(xhr, status, error) {
                    // Xử lý lỗi nếu có
                    alert('Có lỗi xảy ra, vui lòng thử lại sau.');
                }
            });
        });
    });
</script> --}}

<!-- Modal Thêm Sản Phẩm -->
<!-- Modal Thêm Sản Phẩm -->
<div class="modal fade" id="addProductModal" tabindex="-1" aria-labelledby="addProductModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addProductModalLabel">Thêm Sản Phẩm Mới</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <!-- Form thêm sản phẩm -->
            <form id="productForm" action="/themsanpham" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="ID_SupCategory">Danh mục sản phẩm</label>
                        <select class="form-control" id="ID_SupCategory" name="ID_SupCategory" required>
                            <option value="">Chọn danh mục</option>
                            <!-- Giả sử bạn có một danh sách danh mục -->
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->SupCategoryName }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="productName">Tên sản phẩm</label>
                        <input type="text" class="form-control" id="productName" name="productName" required>
                    </div>

                    <div class="form-group">
                        <label for="description">Mô tả sản phẩm</label>
                        <textarea class="form-control" id="description" name="description"></textarea>
                    </div>

                    <div class="form-group">
                        <label for="productImages">Hình ảnh sản phẩm</label>
                        <input type="file" class="form-control" id="productImages" name="productImages[]" accept="image/*" multiple>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Lưu sản phẩm</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                   
                </div>
            </form>
        </div>
    </div>
</div>

