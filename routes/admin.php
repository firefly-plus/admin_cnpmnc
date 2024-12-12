<?php

use App\Http\Controllers\ProductController;

use Illuminate\Support\Facades\Route;

Route::middleware('permission:Quản lý sản phẩm - Xem Sản phẩm Layout')->group(function() {

Route::get('/danhsachsanpham', [ProductController::class,'danhSachSanPham'])->name('danhSachSanPham');;
Route::get('/showthemsanpham',[ProductController::class,'showThemSP']);
Route::post('/themsanpham',[ProductController::class,'themSanPham'])->name('products.store');
Route::delete('/xoasanpham/{id}',[ProductController::class,'xoaSanPham']);
Route::post('/suasanpham',[ProductController::class,'suaSanPham']);
});

