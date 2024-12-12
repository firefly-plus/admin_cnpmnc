@extends('layout.index')

@section('title', 'Category Management')

@section('css')
<style>
    table {
        width: 100%;
        border-collapse: collapse;
    }

    th, td {
        padding: 10px;
        text-align: left;
        border: 1px solid #ddd;
    }

    th {
        background-color: #f4f4f4;
    }

    tr:nth-child(even) {
        background-color: #f9f9f9;
    }

    tr:hover {
        background-color: #f1f1f1;
    }
</style>
@endsection

@section('content')
<div class="container my-5">
    <h1 class="mb-4">Thêm Nhân Viên</h1>

    <!-- Form thêm nhân viên -->
    <form id="addEmployeeForm" class="row g-3">
        @csrf
        <div class="col-md-6">
            <label for="FullName" class="form-label">Họ và tên</label>
            <input type="text" id="FullName" name="FullName" class="form-control" required>
        </div>

        <div class="col-md-6">
            <label for="Phone" class="form-label">Số điện thoại</label>
            <input type="text" id="Phone" name="Phone" class="form-control" required>
        </div>

        <div class="col-md-6">
            <label for="Passwords" class="form-label">Mật khẩu</label>
            <input type="password" id="Passwords" name="Passwords" class="form-control" required>
        </div>

        <div class="col-md-6">
            <label for="address" class="form-label">Địa chỉ</label>
            <input type="text" id="address" name="address" class="form-control">
        </div>

        <div class="col-12">
            <button type="submit" class="btn btn-primary">Thêm Nhân Viên</button>
        </div>
    </form>

    <div class="container mt-4" style="border: 1px solid">
        <h4>Danh Sách Quyền Người Dùng</h4>
        <div class="row" id="user-role-container">
            <!-- Các quyền người dùng sẽ được load vào đây -->
        </div>
    </div>

    <h1 class="mt-5">Danh sách nhân viên</h1>
    <table class="table table-striped mt-3" id="employeeTable">
        <thead>
            <tr>
                <th>Chọn</th>
                <th>ID</th>
                <th>Họ tên</th>
                <th>Số điện thoại</th>
                <th>Địa chỉ</th>
                <th>Trạng thái</th>
                <th>Ngày tạo</th>
                <th>Ngày cập nhật</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            <!-- Dữ liệu nhân viên sẽ được load vào đây -->
        </tbody>
    </table>
</div>
@endsection

@section('js')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
$(document).ready(function() {
    let selectEmployee = null;
    let selectedUserRole = null;
    let selectedRoles = [];  // Mảng lưu các quyền đã chọn

    // Load danh sách quyền người dùng
    function loadUserRoles() {
        $.ajax({
            url: '/getrole', 
            method: 'GET',
            success: function (response) {
                let userRolesHTML = '';
                response.forEach(function (role) {
                    if (role.isDelete === 0) {
                        userRolesHTML += `
                            <div class="col-md-4 mb-4">
                                <div class="card">
                                    <div class="card-header text-center">
                                        <input type="checkbox" name="user-role" id="${role.id}" value="${role.id}">
                                        ${role.name}
                                    </div>
                                </div>
                            </div>
                        `;
                    }
                });
                $('#user-role-container').html(userRolesHTML);

                // Khi thay đổi checkbox quyền người dùng
                $('input[name="user-role"]').change(function () {
                    let roleId = $(this).val();
                    let isChecked = $(this).prop('checked');

                    if (isChecked) {
                        // Thêm quyền vào mảng
                        if (!selectedRoles.includes(roleId)) {
                            selectedRoles.push(roleId.toString());

                            console.log("danh sách",selectedRoles);
                        }
                    } else {
                        // Xóa quyền khỏi mảng
                        selectedRoles = selectedRoles.filter(role => role !== roleId);
                    }

                    console.log('Danh sách quyền đã chọn:', selectedRoles);
                });
            },
            error: function () {
                alert('Có lỗi xảy ra khi tải danh sách quyền người dùng.');
            }
        });
    }

    // Tải quyền của nhân viên khi chọn nhân viên
    function loadEmployeeRoles(employeeId) {
        // Làm mới lại danh sách quyền đã chọn khi chọn nhân viên mới
        selectedRoles = [];  // Xóa mảng quyền đã chọn
        $('input[name="user-role"]').prop('checked', false);  // Bỏ chọn tất cả checkbox

        $.ajax({
            url: `/getEmployeeRoles?id=${employeeId}`,
            method: 'GET',
            success: function(response) {
                // Đánh dấu các quyền đã được gán cho nhân viên
                response.forEach(function(role) {
                    $(`#${role.role_id}`).prop('checked', true);
                    // Thêm quyền vào mảng
                    selectedRoles.push(role.role_id.toString());
                    console.log("Đã check " + role.role_id);
                });
            },
            error: function() {
                alert('Có lỗi xảy ra khi tải quyền nhân viên.');
            }
        });
    }

    // Tải danh sách nhân viên
    function loadEmployees() {
        $.ajax({
            url: '/dsemployees',
            method: 'GET',
            success: function(data) {
                $('#employeeTable tbody').empty();
                data.forEach(function(employee) {
                    let status = employee.isDelete ? 'Đã xóa' : 'Hoạt động';
                    let row = `<tr>
                                <td><input type="radio" name="employeeSelect" value="${employee.id}" onclick="selectEmployee(${employee.id})"></td>
                                <td>${employee.id}</td>
                                <td>${employee.FullName}</td>
                                <td>${employee.Phone}</td>
                                <td>${employee.address}</td>
                                <td>${status}</td>
                                <td>${employee.createdAt}</td>
                                <td>${employee.updatedAt}</td>
                               <td>
                                    <button id="btn-delete" class="btn btn-delete" data-id="${employee.id}">Xóa</button>
                                </td>
                              </tr>`;
                    $('#employeeTable tbody').append(row);
                });
            },
            error: function() {
                alert('Có lỗi xảy ra khi tải dữ liệu.');
            }
        });
    }

    // Xử lý khi chọn nhân viên
    window.selectEmployee = function(employeeId) {
        selectEmployee = employeeId;
        loadEmployeeRoles(employeeId); // Tải quyền của nhân viên khi chọn
        console.log("Selected Employee ID: " + selectEmployee);
    };

    // Cập nhật quyền người dùng
    $(document).on('change', 'input[name="user-role"]', function() {
        if (selectEmployee === null) {
            alert('Vui lòng chọn một nhân viên trước khi thay đổi quyền.');
            return;
        }

        let roleId = $(this).val();
        let isChecked = $(this).prop('checked');

        if (isChecked) {
            // Thêm quyền vào mảng
            if (!selectedRoles.includes(roleId)) {
                selectedRoles.push(roleId);
            }
        } else {
            // Xóa quyền khỏi mảng
            selectedRoles = selectedRoles.filter(role => role !== roleId);
        }

        console.log('Danh sách quyền đã chọn:', selectedRoles);

        $.ajax({
            url: '/suaQuyenNhanVien',
            method: 'POST',
            data: {
                selectedRoles: selectedRoles,
                selectEmployee: selectEmployee,
         
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                // alert(response.message ? 'Cập nhật quyền thành công!' : 'Cập nhật quyền thất bại.');
            },
            error: function() {
                alert('Có lỗi xảy ra khi cập nhật quyền.');
            }
        });
    });

    $(document).on('click', '#btn-delete', function() {
    let employeeId = $(this).data('id');
    if (confirm('Bạn có chắc muốn xóa nhân viên này?')) {
        $.ajax({
            url: `/xoanhanvien?id=${employeeId}`,
            method: 'post',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                alert('Xóa nhân viên thành công!');
                loadEmployees(); // Tải lại danh sách nhân viên
            },
            error: function() {
                alert('Có lỗi xảy ra khi xóa nhân viên.');
            }
        });
    }
});


    // Xử lý form thêm nhân viên
    $('#addEmployeeForm').on('submit', function(e) {
        e.preventDefault();
        
        $.ajax({
            url: '/themnhanvien',
            method: 'POST',
            data: $(this).serialize(),
            success: function(response) {
                alert('Thêm nhân viên thành công!');
                $('#addEmployeeForm')[0].reset();
                loadEmployees(); // Reload danh sách nhân viên
            },
            error: function(xhr) {
                let errors = xhr.responseJSON.errors;
                let errorMessage = 'Có lỗi xảy ra:\n';
                for (const key in errors) {
                    errorMessage += errors[key].join('\n') + '\n';
                }
                alert(errorMessage);
            }
        });
    });

    // Tải danh sách nhân viên và quyền khi trang được tải
    loadEmployees();
    loadUserRoles();
});

</script>
@endsection
