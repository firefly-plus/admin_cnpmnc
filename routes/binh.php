<?php

use Illuminate\Support\Facades\Route;

Route::post('/addvoucher', [App\Http\Controllers\VoucherController::class, 'addVoucher']);
Route::get('/getvoucher/{id}', [App\Http\Controllers\VoucherController::class, 'getVoucherById']);
Route::put('/editvoucher/{id}', [App\Http\Controllers\VoucherController::class, 'editVoucher']);
Route::delete('/deletevoucher/{id}', [App\Http\Controllers\VoucherController::class, 'deleteVoucher']);
