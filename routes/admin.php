<?php

use App\Http\Controllers\ProductController;

use Illuminate\Support\Facades\Route;


Route::get('/danhsachsanpham', [ProductController::class,'danhSachSanPham']);
Route::post('/themsanpham',[ProductController::class,'themSanPham']);
Route::delete('/xoasanpham/{id}',[ProductController::class,'xoaSanPham']);
Route::post('/suasanpham',[ProductController::class,'suaSanPham']);