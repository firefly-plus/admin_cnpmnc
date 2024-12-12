
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

@extends('layout.index')

@section('title', 'Quản lý danh mục')

@section('css')
    <style>
        .table-container {
            max-height: 400px;
            overflow-y: auto;
        }

        .table th,
        .table td {
            vertical-align: middle;
        }

        .modal-content {
            width: 90%;
        }
        .modal-dialog {
            max-width: 80%;
        }
    </style>
@endsection

@section('content')
    <div class="container mt-4">
        <h1>QUẢN LÝ DANH MỤC</h1>

        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addCategoryModal"><i class="fa-solid fa-plus"></i></button>

        <!-- Modal Add Category -->
        <div class="modal fade" id="addCategoryModal" tabindex="-1" aria-labelledby="addCategoryModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addCategoryModalLabel">Thêm Danh Mục</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="addCategoryForm" action="/category" method="POST">
                            <div class="mb-3">
                                <label for="categoryName" class="form-label">Tên Danh Mục</label>
                                <input type="text" class="form-control" 
                                id="categoryName" value="{{ old('categoryName', $category->categoryName ?? '') }}" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Lưu</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Table danh sách Category -->
        <div class="table-container">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Mã danh mục</th>
                        <th>Tên danh mục</th>
                        <th>Ngày tạo</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody id="categoryTableBody">
                </tbody>
            </table>
        </div>

        <!-- Modal chi tiết Category và SupCategory -->
        <div class="modal fade" id="categoryDetailModal" tabindex="-1" aria-labelledby="categoryDetailModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="categoryDetailModalLabel">Chi tiết danh mục</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <!-- Hiển thị chi tiết Category -->
                        <h4 id="categoryName"></h4>
                        <p id="categoryCreatedAt"></p>

                        <!-- Form thêm SupCategory -->
                        <form id="addSupCategoryForm">
                            <div class="form-group">
                                <label for="supCategoryName">Tên Danh Mục Con:</label>
                                <input type="text" class="form-control" id="supCategoryName" name="SupCategoryName"
                                    value="{{ old('SupCategoryName', $category->SupCategoryName ?? '') }}" required>

                            </div>
                            <button type="submit" class="btn btn-primary"><i class="fa-solid fa-plus"></i></button>
                        </form>


                        <div class="table-container">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Mã danh mục con</th>
                                        <th>Tên danh mục con</th>
                                        <th>Ngày tạo</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody id="subcategoryTableBody"></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection

@section('js')
    <script>
        let currentCategoryId = null;

        // Load Category List
        function loadCategories() {
            fetch('/categories')
                .then(response => response.json())
                .then(data => {
                    const tableBody = document.getElementById('categoryTableBody');
                    tableBody.innerHTML = '';
                    data.forEach(category => {
                        tableBody.innerHTML += `
                        <tr>
                            <td>${category.id}</td>
                            <td>${category.categoryName}</td>
                            <td>${category.createdAt}</td>
                            <td>
                                <button class="btn btn-sm btn-info" onclick="viewCategoryDetails(${category.id})"><i class="fa-solid fa-eye"></i></button>
                                <button class="btn btn-sm btn-warning" onclick="editCategory(${category.id})"><i class="fa-solid fa-pen-to-square"></i></button>
                                <button class="btn btn-sm btn-danger" onclick="deleteCategory(${category.id})"><i class="fa-solid fa-trash"></i></button>
                            </td>
                        </tr>`;
                    });
                })
                .catch(error => alert("Error loading categories: " + error));
        }

        // Add Category
        document.getElementById('addCategoryForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const categoryName = document.getElementById('categoryName').value;

            const formData = {
                categoryName
            };

            fetch('/category', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify(formData),
                })
                .then(response => response.json())
                .then(data => {
                    alert(data.message);
                    loadCategories();
                })
                .catch(error => alert("Error adding category: " + error));
            console.log('Dữ liệu gửi đi:', formData)
        });



        // View Category Details
        const viewCategoryDetails = (id) => {
            currentCategoryId = id; 

            fetch(`/category/${id}`)
                .then(response => response.json())
                .then((categoryData) => {
                    const categoryName = categoryData.categoryName;
                    const createdAt = new Date(categoryData.createdAt).toLocaleString(); 
                    document.getElementById('categoryName').textContent = categoryName;
                    document.getElementById('categoryCreatedAt').textContent = `Created At: ${createdAt}`;

                    const subcategoryTableBody = document.getElementById('subcategoryTableBody');
                    subcategoryTableBody.innerHTML = ''; 

                    if (Array.isArray(categoryData.sub_categories)) {
                        categoryData.sub_categories.forEach(subcategory => {
                            subcategoryTableBody.innerHTML += `
                                <tr>
                                    <td>${subcategory.id}</td>  <!-- ID sẽ là chuỗi -->
                                    <td>${subcategory.SupCategoryName}</td>
                                    <td>${new Date(subcategory.createdAt).toLocaleString()}</td>
                                    <td>
                                        <button class="btn btn-sm btn-warning" onclick="editSupCategory('${categoryData.id}', '${subcategory.id}')"><i class="fa-solid fa-pen-to-square"></i></button>
                                        <button class="btn btn-sm btn-danger" onclick="deleteSupCategory('${categoryData.id}', '${subcategory.id}')"><i class="fa-solid fa-trash"></i></button>
                                    </td>
                                </tr>
                            `;
                        });

                    } else {
                        subcategoryTableBody.innerHTML = '<tr><td colspan="4">No subcategories found.</td></tr>';
                    }

                    const modal = new bootstrap.Modal(document.getElementById('categoryDetailModal'));
                    modal.show();
                })
                .catch(error => {
                    console.error('Error fetching category details:', error);
                });

        };

        // Add SupCategory
        document.getElementById('addSupCategoryForm').addEventListener('submit', function(e) {
            e.preventDefault(); 

            const supCategoryName = document.getElementById('supCategoryName').value;
            const categoryId = currentCategoryId; 

            const formData = {
                SupCategoryName: supCategoryName
            };

            console.log('formdata', formData); 

            fetch(`/category/${categoryId}/supcategory`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify(formData),
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error(
                            `HTTP error! status: ${response.status}`); 
                    }
                    return response.json(); 
                })
                .then(data => {
                    console.log('Response data:', data);


                    alert(data.message);
                    viewCategoryDetails(currentCategoryId);
                    
                    const modal = bootstrap.Modal.getInstance(document.getElementById('categoryDetailModal'));
                    modal.hide();

                    const backdrop = document.querySelector('.modal-backdrop');
                    if (backdrop) {
                        backdrop.remove();
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error adding subcategory: ' + error.message);
                });
        });


        // Edit Category
        function editCategory(id) {
            const newCategoryName = prompt("Nhập tên mới cho danh mục:");
            if (!newCategoryName) return;

            fetch(`/category/${id}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        categoryName: newCategoryName
                    })
                })
                .then(response => response.json())
                .then(data => {
                    alert(data.message);
                    loadCategories();
                })
                .catch(error => alert("Error editing category: " + error));
        }

        // Delete Category
        function deleteCategory(id) {
            if (confirm("Có chắc muốn xóa danh mục này?")) {
                fetch(`/category/${id}`, {
                        method: 'DELETE',
                    })
                    .then(response => response.json())
                    .then(data => {
                        alert(data.message);
                        loadCategories();
                    })
                    .catch(error => alert("Lỗi khi xóa danh mục: " + error));
            }
        }

        // Delete SupCategory
        function deleteSupCategory(categoryId, supCategoryId) {
            if (confirm("Bạn có chắc chắn muốn xóa danh mục con này?")) {
                console.log('id category:', categoryId);
                console.log('id subcategory:', supCategoryId);

                fetch(`/category/${categoryId}/supcategory/${supCategoryId}`, {
                        method: 'DELETE',
                    })
                    .then(response => response.json())
                    .then(data => {
                        alert(data.message);
                        viewCategoryDetails(categoryId);
                        const modal = bootstrap.Modal.getInstance(document.getElementById('categoryDetailModal'));
                        modal.hide(); 

                        const backdrop = document.querySelector('.modal-backdrop');
                        if (backdrop) {
                            backdrop.remove();
                        }
                    })
                    .catch(error => alert("Error deleting subcategory: " + error));
            }
        }



        // Edit SupCategory
        function editSupCategory(categoryId, supCategoryId) {
            const newSupCategoryName = prompt("Nhập tên mới cho danh mục con:");
            if (!newSupCategoryName) return;

            fetch(`/category/${categoryId}/supcategory/${supCategoryId}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        SupCategoryName: newSupCategoryName
                    })
                })
                .then(response => response.json())
                .then(data => {
                    alert(data.message);
                    viewCategoryDetails(categoryId);
                     const modal = bootstrap.Modal.getInstance(document.getElementById('categoryDetailModal'));
                    modal.hide();
                    const backdrop = document.querySelector('.modal-backdrop');
                    if (backdrop) {
                        backdrop.remove();
                    }
                })
                .catch(error => alert("Error updating subcategory: " + error));
        }

        loadCategories();
    </script>
@endsection
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>