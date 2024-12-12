@extends('layout.index')
@section('title', 'Thêm Đơn Hàng')
@section('css')
<style>
    .product-option {
        display: flex;
        align-items: center;
    }

    .product-option img {
        width: 50px;
        height: 50px;
        margin-right: 10px;
    }

    .product-option .product-name {
        flex-grow: 1;
    }

    .added-products-table {
        margin-top: 20px;
        width: 100%;
    }
</style>
@endsection

@section('content')
<div class="col-md-12">
    <div class="tile">
        <h3>Chọn Danh Mục</h3>
        <div class="form-group">
            <label for="category">Danh Mục Cha</label>
            <select id="category" class="form-control">
                <option value="">Chọn danh mục cha</option>
            </select>
        </div>

        <div class="form-group">
            <label for="subcategory">Danh Mục Con</label>
            <select id="subcategory" class="form-control" disabled>
                <option value="">Chọn danh mục con</option>
            </select>
        </div>

        <div class="form-group">
            <label for="product">Sản Phẩm</label>
            <select id="product" class="form-control" disabled>
                <option value="">Chọn sản phẩm</option>
            </select>
        </div>

        <div class="form-group">
            <label for="variation">Biến Thể Sản Phẩm</label>
            <select id="variation" class="form-control" disabled>
                <option value="">Chọn biến thể sản phẩm</option>
            </select>
        </div>

        <div class="form-group">
            <label for="quantity">Số Lượng Nhập</label>
            <input type="number" id="quantity" class="form-control" min="1" disabled>
        </div>

        <div class="form-group">
            <label for="purchasePrice">Giá Nhập</label>
            <input type="number" id="purchasePrice" class="form-control" min="1" disabled>
        </div>

        <button id="addProductButton" class="btn btn-primary" disabled>Thêm Sản Phẩm</button>

        <!-- Bảng hiển thị các sản phẩm đã thêm -->
        <select id="supplierSelect">
            <option value="">Chọn nhà cung cấp</option>
            <!-- Các option sẽ được thêm vào đây -->
        </select>

        <table class="table added-products-table">
            <thead>
                <tr>
                    <th>Mã Biến thể</th>
                    <th>Tên Sản Phẩm</th>
                    <th>Biến Thể</th>
                    <th>Số Lượng</th>
                    <th>Giá Nhập</th>
                    <th>Hành Động</th>
                </tr>
            </thead>
            <tbody id="addedProductsList">
                <!-- Các sản phẩm thêm sẽ được hiển thị ở đây -->
            </tbody>
        </table>
        <button id="createInvoiceButton" class="btn btn-success">Tạo Hóa Đơn Nhập</button>

    </div>
</div>
@endsection

@section('js')
<script>
    // Xử lý khi nhấn nút "Tạo Hóa Đơn Nhập"
    document.getElementById('createInvoiceButton').addEventListener('click', function() {
        // Lấy thông tin đơn hàng từ localStorage
        const products = JSON.parse(localStorage.getItem('addedProducts')) || [];

        // Kiểm tra nếu giỏ hàng không có sản phẩm
        if (products.length === 0) {
            alert('Bạn cần thêm ít nhất một sản phẩm trước khi tạo hóa đơn!');
            return; // Dừng lại nếu không có sản phẩm
        }
        const supplierId = document.getElementById('supplierSelect').value; // Lấy ID nhà cung cấp
        const employeerId = 2;  // Giả sử bạn muốn gửi ID của nhân viên (có thể thay đổi theo nhu cầu)
        const orderDate = new Date().toISOString().split('T')[0];  // Lấy ngày hiện tại
        // Tính tổng tiền của đơn hàng
        let totalAmount = 0;
            products.forEach(product => {
                totalAmount += product.purchasePrice * product.quantity; // Tổng tiền = giá * số lượng
            });
        // Chuyển đổi sản phẩm thành dữ liệu yêu cầu cho API
        const orderData = {
            supplierId: supplierId,
            employeerId: employeerId,
            orderDate: orderDate,
            totalPrice: totalAmount,
            items: products.map(product => ({
                productVariationId: product.variationId,
                quantityOrdered: product.quantity,
                unitPrice: product.purchasePrice,
                amount: product.purchasePrice * product.quantity
            }))
        };

        // Gửi yêu cầu đến API tạo đơn hàng
        fetch('http://localhost:8017/v1/orderSupplier/create', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(orderData)
        })
        .then(response => response.json())
        .then(data => {
            alert('Đơn hàng đã được tạo thành công!');
            console.log('Order created:', data);
            // Xóa sản phẩm đã thêm trong localStorage sau khi tạo đơn hàng thành công
            localStorage.removeItem('addedProducts');
            displayAddedProducts();  // Cập nhật lại giao diện sản phẩm đã thêm (nếu có)
        })
        .catch(error => {
            console.error('Error creating order:', error);
            alert('Đã xảy ra lỗi khi tạo đơn hàng!');
        });
    });



    fetch('http://localhost:8017/v1/supplier/')
    .then(response => response.json())
    .then(suppliers => {
        const supplierSelect = document.querySelector('#supplierSelect');

        suppliers.forEach(supplier => {
            const option = document.createElement('option');
            option.value = supplier.id;
            option.textContent = supplier.SupplierName;  // Hiển thị tên nhà cung cấp

            supplierSelect.appendChild(option);
        });
    })
    .catch(error => console.error('Error loading suppliers:', error));

    // Lưu trữ sản phẩm đã thêm vào trong session hoặc localStorage
    function saveToSession(product) {
        let products = JSON.parse(localStorage.getItem('addedProducts')) || [];

        // Kiểm tra xem mã biến thể đã có trong sản phẩm chưa
        const existingProductIndex = products.findIndex(p => p.variationId === product.variationId);

        if (existingProductIndex !== -1) {
            // Nếu đã có, tăng số lượng của sản phẩm đó
            products[existingProductIndex].quantity += product.quantity;
        } else {
            // Nếu chưa có, thêm mới sản phẩm
            products.push(product);
        }

        // Lưu lại vào session hoặc localStorage
        localStorage.setItem('addedProducts', JSON.stringify(products));
    }

    // Hiển thị các sản phẩm đã thêm trong bảng
    function displayAddedProducts() {
        const products = JSON.parse(localStorage.getItem('addedProducts')) || [];
        const addedProductsList = document.getElementById('addedProductsList');
        addedProductsList.innerHTML = '';

        products.forEach(product => {
            const row = document.createElement('tr');
            row.innerHTML = `
                <td>${product.variationId}</td>
                <td>${product.name}</td>
                <td>${product.variation}</td>
                <td>${product.quantity}</td>
                <td>${product.purchasePrice}</td>
                <td><button class="btn btn-danger btn-sm" onclick="removeProduct('${product.variationId}')">Xóa</button></td>
            `;
            addedProductsList.appendChild(row);
        });
    }

    // Xử lý khi nhấn nút "Xóa"
    function removeProduct(variationId) {
        let products = JSON.parse(localStorage.getItem('addedProducts')) || [];
        products = products.filter(product => product.variationId !== variationId);
        localStorage.setItem('addedProducts', JSON.stringify(products));
        displayAddedProducts();
    }

    // Lấy dữ liệu từ API danh mục
    fetch('http://localhost:8017/v1/category')
      .then(response => response.json())
      .then(data => {
        const categorySelect = document.getElementById('category');
        data.forEach(category => {
          const option = document.createElement('option');
          option.value = category.category_id;
          option.textContent = category.category_name;
          categorySelect.appendChild(option);
        });

        categorySelect.addEventListener('change', function() {
          const selectedCategoryId = this.value;
          const subcategorySelect = document.getElementById('subcategory');
          subcategorySelect.innerHTML = '<option value="">Chọn danh mục con</option>';
          document.getElementById('product').innerHTML = '<option value="">Chọn sản phẩm</option>';
          document.getElementById('variation').innerHTML = '<option value="">Chọn biến thể sản phẩm</option>';

          if (selectedCategoryId) {
            const selectedCategory = data.find(category => category.category_id == selectedCategoryId);
            selectedCategory.subcategories.forEach(subcategory => {
              const option = document.createElement('option');
              option.value = subcategory.id;
              option.textContent = subcategory.SupCategoryName;
              subcategorySelect.appendChild(option);
            });
            subcategorySelect.disabled = false;
          } else {
            subcategorySelect.disabled = true;
          }
        });
      })
      .catch(error => console.error('Error fetching categories:', error));

    // Xử lý khi chọn danh mục con
    document.getElementById('subcategory').addEventListener('change', function() {
      const selectedSubcategoryId = this.value;
      const productSelect = document.getElementById('product');
      productSelect.innerHTML = '<option value="">Chọn sản phẩm</option>';
      document.getElementById('variation').innerHTML = '<option value="">Chọn biến thể sản phẩm</option>';

      if (selectedSubcategoryId) {
        fetch(`http://localhost:8017/v1/product/getProductBySupAdmin/${selectedSubcategoryId}`)
          .then(response => response.json())
          .then(products => {
            products.forEach(product => {
              const option = document.createElement('option');
              option.value = product.id;
              option.innerHTML = `<span class="product-option"><img src="${product.IMG_URL}" alt="${product.productName}" /><span class="product-name">${product.productName}</span></span>`;
              productSelect.appendChild(option);
            });
            productSelect.disabled = false;
          })
          .catch(error => console.error('Error fetching products:', error));
      } else {
        productSelect.disabled = true;
      }
    });

    // Xử lý khi chọn sản phẩm
    document.getElementById('product').addEventListener('change', function() {
        const selectedProductId = this.value;
        const variationSelect = document.getElementById('variation');
        variationSelect.innerHTML = '<option value="">Chọn biến thể sản phẩm</option>';  // Reset biến thể

        if (selectedProductId) {
            // Gửi yêu cầu đến API để lấy biến thể của sản phẩm
            fetch(`http://localhost:8017/v1/product/${selectedProductId}`)
                .then(response => response.json())
                .then(data => {
                    // Hiển thị các biến thể sản phẩm vào combobox
                    data.variations.forEach(variation => {
                        const option = document.createElement('option');
                        option.value = variation.variation_id;
                        option.textContent = `${variation.size} - Giá: ${variation.final_price} VND`;
                        variationSelect.appendChild(option);
                    });
                    variationSelect.disabled = false;  // Bật combobox biến thể sản phẩm
                })
                .catch(error => console.error('Error fetching variations:', error));
        } else {
            variationSelect.disabled = true;  // Tắt combobox biến thể sản phẩm nếu không chọn sản phẩm
        }
    });

    // Xử lý khi chọn biến thể sản phẩm
    document.getElementById('variation').addEventListener('change', function() {
        const selectedVariationId = this.value;
        const quantityInput = document.getElementById('quantity');
        const purchasePriceInput = document.getElementById('purchasePrice');
        const addProductButton = document.getElementById('addProductButton');

        if (selectedVariationId) {
            // Tìm thông tin biến thể từ các sản phẩm đã tải
            const selectedProductId = document.getElementById('product').value;
            fetch(`http://localhost:8017/v1/product/${selectedProductId}`)
                .then(response => response.json())
                .then(data => {
                    const selectedVariation = data.variations.find(v => v.variation_id == selectedVariationId);
                    quantityInput.disabled = false;
                    purchasePriceInput.disabled = false;
                    quantityInput.value = 1;
                    purchasePriceInput.value = selectedVariation.final_price;
                    addProductButton.disabled = false;
                });
        } else {
            quantityInput.disabled = true;
            purchasePriceInput.disabled = true;
            addProductButton.disabled = true;
        }
    });

    // Xử lý khi nhấn nút thêm sản phẩm
    document.getElementById('addProductButton').addEventListener('click', function() {
        const selectedProduct = document.getElementById('product');
        const selectedVariation = document.getElementById('variation');
        const quantityInput = document.getElementById('quantity');
        const purchasePriceInput = document.getElementById('purchasePrice');

        const product = {
            name: selectedProduct.options[selectedProduct.selectedIndex].textContent,
            variation: selectedVariation.options[selectedVariation.selectedIndex].textContent,
            image: selectedProduct.options[selectedProduct.selectedIndex].dataset.image,
            variationId: selectedVariation.value,
            quantity: parseInt(quantityInput.value),
            purchasePrice: parseInt(purchasePriceInput.value)
        };

        // Lưu vào session hoặc localStorage
        saveToSession(product);

        // Hiển thị lại các sản phẩm đã thêm
        displayAddedProducts();
    });

    // Hiển thị các sản phẩm đã thêm khi trang tải
    displayAddedProducts();
</script>
@endsection
