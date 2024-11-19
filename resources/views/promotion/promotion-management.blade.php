@extends('layout.index')
@section('title','Management Promotion')
@section('css')
<style>
.promotion-info {
    max-width: 800px; 
    margin: 0 auto;
}

.form-row {
    display: flex;
    justify-content: space-between; 
    gap: 10px; 
    align-items: flex-start;
}

.form-group {
    flex: 1;
    min-width: 150px;
}

label {
    display: block;
    margin-bottom: 5px;
}

input.form-control {
    width: 100%;
    padding: 8px;
    font-size: 14px;
}

.product-list {
    max-width: 1000px;
    margin: 0 auto;
}

.product-grid {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    justify-content: flex-start;
    max-height: 300px; /* Giới hạn chiều cao */
    overflow-y: auto; /* Hiển thị thanh cuộn dọc nếu nội dung vượt quá chiều cao */
}


.product-card {
    flex: 1 0 21%;  
    min-width: 200px; 
    max-width: 210px; 
    border: 1px solid #ddd;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    text-align: center;
    padding: 10px;  
}

.product-card input {
    margin-bottom: 10px;
}

.product-card p {
    margin: 5px 0;
}

button#apply-discount {
    margin-top: 20px;
    display: block;
    width: 200px;
    margin-left: auto;
    margin-right: auto;
}
.product-checkbox{
    float: left;
}
.form-row {
    display: flex;
    justify-content: space-between;
    gap: 10px; /* Khoảng cách giữa các ô */
    align-items: flex-start;
    margin-top: 10px; /* Tạo khoảng cách với phần trên */
}

.form-group {
    flex: 1;
    min-width: 150px;
}

label {
    display: block;
    margin-bottom: 5px;
}

select.form-control {
    width: 100%;
    padding: 8px;
    font-size: 14px;
}


</style>
@endsection

@section('content')
<div class="container">
    <!-- Phần thông tin giảm giá -->
    <div class="promotion-info">      
        <form id="promotion-form">
            <div class="form-row">
                <div class="form-group">
                    <label for="discount">Discount Percentage (%):</label>
                    <select id="discount" name="discount" class="form-control">
                        <option value="">Select Discount</option>
                        <option value="1">Discount 1</option>
                        <option value="2">Discount 2</option>
                        <option value="3">Discount 3</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="start_date">Start Date:</label>
                    <input type="date" id="start_date" name="start_date" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="end_date">End Date:</label>
                    <input type="date" id="end_date" name="end_date" class="form-control" required>
                </div>
            </div>
        </form>
    
        <!-- 3 Select Boxes -->
        <div class="form-row">
            <div class="form-group">
                <select id="category" name="category" class="form-control">
                    <option value="">Select Category</option>
                    <option value="1">Category 1</option>
                    <option value="2">Category 2</option>
                    <option value="3">Category 3</option>
                </select>
            </div>
            <div class="form-group">
                <select id="subcategory" name="subcategory" class="form-control">
                    <option value="">Select SubCategory</option>
                    <option value="1">Type 1</option>
                    <option value="2">Type 2</option>
                    <option value="3">Type 3</option>
                </select>
            </div>
            <div class="form-group">
                <select id="product" name="product" class="form-control">
                    <option value="">Select Product</option>
                    <option value="1">Product 1</option>
                    <option value="2">Product 2</option>
                    <option value="3">Product 3</option>
                </select>
            </div>
        </div>
    </div>
    
    
    <div class="product-list">
        <h3>Product List</h3>
        <input type="checkbox" id="check-all" class="check-all-checkbox"> Check All

        <div class="product-grid">
            
        </div>
        <button type="button" id="apply-discount" class="btn btn-success">Apply Discount</button>
    </div>
    <div>
        <table>
            <thead>
                <tr>
                   
                    <th>Product Name</th>
                    <th>Size</th>
                    <th>Price</th>
                    <th>Discount(%)</th>
                    <th>Product Image</th>
                    <th>Fun</th>
                </tr>
            </thead>
            <tbody id="product-list-discount">
                <tr>
                  
                    <td>Vải thổ cẩm</td>
                    <td>L</td>
                    <td>180000.00</td>
                    <td>30</td>
                    <td><img src="https://res.cloudinary.com/dkcizqsb3/image/upload/v1731226702/vb9pshh6ky3krifvo6mc.jpg" alt="Vải thổ cẩm" width="100"></td>
                    <td>Vải thổ cẩm với họa tiết truyền thống của dân tộc.</td>
                </tr>
                <tr>
                    <td>Tranh dân gian Đông Hồ</td>
                    <td>M</td>
                    <td>150000.00</td>
                    <td>50</td>
                    <td><img src="https://res.cloudinary.com/dkcizqsb3/image/upload/v1731234383/pwgstuca2ml2oq6pirik.jpg" alt="Tranh dân gian Đông Hồ" width="100"></td>
                    <td>Tranh Đông Hồ với hình ảnh gần gũi và ý nghĩa.</td>
                </tr>
               
            </tbody>
        </table>
    </div>
    
</div>
@endsection

@section('js')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    
    $(document).ready(function() {
        $.ajax({
            url: '/getdiscount',
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                $('#discount').empty(); 

                $('#discount').append('<option value="">Select Discount</option>');

                $.each(data, function(index, discount) {
                    $('#discount').append('<option value="' + discount.id + '">' + discount.discount + '%</option>');
                });
            },
            error: function(xhr, status, error) {
            
                console.error('Error fetching discount data: ', error);
            }
        });
    });
    $(document).ready(function() {
        $.ajax({
            url: '/getcategory',
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                $('#category').empty(); 

                $('#category').append('<option value="">Select Category</option>');

                $.each(data, function(index, category) {
                    $('#category').append('<option value="' + category.id + '">' + category.categoryName + '</option>');
                });
            },
            error: function(xhr, status, error) {
            
                console.error('Error fetching discount data: ', error);
            }
        });
    });
    $(document).ready(function() {
        $.ajax({
            url: '/getsubcategory',
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                $('#subcategory').empty(); 

                $('#subcategory').append('<option value="">Select SubCategory</option>');

                $.each(data, function(index, subcategory) {
                    $('#subcategory').append('<option value="' + subcategory.id + '">' + subcategory.SupCategoryName + '</option>');
                });
            },
            error: function(xhr, status, error) {
            
                console.error('Error fetching discount data: ', error);
            }
        });
    });
    $(document).ready(function() {
        $.ajax({
            url: '/getproduct',
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                $('#product').empty(); 

                $('#product').append('<option value="">Select Product</option>');

                $.each(data, function(index, product) {
                    $('#product').append('<option value="' + product.id + '">' + product.productName + '</option>');
                });
            },
            error: function(xhr, status, error) {
            
                console.error('Error fetching discount data: ', error);
            }
        });
    });

</script>
<script>
    $(document).ready(function() {
        $('#category').on('change', function() {
            var categoryId = $(this).val();

            if (categoryId) {
                $.ajax({
                    url: '/getproductvariationbycategory', 
                    type: 'GET',
                    data: { id: categoryId },
                    dataType: 'json',
                    success: function(data) {
                        $('.product-grid').empty();

                        $.each(data, function(index, variation) {
                            
                            var imageUrl = variation.product.product_images && variation.product.product_images.length > 0
                                ? variation.product.product_images[0].IMG_URL 
                                : '/images/team.jpg';

                            var productHtml = '<div class="product-card">' +
                                '<input type="checkbox" class="product-checkbox" data-product-id="' + variation.product.id + '">' +
                                '<div style="clear: both"></div>' +
                                '<p><img style="width: 150px;" src="' + imageUrl + '" alt="Product Image"></p>' +
                                '<p><strong>Product Name:</strong> ' + variation.product.productName + '</p>' +
                                '<p><strong>Price:</strong> ' + variation.Price + '</p>' +
                                '</div>';

                            $('.product-grid').append(productHtml);
                        });
                    },
                    error: function(xhr, status, error) {
                        console.error('Error fetching product variations:', error);
                    }
                });
            } else {
                $('.product-grid').empty();
            }
        });
    });
    $(document).ready(function() {
        $('#subcategory').on('change', function() {
            var categoryId = $(this).val();

            if (categoryId) {
                $.ajax({
                    url: '/getproductvariationbysubcategory', 
                    type: 'GET',
                    data: { id: categoryId },
                    dataType: 'json',
                    success: function(data) {
                        $('.product-grid').empty();

                        $.each(data, function(index, variation) {
                            
                            var imageUrl = variation.product.product_images && variation.product.product_images.length > 0
                                ? variation.product.product_images[0].IMG_URL 
                                : '/images/team.jpg';

                            var productHtml = '<div class="product-card">' +
                                '<input type="checkbox" class="product-checkbox" data-product-id="' + variation.product.id + '">' +
                                '<div style="clear: both"></div>' +
                                '<p><img style="width: 150px;" src="' + imageUrl + '" alt="Product Image"></p>' +
                                '<p><strong>Product Name:</strong> ' + variation.product.productName + '</p>' +
                                '<p><strong>Price:</strong> ' + variation.Price + '</p>' +
                                '</div>';

                            $('.product-grid').append(productHtml);
                        });
                    },
                    error: function(xhr, status, error) {
                        console.error('Error fetching product variations:', error);
                    }
                });
            } else {
                $('.product-grid').empty();
            }
        });
    });
    $(document).ready(function() {
        $('#product').on('change', function() {
            var categoryId = $(this).val();

            if (categoryId) {
                $.ajax({
                    url: '/getproductvariationbyproduct', 
                    type: 'GET',
                    data: { id: categoryId },
                    dataType: 'json',
                    success: function(data) {
                        $('.product-grid').empty();

                        $.each(data, function(index, variation) {
                            
                            var imageUrl = variation.product.product_images && variation.product.product_images.length > 0
                                ? variation.product.product_images[0].IMG_URL 
                                : '/images/team.jpg';

                            var productHtml = '<div class="product-card">' +
                                '<input type="checkbox" class="product-checkbox" data-product-id="' + variation.product.id + '">' +
                                '<div style="clear: both"></div>' +
                                '<p><img style="width: 150px;" src="' + imageUrl + '" alt="Product Image"></p>' +
                                '<p><strong>Product Name:</strong> ' + variation.product.productName + '</p>' +
                                '<p><strong>Price:</strong> ' + variation.Price + '</p>' +
                                '</div>';

                            $('.product-grid').append(productHtml);
                        });
                    },
                    error: function(xhr, status, error) {
                        console.error('Error fetching product variations:', error);
                    }
                });
            } else {
                $('.product-grid').empty();
            }
        });
    });
</script>
{{-- check all cho ds chưa discount --}}
<script>
     $(document).ready(function() {
        var selectedProducts = []; 

        $('#check-all').on('change', function() {
            var isChecked = $(this).prop('checked'); 

            $('.product-checkbox').prop('checked', isChecked);

            if (isChecked) {
                $('.product-checkbox').each(function() {
                    selectedProducts.push($(this).data('product-id'));
                });
            } else {
                selectedProducts = [];
            }

            console.log('Selected Products:', selectedProducts); 
        });

        // Lắng nghe sự kiện thay đổi của checkbox sản phẩm
        $(document).on('change', '.product-checkbox', function() {
            var productId = $(this).data('product-id'); 
            var isChecked = $(this).prop('checked'); 

            if (isChecked) {
                
                if (!selectedProducts.includes(productId)) {
                    selectedProducts.push(productId);
                }
            } else {
               
                var index = selectedProducts.indexOf(productId);
                if (index > -1) {
                    selectedProducts.splice(index, 1);
                }
            }

            console.log('Selected Products:', selectedProducts); 
        });
    });
</script>
{{-- check all cho ds đã discount --}}
<script>
    $(document).ready(function() {
        function loadProducts() {
            $.ajax({
                url: '/getproductvariationdiscount',  // Địa chỉ API để lấy dữ liệu sản phẩm
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    // Xóa nội dung bảng trước khi thêm dữ liệu mới
                    $('#product-list-discount').empty();

                    // Duyệt qua từng sản phẩm trong mảng dữ liệu
                    data.forEach(function(product) {
                        var row = `
                           <tr id="product-${product.id}">
                                <td>${product.product.productName}</td>
                                <td>${product.size}</td>
                                <td>${product.Price}</td>
                                <td>${product.variationdiscount[0].discount.discount}</td>
                                <td><img src="${product.product.product_images[0].IMG_URL}" alt="${product.product.productName}" width="100"></td>
                                <td><button class="btn btn-success delete-discount" data-product-id="${product.id}">Accept</button></td>
                            </tr>


                        `;
                        // Thêm dòng vào bảng
                        $('#product-list-discount').append(row);
                    });
                },
                error: function(xhr, status, error) {
                    console.error('Error loading products:', error);
                }
            });
        }

        // Gọi hàm để tải sản phẩm ngay khi trang được tải
        loadProducts();
    });

    $(document).ready(function () {
    $(document).on('click', '.delete-discount', function () {
        var productId = $(this).data('product-id'); 
        console.log("Product ID: ", productId); // Kiểm tra xem ID có được log ra hay không

        $.ajax({
            url: '/deletediscountbyproductvariation?id=' + productId, 
            method: 'DELETE', 
            dataType: 'json',
            success: function (response) {
                if (response.message === 'Khuyến mãi đã được hủy thành công.') {
                    $('#product-' + productId).remove();
                    alert('Khuyến mãi đã được hủy thành công!');
                }
            },
            error: function (xhr, status, error) {
                alert('Đã có lỗi xảy ra khi hủy khuyến mãi. Vui lòng thử lại!');
            }
        });
    });
});



</script>


@endsection
