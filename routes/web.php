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


Route::post('/login',[App\Http\Controllers\AdminController::class,'login']);
Route::get('/product-management.html',[App\Http\Controllers\AdminController::class,'showProductManagement']);
Route::get('/invoice-management.html',[App\Http\Controllers\AdminController::class,'showInvoice']);
Route::get('/invoice', [App\Http\Controllers\AdminController::class, 'Invoice']);
Route::get('/export-pdf', [App\Http\Controllers\AdminController::class, 'exportPdf']);

Route::get('/statistics.html', [App\Http\Controllers\AdminController::class, 'Statistics']);
Route::post('/statistics/revenue', [App\Http\Controllers\AdminController::class, 'getRevenueData'])->name('statistics.revenue');

Route::get('/promotion-management.html', [App\Http\Controllers\AdminController::class, 'showPromotion']);
Route::get('/getdiscount', [App\Http\Controllers\AdminController::class, 'getDiscount']);
Route::get('/getproductvariationbycategory', [App\Http\Controllers\AdminController::class, 'getProductVariationByCate']);
Route::get('/getproductvariationbysubcategory', [App\Http\Controllers\AdminController::class, 'getProductVariationBySubCate']);