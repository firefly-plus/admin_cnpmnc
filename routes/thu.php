<?php

use Illuminate\Support\Facades\Route;


Route::middleware('permission:Quản lý danh mục - Xem danh mục Layout')->group(function() {
    Route::get('/category-management.html', [App\Http\Controllers\CategoryController::class, 'showCategoryPage']);
    Route::get('/categories', [App\Http\Controllers\CategoryController::class, 'index']);
    Route::post('/category', [App\Http\Controllers\CategoryController::class, 'addCategory']);
    Route::get('/category/{id}', [App\Http\Controllers\CategoryController::class, 'getSubcategoriesByCategoryId']);
    Route::put('/category/{id}', [App\Http\Controllers\CategoryController::class, 'editCategory']);
    Route::delete('/category/{id}', [App\Http\Controllers\CategoryController::class, 'deleteCategory']);
    Route::post('/category/{categoryId}/supcategory', [App\Http\Controllers\CategoryController::class, 'addSupCategory']);
    Route::put('/category/{categoryId}/supcategory/{supCategoryId}', [App\Http\Controllers\CategoryController::class, 'editSupCategory']);
    Route::delete('/category/{categoryId}/supcategory/{supCategoryId}', [App\Http\Controllers\CategoryController::class, 'deleteSupCategory']);

});
