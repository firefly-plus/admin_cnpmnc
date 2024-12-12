<!-- Thêm link CSS của Bootstrap -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- Thêm JS của Bootstrap -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

@extends('layout.index')

@section('title', 'Category Management')

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
            width: 80%;
        }
    </style>
@endsection

@section('content')
    <div class="container mt-4">
        <h1>Category Management</h1>

        <!-- Nút thêm danh mục -->
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addCategoryModal">Add Category</button>

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
                                <input type="text" class="form-control" id="categoryName" required>
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
                        <h5 class="modal-title" id="categoryDetailModalLabel">Category Details</h5>
                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <h4>Subcategories</h4>
                        <ul id="supCategoryList"></ul>
                        <form id="addSupCategoryForm">
                            <div class="form-group">
                                <label for="supCategoryName">Subcategory Name:</label>
                                <input type="text" class="form-control" id="supCategoryName" name="supCategoryName"
                                    required>
                            </div>
                            <button type="submit" class="btn btn-primary">Add Subcategory</button>
                        </form>
                        <div class="table-container">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Mã danh mục con</th>
                                        <th>Tên danh mục con</th>
                                        <th>Mã danh mục</th>
                                        <th>Ngày tạo</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody id="subcategoryTableBody">
                                </tbody>
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
                                <button class="btn btn-sm btn-info" onclick="viewCategoryDetails(${category.id})">View Details</button>
                                <button class="btn btn-sm btn-warning" onclick="editCategory(${category.id})">Edit</button>
                                <button class="btn btn-sm btn-danger" onclick="deleteCategory(${category.id})">Delete</button>
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
        // View Category Details
        const viewCategoryDetails = (id) => {
            // Store the current category ID for later use
            currentCategoryId = id;

            // Fetch the category details
            fetch(`/category/${id}`)
                .then((response) => response.json())
                .then((categoryData) => {
                    // Show the category name and other details
                    const categoryName = categoryData.categoryName; // Assuming categoryName is directly available
                    const createdAt = categoryData.createdAt; // Assuming createdAt is directly available

                    // Populate the modal with category details
                    const categoryDetailModal = document.getElementById("categoryDetailModal");
                    const modalTitle = categoryDetailModal.querySelector("#categoryDetailModalLabel");
                    const modalBody = categoryDetailModal.querySelector(".modal-body");

                    modalTitle.textContent = categoryName;

                    // Add the category details
                    modalBody.innerHTML = `
                <p>Created At: ${createdAt}</p>
                <h5>Subcategories:</h5>
                <ul id="supCategoryList"></ul>
                <form id="addSupCategoryForm">
                    <div class="form-group">
                        <label for="supCategoryName">Subcategory Name:</label>
                        <input type="text" class="form-control" id="supCategoryName" name="supCategoryName" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Add Subcategory</button>
                </form>
            `;

                    // Fetch the subcategories for the current category
                    fetch(
                        `/categories/${id}/subcategories`) // Assuming there's an endpoint for fetching subcategories by category ID
                        .then((response) => response.json())
                        .then((subcategories) => {
                            // Populate the subcategories list
                            const supCategoryList = document.getElementById("supCategoryList");
                            supCategoryList.innerHTML = ''; // Clear the list before adding new items

                            subcategories.forEach(subcategory => {
                                const listItem = document.createElement("li");
                                listItem.innerHTML = `
                            ${subcategory.SupCategoryName}
                            <button class="btn btn-sm btn-warning" onclick="editSupCategory(${subcategory.id})">Edit</button>
                            <button class="btn btn-sm btn-danger" onclick="deleteSupCategory(${subcategory.id})">Delete</button>
                        `;
                                supCategoryList.appendChild(listItem);
                            });
                        })
                        .catch((error) => {
                            console.error("Error fetching subcategories:", error);
                        });

                    // Open the modal
                    const modal = new bootstrap.Modal(categoryDetailModal);
                    modal.show();
                })
                .catch((error) => {
                    console.error("Error fetching category details:", error);
                });
        };

        // Add SupCategory
        document.getElementById('addSupCategoryForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const supCategoryName = document.getElementById('supCategoryName').value;

            if (!supCategoryName) {
                alert('Subcategory name is required!');
                return;
            }

            fetch(`/categories/${currentCategoryId}/supcategory`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        name: supCategoryName
                    })
                })
                .then(response => response.json())
                .then(data => {
                    alert(data.message);
                    viewCategoryDetails(currentCategoryId); // Refresh details
                })
                .catch(error => alert("Error adding subcategory: " + error));
        });

        // Edit Category
        function editCategory(id) {
            const newCategoryName = prompt("Enter new category name:");
            if (!newCategoryName) return;

            fetch(`/categories/${id}`, {
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
            if (confirm("Are you sure you want to delete this category?")) {
                fetch(`/categories/${id}`, {
                        method: 'DELETE'
                    })
                    .then(response => response.json())
                    .then(data => {
                        alert(data.message);
                        loadCategories();
                    })
                    .catch(error => alert("Error deleting category: " + error));
            }
        }

        // Delete SupCategory
        function deleteSupCategory(id) {
            if (confirm("Are you sure you want to delete this subcategory?")) {
                fetch(`/categories/${currentCategoryId}/supcategory/${id}`, {
                        method: 'DELETE'
                    })
                    .then(response => response.json())
                    .then(data => {
                        alert(data.message);
                        viewCategoryDetails(currentCategoryId); // Refresh details
                    })
                    .catch(error => alert("Error deleting subcategory: " + error));
            }
        }

        // Edit SupCategory
        function editSupCategory(id) {
            const newName = prompt("Enter new name for the subcategory:");
            if (!newName) return;

            fetch(`/categories/${currentCategoryId}/supcategory/${id}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        name: newName
                    })
                })
                .then(response => response.json())
                .then(data => {
                    alert(data.message);
                    viewCategoryDetails(currentCategoryId); // Refresh details
                })
                .catch(error => alert("Error editing subcategory: " + error));
        }

        // Initial load
        loadCategories();
    </script>
@endsection
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
