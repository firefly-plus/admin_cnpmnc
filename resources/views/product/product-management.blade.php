@php
    $permissions = session('employee_permissions', collect())->toArray(); 
   
@endphp
@extends('layout.index')

@section('title', 'Quản lý sản phẩm')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Quản lý sản phẩm</h2>
        
        @if(in_array('Quản lý sản phẩm - Thêm nhiều sản phẩm', $permissions))
            <a href="{{ url('/showthemsanpham') }}" class="btn btn-primary" >Thêm sản phẩm mới</a>
        @endif
    </div>
   

   

    <table class="table table-striped">
        <thead>
            <tr>
                <th>#</th>
                <th>Hình ảnh</th>
                <th>Tên sản phẩm</th>
                <th>Giá</th>
                <th>Danh mục</th>
                <th>Số lượng</th>
                <th>Trạng thái</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($products as $product)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>
                        @if ($product->productImages->isNotEmpty())
                            <img src="{{ $product->productImages->first()->IMG_URL }}" class="img-thumbnail" style="width: 60px; height: 60px; object-fit: cover;">
                            {{-- @foreach ($product->productImages->skip(1) as $image)
                                <img src="{{ $image->IMG_URL }}" class="img-thumbnail" style="width: 60px; height: 60px; object-fit: cover;">
                            @endforeach --}}
                        @else
                            <span>Không có hình ảnh</span>
                        @endif
                    </td>
                    <td>{{ $product->productName }}</td>
                    <td>
                        @if ($product->productVariations->isNotEmpty())
                            {{ number_format($product->productVariations->min('Price'), 0, ',', '.') }}₫
                        @else
                            <span>Chưa có giá</span>
                        @endif
                    </td>
                    <td>{{ $product->subCategory->SupCategoryName ?? 'Không rõ' }}</td>
                    <td>
                        @if ($product->productVariations->isNotEmpty())
                            {{ $product->productVariations->sum('stock') }}
                        @else
                            <span>Chưa có</span>
                        @endif
                    </td>
                    <td>
                        @if ($product->isDelete == 0)
                            <span class="badge bg-success">Còn bán</span>
                        @else
                            <span class="badge bg-secondary">Ngưng bán</span>
                        @endif
                    </td>
                    <td>
                        @if(in_array('Quản lý sản phẩm - Chỉnh sửa sản phẩm', $permissions))
                            <a href="javascript:void(0);" 
                                class="btn btn-warning btn-sm edit-btn" 
                                data-id="{{ $product->id }}" 
                                data-name="{{ $product->productName }}" 
                                data-price="{{ $product->productVariations->pluck('Price')->implode(', ') }}" 
                                data-stock="{{ $product->productVariations->pluck('stock')->implode(', ') }}" 
                                data-size="{{ $product->productVariations->pluck('size')->implode(', ') }}"
                                data-description="{{ $product->description ?? '' }}">
                                Sửa
                            </a>
                        @endif
                        <form action="{{ url('/xoasanpham/' . $product->id) }}" method="POST" class="d-inline-block">
                            @csrf
                            @method('DELETE')
                            @if(in_array('Quản lý sản phẩm - Xóa sản phẩm', $permissions))
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Bạn có chắc chắn muốn xóa sản phẩm này?')">
                                    Xóa
                                </button>
                            @endif
                            
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center">Không có sản phẩm nào</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="d-flex justify-content-center  mt-3">
        {{ $products->links('vendor.pagination.bootstrap-4') }}
    </div>
    
</div>


<div class="modal fade" id="editProductModal" tabindex="-1" aria-labelledby="editProductModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="editProductForm" method="POST" action="/suasanpham" enctype="multipart/form-data">
                @csrf
                @method('POST')
                <input type="hidden" name="productId" id="productId">
                <div class="modal-header">
                    <h5 class="modal-title" id="editProductModalLabel">Chỉnh sửa sản phẩm</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
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
