<div class="modal fade" id="addProductModal" tabindex="-1" aria-labelledby="addProductModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addProductModalLabel">Thêm Danh Mục Mới</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <!-- Form thêm sản phẩm -->
            <form id="productForm" action="/themdanhmuc" method="POST" enctype="multipart/form-data">
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