<?php

use App\Http\Controllers\ProductController;

use Illuminate\Support\Facades\Route;


Route::get('/danhsachsanpham', [ProductController::class,'danhSachSanPham']);
Route::post('/themsanpham',[ProductController::class,'themSanPham']);
Route::post('/xoasanpham',[ProductController::class,'xoaSanPham']);
Route::post('/suasanpham',[ProductController::class,'suaSanPham']);