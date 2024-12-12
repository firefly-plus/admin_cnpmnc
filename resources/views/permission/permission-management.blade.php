@extends('layout.index')
@section('title', 'Danh Sách Quyền')
@section('css')
<style>
    .card-header {
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis; /* Cắt chữ nếu quá dài */
        word-wrap: break-word;   /* Cho phép xuống dòng nếu cần */
    }
</style>
@endsection

@section('content')

<div class="container mt-4">
    <h2 class="text-center mb-4">Danh Sách Quyền Người Dùng</h2>

    <!-- Bố cục 3 cột cho các quyền -->
    <div class="row" id="user-role-container">
        <!-- Các quyền người dùng sẽ được tải từ API -->
    </div>
</div>

<hr style="border: 1px solid #dfdddd">

<div class="container mt-4" style="border: 1px solid">
    <h4 class="mb-4">Quyền Báo Cáo và Thống Kê</h4>

    
    <div class="row" id="report-role-container">
       
    </div>
</div>

<div class="container mt-4" style="border: 1px solid">
    <h4 class="mb-4">Blog</h4>

   
    <div class="row" id="report-blog-container">
        
    </div>
</div>

<div class="container mt-4" style="border: 1px solid">
    <h4 class="mb-4">Giảm giá - Xem giảm giá Layout</h4>

   
    <div class="row" id="report-discount-container">
        
    </div>
</div>

<div class="container mt-4" style="border: 1px solid">
    <h4 class="mb-4">Phân quyền - Xem phân quyền Layout</h4>

   
    <div class="row" id="report-permission-container">
        
    </div>
</div>

<div class="container mt-4" style="border: 1px solid">
    <h4 class="mb-4">Quản lý danh mục</h4>

   
    <div class="row" id="report-managementcategory-container">
        
    </div>
</div>

<div class="container mt-4" style="border: 1px solid">
    <h4 class="mb-4">Quản lý khách hàng </h4>

   
    <div class="row" id="report-managementcustomer-container">
        
    </div>
</div>

<div class="container mt-4" style="border: 1px solid">
    <h4 class="mb-4">Quản lý kho</h4>

   
    <div class="row" id="report-managementwarehouse-container">
        
    </div>
</div>

<div class="container mt-4" style="border: 1px solid">
    <h4 class="mb-4">Quản lý nhân viên</h4>

   
    <div class="row" id="report-managementemployee-container">
        
    </div>
</div>

<div class="container mt-4" style="border: 1px solid">
    <h4 class="mb-4">Quản lý sản phẩm</h4>

   
    <div class="row" id="report-managementproduct-container">
        
    </div>
</div>

<div class="container mt-4" style="border: 1px solid">
    <h4 class="mb-4">Quản lý đơn hàng</h4>

   
    <div class="row" id="report-managementinvoice-container">
        
    </div>
</div>

@endsection

@section('js')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>

$(document).ready(function () {
    let selectedUserRole = null; 
    let checkedIds = []; 

    /**
     * Hàm load danh sách quyền người dùng
     */
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
                                        <input type="radio" name="user-role" id="${role.id}" value="${role.id}">
                                        ${role.name}
                                    </div>
                                </div>
                            </div>
                        `;
                    }
                });
                $('#user-role-container').html(userRolesHTML);

                // Khi thay đổi radio button
                $('input[name="user-role"]').change(function () {
                    selectedUserRole = $(this).val();
                    console.log('Quyền được chọn:', selectedUserRole);

                    // Gọi API để lọc theo quyền
                    filterPermissions(selectedUserRole);
                });
            },
            error: function () {
                alert('Có lỗi xảy ra khi tải danh sách quyền người dùng.');
            }
        });
    }

   
    function filterPermissions(roleId) {
        
        checkedIds = [];

        $.ajax({
            url: `/getrolepermission?role_id=${roleId}`, 
            method: 'GET',
            success: function (response) {
                console.log('Danh sách quyền theo role:', response);
              
                $('input[type="checkbox"]').prop('checked', false);

                
                response.forEach(function (id) {
                    $(`#${id}`).prop('checked', true);
                   
                    if (!checkedIds.includes(String(id))) {
                        checkedIds.push(String(id)); // Chuyển đổi ID thành chuỗi
                    }
                });
                // updateRolePermissions(selectedUserRole, checkedIds);
                console.log('Danh sách quyền đã chọn:', checkedIds);
            },
            error: function () {
                alert('Có lỗi xảy ra khi lọc quyền.');
            }
        });
    }

    function updateRolePermissions(roleId, permissions) {
        if (!roleId) {
            alert('Vui lòng chọn một quyền người dùng trước khi cập nhật.');
            return;
        }

        $.ajax({
            url: '/updaterolepermission', 
            method: 'POST',
            contentType: 'application/json',
            data: JSON.stringify({
                role_id: roleId, 
                ds_role_permission: permissions 
            }),
            success: function (response) {
                if (response.message) {
                    // alert('Cập nhật quyền thành công.');
                } else {
                    // alert('Có lỗi xảy ra: ' + response.message);
                }
            },
            error: function () {
                alert('Có lỗi xảy ra khi cập nhật quyền.');
            }
        });
    }

   
    function loadReport() {
        $.ajax({
            url: '/getpermission', 
            method: 'GET',
            success: function (response) {
                const reportRoles = response.filter(role => role.name.includes('Báo cáo & Thống kê'));
                let reportRolesHTML = '';
                reportRoles.forEach(function (role) {
                    reportRolesHTML += `
                        <div class="col-md-4 mb-4">
                            <div class="card">
                                <div class="card-header text-center">
                                    <input type="checkbox" id="${role.id}" data-name="${role.name}">
                                    ${role.name}
                                </div>
                            </div>
                        </div>
                    `;
                });
                $('#report-role-container').html(reportRolesHTML);

               
                $('input[type="checkbox"]').change(function () {
                    const id = $(this).attr('id');
                    console.log("Đang thay đổi checkbox với ID: ", id);  

                    if ($(this).is(':checked')) {
                        
                        if (!checkedIds.includes(String(id))) {
                            checkedIds.push(String(id)); 
                        }
                    } else {
                        
                        checkedIds = checkedIds.filter(item => item !== String(id)); 
                    }
                    updateRolePermissions(selectedUserRole, checkedIds);
                    console.log('Danh sách ID đã chọn:', checkedIds);  
                });
            },
            error: function () {
                alert('Có lỗi xảy ra khi tải danh sách quyền Báo cáo và Thống Kê.');
            }
        });
    }
   
    function loadReportBlog() {
        $.ajax({
            url: '/getpermission', 
            method: 'GET',
            success: function (response) {
                const blogRoles = response.filter(role => role.name.includes('Blog'));
                let blogRolesHTML = '';
                blogRoles.forEach(function (role) {
                    blogRolesHTML += `
                        <div class="col-md-4 mb-4">
                            <div class="card">
                                <div class="card-header text-center">
                                    <input type="checkbox" id="${role.id}" data-name="${role.name}">
                                    ${role.name}
                                </div>
                            </div>
                        </div>
                    `;
                });
                $('#report-blog-container').html(blogRolesHTML);

                
                $('input[type="checkbox"]').change(function () {
                    const id = $(this).attr('id');
                    if ($(this).is(':checked')) {
                        if (!checkedIds.includes(String(id))) {
                            checkedIds.push(String(id)); 
                        }
                    } else {
                        checkedIds = checkedIds.filter(item => item !== String(id)); 
                    }
                    updateRolePermissions(selectedUserRole, checkedIds);
                    console.log('Danh sách ID đã chọn:', checkedIds);
                });
            },
            error: function () {
                alert('Có lỗi xảy ra khi tải danh sách quyền Blog.');
            }
        });
    }

    function loadReportDiscount() {
        $.ajax({
            url: '/getpermission', 
            method: 'GET',
            success: function (response) {
                const blogRoles = response.filter(role => role.name.includes('Giảm giá - Xem giảm giá Layout'));
                let blogRolesHTML = '';
                blogRoles.forEach(function (role) {
                    blogRolesHTML += `
                        <div class="col-md-4 mb-4">
                            <div class="card">
                                <div class="card-header text-center">
                                    <input type="checkbox" id="${role.id}" data-name="${role.name}">
                                    ${role.name}
                                </div>
                            </div>
                        </div>
                    `;
                });
                $('#report-discount-container').html(blogRolesHTML);

                
                $('input[type="checkbox"]').change(function () {
                    const id = $(this).attr('id');
                    if ($(this).is(':checked')) {
                        if (!checkedIds.includes(String(id))) {
                            checkedIds.push(String(id)); 
                        }
                    } else {
                        checkedIds = checkedIds.filter(item => item !== String(id)); 
                    }
                    updateRolePermissions(selectedUserRole, checkedIds);
                    console.log('Danh sách ID đã chọn:', checkedIds);
                });
            },
            error: function () {
                alert('Có lỗi xảy ra khi tải danh sách quyền Blog.');
            }
        });
    }

    function loadReportPermission() {
        $.ajax({
            url: '/getpermission', 
            method: 'GET',
            success: function (response) {
                const blogRoles = response.filter(role => role.name.includes('Phân quyền'));
                let blogRolesHTML = '';
                blogRoles.forEach(function (role) {
                    blogRolesHTML += `
                        <div class="col-md-4 mb-4">
                            <div class="card">
                                <div class="card-header text-center">
                                    <input type="checkbox" id="${role.id}" data-name="${role.name}">
                                    ${role.name}
                                </div>
                            </div>
                        </div>
                    `;
                });
                $('#report-permission-container').html(blogRolesHTML);

                
                $('input[type="checkbox"]').change(function () {
                    const id = $(this).attr('id');
                    if ($(this).is(':checked')) {
                        if (!checkedIds.includes(String(id))) {
                            checkedIds.push(String(id)); 
                        }
                    } else {
                        checkedIds = checkedIds.filter(item => item !== String(id)); 
                    }
                    updateRolePermissions(selectedUserRole, checkedIds);
                    console.log('Danh sách ID đã chọn:', checkedIds);
                });
            },
            error: function () {
                alert('Có lỗi xảy ra khi tải danh sách quyền Blog.');
            }
        });
    }

    function loadReportManagementCategory() {
        $.ajax({
            url: '/getpermission', 
            method: 'GET',
            success: function (response) {
                const blogRoles = response.filter(role => role.name.includes('Quản lý danh mục'));
                let blogRolesHTML = '';
                blogRoles.forEach(function (role) {
                    blogRolesHTML += `
                        <div class="col-md-4 mb-4">
                            <div class="card">
                                <div class="card-header text-center">
                                    <input type="checkbox" id="${role.id}" data-name="${role.name}">
                                    ${role.name}
                                </div>
                            </div>
                        </div>
                    `;
                });
                $('#report-managementcategory-container').html(blogRolesHTML);

                
                $('input[type="checkbox"]').change(function () {
                    const id = $(this).attr('id');
                    if ($(this).is(':checked')) {
                        if (!checkedIds.includes(String(id))) {
                            checkedIds.push(String(id)); 
                        }
                    } else {
                        checkedIds = checkedIds.filter(item => item !== String(id)); 
                    }
                    updateRolePermissions(selectedUserRole, checkedIds);
                    console.log('Danh sách ID đã chọn:', checkedIds);
                });
            },
            error: function () {
                alert('Có lỗi xảy ra khi tải danh sách quyền Blog.');
            }
        });
    }

    function loadReportManagementCustomer() {
        $.ajax({
            url: '/getpermission', 
            method: 'GET',
            success: function (response) {
                const blogRoles = response.filter(role => role.name.includes('Quản lý khách hàng'));
                let blogRolesHTML = '';
                blogRoles.forEach(function (role) {
                    blogRolesHTML += `
                        <div class="col-md-4 mb-4">
                            <div class="card">
                                <div class="card-header text-center">
                                    <input type="checkbox" id="${role.id}" data-name="${role.name}">
                                    ${role.name}
                                </div>
                            </div>
                        </div>
                    `;
                });
                $('#report-managementcustomer-container').html(blogRolesHTML);

                
                $('input[type="checkbox"]').change(function () {
                    const id = $(this).attr('id');
                    if ($(this).is(':checked')) {
                        if (!checkedIds.includes(String(id))) {
                            checkedIds.push(String(id)); 
                        }
                    } else {
                        checkedIds = checkedIds.filter(item => item !== String(id)); 
                    }
                    updateRolePermissions(selectedUserRole, checkedIds);
                    console.log('Danh sách ID đã chọn:', checkedIds);
                });
            },
            error: function () {
                alert('Có lỗi xảy ra khi tải danh sách quyền Blog.');
            }
        });
    }

    function loadReportManagementWareHouse() {
        $.ajax({
            url: '/getpermission', 
            method: 'GET',
            success: function (response) {
                const blogRoles = response.filter(role => role.name.includes('Quản lý kho'));
                let blogRolesHTML = '';
                blogRoles.forEach(function (role) {
                    blogRolesHTML += `
                        <div class="col-md-4 mb-4">
                            <div class="card">
                                <div class="card-header text-center">
                                    <input type="checkbox" id="${role.id}" data-name="${role.name}">
                                    ${role.name}
                                </div>
                            </div>
                        </div>
                    `;
                });
                $('#report-managementwarehouse-container').html(blogRolesHTML);

                
                $('input[type="checkbox"]').change(function () {
                    const id = $(this).attr('id');
                    if ($(this).is(':checked')) {
                        if (!checkedIds.includes(String(id))) {
                            checkedIds.push(String(id)); 
                        }
                    } else {
                        checkedIds = checkedIds.filter(item => item !== String(id)); 
                    }
                    updateRolePermissions(selectedUserRole, checkedIds);
                    console.log('Danh sách ID đã chọn:', checkedIds);
                });
            },
            error: function () {
                alert('Có lỗi xảy ra khi tải danh sách quyền Blog.');
            }
        });
    }

    function loadReportManagementEmployee() {
        $.ajax({
            url: '/getpermission', 
            method: 'GET',
            success: function (response) {
                const blogRoles = response.filter(role => role.name.includes('Quản lý nhân viên'));
                let blogRolesHTML = '';
                blogRoles.forEach(function (role) {
                    blogRolesHTML += `
                        <div class="col-md-4 mb-4">
                            <div class="card">
                                <div class="card-header text-center">
                                    <input type="checkbox" id="${role.id}" data-name="${role.name}">
                                    ${role.name}
                                </div>
                            </div>
                        </div>
                    `;
                });
                $('#report-managementemployee-container').html(blogRolesHTML);

                
                $('input[type="checkbox"]').change(function () {
                    const id = $(this).attr('id');
                    if ($(this).is(':checked')) {
                        if (!checkedIds.includes(String(id))) {
                            checkedIds.push(String(id)); 
                        }
                    } else {
                        checkedIds = checkedIds.filter(item => item !== String(id)); 
                    }
                    updateRolePermissions(selectedUserRole, checkedIds);
                    console.log('Danh sách ID đã chọn:', checkedIds);
                });
            },
            error: function () {
                alert('Có lỗi xảy ra khi tải danh sách quyền Blog.');
            }
        });
    }

    function loadReportManagementProduct() {
        $.ajax({
            url: '/getpermission', 
            method: 'GET',
            success: function (response) {
                const blogRoles = response.filter(role => role.name.includes('Quản lý sản phẩm'));
                let blogRolesHTML = '';
                blogRoles.forEach(function (role) {
                    blogRolesHTML += `
                        <div class="col-md-4 mb-4">
                            <div class="card">
                                <div class="card-header text-center">
                                    <input type="checkbox" id="${role.id}" data-name="${role.name}">
                                    ${role.name}
                                </div>
                            </div>
                        </div>
                    `;
                });
                $('#report-managementproduct-container').html(blogRolesHTML);

                
                $('input[type="checkbox"]').change(function () {
                    const id = $(this).attr('id');
                    if ($(this).is(':checked')) {
                        if (!checkedIds.includes(String(id))) {
                            checkedIds.push(String(id)); 
                        }
                    } else {
                        checkedIds = checkedIds.filter(item => item !== String(id)); 
                    }
                    updateRolePermissions(selectedUserRole, checkedIds);
                    console.log('Danh sách ID đã chọn:', checkedIds);
                });
            },
            error: function () {
                alert('Có lỗi xảy ra khi tải danh sách quyền Blog.');
            }
        });
    }

    function loadReportManagementInvoice() {
        $.ajax({
            url: '/getpermission', 
            method: 'GET',
            success: function (response) {
                const blogRoles = response.filter(role => role.name.includes('Quản lý đơn hàng'));
                let blogRolesHTML = '';
                blogRoles.forEach(function (role) {
                    blogRolesHTML += `
                        <div class="col-md-4 mb-4">
                            <div class="card">
                                <div class="card-header text-center">
                                    <input type="checkbox" id="${role.id}" data-name="${role.name}">
                                    ${role.name}
                                </div>
                            </div>
                        </div>
                    `;
                });
                $('#report-managementinvoice-container').html(blogRolesHTML);

                
                $('input[type="checkbox"]').change(function () {
                    const id = $(this).attr('id');
                    if ($(this).is(':checked')) {
                        if (!checkedIds.includes(String(id))) {
                            checkedIds.push(String(id)); 
                        }
                    } else {
                        checkedIds = checkedIds.filter(item => item !== String(id)); 
                    }
                    updateRolePermissions(selectedUserRole, checkedIds);
                    console.log('Danh sách ID đã chọn:', checkedIds);
                });
            },
            error: function () {
                alert('Có lỗi xảy ra khi tải danh sách quyền Blog.');
            }
        });
    }

    
    loadUserRoles();
    loadReport();
    loadReportBlog();
    loadReportDiscount();
    loadReportPermission();
    loadReportManagementCategory();
    loadReportManagementCustomer();
    loadReportManagementWareHouse();
    loadReportManagementEmployee();
    loadReportManagementProduct();
    loadReportManagementInvoice();
});



</script>
@endsection
