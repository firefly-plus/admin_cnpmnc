<?php


use Illuminate\Support\Facades\Route;

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
Route::get('/admin', function () {
    return view('sign-in');
});


include 'admin.php';
Route::get('/user', function () {
    return view('user-management');
});

Route::get('/product', function () {
    return view('product.testproduct-management');
});


Route::post('/register',[App\Http\Controllers\AdminController::class,'register']);
Route::post('/login',[App\Http\Controllers\AdminController::class,'login']);
Route::get('/product-management.html',[App\Http\Controllers\AdminController::class,'showProductManagement']);

Route::get('/invoice-management.html',[App\Http\Controllers\AdminController::class,'showInvoice']);
Route::get('/invoice', [App\Http\Controllers\AdminController::class, 'Invoice']);
Route::put('/updateorderstatus', [App\Http\Controllers\AdminController::class, 'updateOrderStatus']);
Route::get('/export-pdf', [App\Http\Controllers\AdminController::class, 'exportPdf']);

Route::get('/statistics.html', [App\Http\Controllers\AdminController::class, 'Statistics']);
Route::post('/statistics/revenue', [App\Http\Controllers\AdminController::class, 'getRevenueData'])->name('statistics.revenue');

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

Route::get('/user-management.html', [App\Http\Controllers\AdminController::class, 'showUser']);
Route::get('/getuser', [App\Http\Controllers\AdminController::class, 'getUser']);
Route::get('/getuserbyid', [App\Http\Controllers\AdminController::class, 'getUserById']);
Route::get('/getinvoicebyuser', [App\Http\Controllers\AdminController::class, 'getInvoiceByUser']);
Route::post('/updatestatususer', [App\Http\Controllers\AdminController::class, 'updateStatusUser']);
Route::post('/getuserbystatus', [App\Http\Controllers\AdminController::class, 'getUserByStatus']);

Route::get('/getvoucher', [App\Http\Controllers\AdminController::class, 'getVoucher']);
Route::get('/voucher-management.html', [App\Http\Controllers\AdminController::class, 'showVoucher']);

Route::get('/warehouse-management.html', [App\Http\Controllers\AdminController::class, 'showWareHouse']);


Route::get('/category-management.html', [App\Http\Controllers\CategoryController::class, 'showCategoryPage']);
Route::get('/categories', [App\Http\Controllers\CategoryController::class, 'index']);
Route::post('/category', [App\Http\Controllers\CategoryController::class, 'addCategory']);
Route::get('/category/{id}', [App\Http\Controllers\CategoryController::class, 'getSubcategoriesByCategoryId']);
Route::put('/category/{id}', [App\Http\Controllers\CategoryController::class, 'editCategory']);
Route::delete('/category/{id}', [App\Http\Controllers\CategoryController::class, 'deleteCategory']);
Route::post('/category/{categoryId}/supcategory', [App\Http\Controllers\CategoryController::class, 'addSupCategory']);
Route::put('/category/{categoryId}/supcategory/{supCategoryId}', [App\Http\Controllers\CategoryController::class, 'editSupCategory']);
Route::delete('/category/{categoryId}/supcategory/{supCategoryId}', [App\Http\Controllers\CategoryController::class, 'deleteSupCategory']);


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


