@extends('layout.index')

@section('title', 'Thêm sản phẩm')

@section('content')
<div class="container mt-4">
    <h2>Thêm sản phẩm mới</h2>

    <form id="productForm" action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
            <label for="productName" class="form-label">Tên sản phẩm</label>
            <input type="text" class="form-control" id="productName" name="productName" required>
        </div>

        <div class="mb-3">
            <label for="ID_SupCategory" class="form-label">Danh mục</label>
            <select class="form-control" id="ID_SupCategory" name="ID_SupCategory" required>
                <option value="" disabled selected>Chọn danh mục</option>
                @foreach ($Subcategories as $category)
                    <option value="{{ $category->id }}">{{ $category->SupCategoryName }}</option>
                @endforeach
            </select>
        </div>
        
        <div class="mb-3">
            <label for="description" class="form-label">Mô tả</label>
            <textarea class="form-control" id="description" name="description" rows="3" required></textarea>
        </div>

        <div class="mb-3">
            <label for="productImage" class="form-label">Hình ảnh</label>
            <input type="file" class="form-control" id="productImage" name="productImage[]" accept="image/*" multiple>
        </div>

       

        <!-- Thêm bảng sản phẩm mẫu -->
        <h4 class="mt-4">Tạo sản phẩm biến thể</h4>
        <table id="productTable" class="table">
            <thead>
                <tr>
                    <th>Kích cỡ</th>
                    <th>Giá</th>
                    <th>Số lượng kho</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><input type="text" class="form-control" name="size[]" placeholder="Kích thước" required></td>
                    <td><input type="number" class="form-control" name="price[]" value="0" required></td>
                    <td><input type="number" class="form-control" name="stock[]" value="0" required></td>
                </tr>
            </tbody>
        </table>

     
        <button type="submit" class="btn btn-primary">Lưu sản phẩm</button>
    </form>
</div>

@endsection



@section('js')
<script>
    // Lắng nghe sự kiện nhấn phím trong bảng
    document.getElementById('productTable').addEventListener('keypress', function(event) {
        // Kiểm tra nếu phím được nhấn là Enter (key code 13)
        if (event.key === 'Enter') {
            event.preventDefault(); // Ngăn không cho trình duyệt thực hiện hành động mặc định

            // Lấy dòng hiện tại (dòng mà người dùng vừa nhập)
            let currentRow = event.target.closest('tr');

            // Tạo một dòng mới cho bảng với thông tin mặc định
            let newRow = document.createElement('tr');
            newRow.innerHTML = `
                <td><input type="text" class="form-control" name="size[]" placeholder="Kích thước" required></td>
                <td><input type="number" class="form-control" name="price[]" value="0" required></td>
                <td><input type="number" class="form-control" name="stock[]" value="0" required></td>
            `;
            
            // Thêm dòng mới vào bảng dưới dòng hiện tại
            let tableBody = document.querySelector('#productTable tbody');
            tableBody.appendChild(newRow);

            // Di chuyển con trỏ vào ô đầu tiên của dòng mới để người dùng tiếp tục nhập
            newRow.querySelector('input').focus();
        }
    });
</script>

@endsection