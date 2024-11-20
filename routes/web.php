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
include 'admin.php';
Route::get('/login', function () {
    return view('sign-in');
});

include 'admin.php';
Route::get('/user', function () {
    return view('user-management');
});

Route::post('/register',[App\Http\Controllers\AdminController::class,'register']);
Route::post('/login',[App\Http\Controllers\AdminController::class,'login']);
