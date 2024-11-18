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

Route::get('/login', function () {
    return view('sign-in');
});

Route::post('/register',[App\Http\Controllers\AdminController::class,'register']);
Route::post('/login',[App\Http\Controllers\AdminController::class,'login']);
