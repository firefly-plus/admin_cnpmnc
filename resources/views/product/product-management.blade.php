@extends('layout.index')
@section('title', 'Danh sách sản phẩm')

@section('css')
<!-- Thêm CSS tùy chỉnh nếu cần -->
@endsection

@section('content')
<div class="col-md-12">
    <div class="tile">
        <h3 class="tile-title">Danh sách sản phẩm</h3>
        <div class="tile-body">
            <!-- Nút thêm sản phẩm -->
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addProductModal">Thêm sản phẩm</button>
            
            <!-- Bảng danh sách sản phẩm -->
            <table class="table table-bordered table-hover" id="productTable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Tên sản phẩm</th>
                        <th>Loại</th>
                        <th>Hình ảnh</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($products as $product)
                    <tr>
                        <td>{{ $product->id }}</td>
                        <td>{{ $product->productName }}</td>
                        <td>{{$product->subCategory->SupCategoryName  }} </td>
                        <td>
                            @if($product->productImages->isNotEmpty())
                                <img src="{{ $product->productImages->first()->IMG_URL }}" alt="Hình ảnh" style="width: 50px; height: 50px;">
                            @else
                                <span>Không có ảnh</span>
                            @endif
                        </td>
                        <td>
                            <button class="btn btn-warning btn-sm edit-product-btn" 
                                    data-id="{{ $product->id }}" 
                                    data-name="{{ $product->productName }}" 
                                    data-subCategory="{{ $product->subCategory->SupCategoryName }}" 
                                    data-description="{{ $product->description }}" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#editProductModal">
                                Sửa
                            </button>
                            <form action="{{ url('/xoasanpham' , $product['id']) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">Xóa</button>
                            </form>
                        </td>
                    </tr>
                    @include('product.update-product')
                    @endforeach
                </tbody>
            </table>
            @include('product.add-product')
        </div>
    </div>
</div>

@endsection

@section('js')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>




@endsection