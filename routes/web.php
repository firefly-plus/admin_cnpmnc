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
//


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