<?php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DoitraController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

include 'admin.php';

include 'thu.php';
include 'binh.php';

Route::get('/', function () {
    return view('sign-in');
});

Route::middleware('permission:Giảm giá - Xem giảm giá Layout')->group(function() {
    Route::get('/promotion-management.html', [App\Http\Controllers\AdminController::class, 'showPromotion']);
    Route::get('/getdiscount', [App\Http\Controllers\AdminController::class, 'getDiscount']);
    Route::get('/getproductvariationbycategory', [App\Http\Controllers\AdminController::class, 'getProductVariationByCate']);
    Route::get('/getproductvariationbysubcategory', [App\Http\Controllers\AdminController::class, 'getProductVariationBySubCate']);
    Route::get('/getproductvariationbyproduct', [App\Http\Controllers\AdminController::class, 'getProductVariationByProduct']);
    Route::get('/getcategory', [App\Http\Controllers\AdminController::class, 'getCategory']);
    Route::get('/getsubcategory', [App\Http\Controllers\AdminController::class, 'getSubCategory']);
    Route::get('/getproduct', [App\Http\Controllers\AdminController::class, 'getProduct']);
    Route::get('/getproductvariationdiscount', [App\Http\Controllers\AdminController::class, 'getProductVariationDiscount']);
    Route::delete('/deletediscountbyproductvariation', [App\Http\Controllers\AdminController::class, 'deleteDiscountByProductVariation']);
    Route::post('/addvariationdiscount', [App\Http\Controllers\AdminController::class, 'addVariationDiscount']);
});

Route::middleware('permission:Phân quyền - Xem phân quyền Layout')->group(function() {
    Route::get('/permission-management.html', [App\Http\Controllers\AdminController::class, 'showPermission']);
    Route::get('/getpermission', [App\Http\Controllers\AdminController::class, 'getPermission']);
    Route::get('/getrole', [App\Http\Controllers\AdminController::class, 'getRole']);
    Route::get('/getrolepermission', [App\Http\Controllers\AdminController::class, 'getRolePermission']);
    Route::post('/updaterolepermission', [App\Http\Controllers\AdminController::class, 'updateRolePermission']);
    Route::post('/insertrole', [App\Http\Controllers\AdminController::class, 'insertRole']);
    Route::post('/deleterole', [App\Http\Controllers\AdminController::class, 'deleteRole']);
});

Route::middleware('permission:Hỗ trợ khách hàng - Xem hỗ trợ Layout')->group(function() {
    Route::get('/user-management.html', [App\Http\Controllers\AdminController::class, 'showUser']);
    Route::get('/getuser', [App\Http\Controllers\AdminController::class, 'getUser']);
    Route::get('/getuserbyid', [App\Http\Controllers\AdminController::class, 'getUserById']);
    Route::get('/getinvoicebyuser', [App\Http\Controllers\AdminController::class, 'getInvoiceByUser']);
    Route::post('/updatestatususer', [App\Http\Controllers\AdminController::class, 'updateStatusUser']);
    Route::post('/getuserbystatus', [App\Http\Controllers\AdminController::class, 'getUserByStatus']);
});

Route::middleware('permission:Quản lý nhân viên - Xem danh sách nhân viên Layout')->group(function() {
    Route::get('/dsemployees', [App\Http\Controllers\AdminController::class, 'DanhSachEmployee']);
    Route::get('/showthemnhanvien',[App\Http\Controllers\AdminController::class,'showThemNV']);
    Route::post('/themnhanvien', [App\Http\Controllers\AdminController::class, 'themNhanVien']);
    Route::post('/xoanhanvien', [App\Http\Controllers\AdminController::class,'xoaNhanVien']);
    Route::get('/getEmployeeRoles', [App\Http\Controllers\AdminController::class,'layQuyenNhanVien']);
    Route::post('/suaQuyenNhanVien', [App\Http\Controllers\AdminController::class,'suaQuyenNhanVien']);
});

Route::middleware('permission:Quản lý đơn hàng - Xem đơn hàng Layout')->group(function() {
    Route::get('/invoice-management.html',[App\Http\Controllers\AdminController::class,'showInvoice']);
    Route::get('/invoice', [App\Http\Controllers\AdminController::class, 'Invoice']);
    Route::put('/updateorderstatus', [App\Http\Controllers\AdminController::class, 'updateOrderStatus']);
    Route::get('/export-pdf', [App\Http\Controllers\AdminController::class, 'exportPdf']);
});

Route::post('/login', [App\Http\Controllers\AdminController::class, 'login'])->name('login');
Route::get('/product-management.html',[App\Http\Controllers\AdminController::class,'showProductManagement']);



Route::get('/statistics.html', [App\Http\Controllers\AdminController::class, 'Statistics'])->middleware('permission:Báo cáo & Thống kê - Xem báo cáo Layout');
Route::post('/statistics/revenue', [App\Http\Controllers\AdminController::class, 'getRevenueData'])->name('statistics.revenue');





Route::get('/getvoucher', [App\Http\Controllers\AdminController::class, 'getVoucher']);
Route::get('/voucher-management.html', [App\Http\Controllers\AdminController::class, 'showVoucher']);

Route::middleware('permission:Quản lý kho - Xem danh sách nhập kho Layout')->group(function() {
    Route::get('/warehouse/warehouse-management.html', [App\Http\Controllers\WarehouseController::class, 'showWareHouse']);
    Route::get('/form-add-don-hang.html', [App\Http\Controllers\WarehouseController::class, 'showFormAddDonHang']);
    // web.php
    Route::get('warehouse/{orderSupplierId}', [App\Http\Controllers\WarehouseController::class, 'showWarehouseDetail'])->name('warehouse.detail');

    Route::post('/warehouse/update-stock', [App\Http\Controllers\WarehouseController::class, 'updateStock'])->name('warehouse.updateStock');
    //code của vương
    Route::get('/warehouse-management.html', [App\Http\Controllers\AdminController::class, 'showWareHouse']);


});
//code của vương

//AI
Route::post('/recommender', [App\Http\Controllers\RecommenderController::class, 'timSanPhamTuongTu']);

// employee
Route::resource('suppliers', App\Http\Controllers\SupplierController::class);
Route::get('/suppliers', [App\Http\Controllers\SupplierController::class, 'index'])->name('suppliers.index');
// Đảm bảo route này tồn tại
Route::get('/suppliers/create', [App\Http\Controllers\SupplierController::class, 'create'])->name('suppliers.create');
Route::post('/suppliers', [App\Http\Controllers\SupplierController::class, 'store'])->name('suppliers.store');
Route::get('/suppliers/{id}', [App\Http\Controllers\SupplierController::class, 'show'])->name('suppliers.show');
Route::get('/suppliers/{id}/edit', [App\Http\Controllers\SupplierController::class, 'edit'])->name('suppliers.edit');
Route::put('/suppliers/{id}', [App\Http\Controllers\SupplierController::class, 'update'])->name('suppliers.update');
Route::patch('/suppliers/{id}', [App\Http\Controllers\SupplierController::class, 'update']);
Route::delete('suppliers/{id}', [App\Http\Controllers\SupplierController::class, 'destroy'])->name('suppliers.destroy');
Route::post('suppliers/{id}/restore', [App\Http\Controllers\SupplierController::class, 'restore'])->name('suppliers.restore');
