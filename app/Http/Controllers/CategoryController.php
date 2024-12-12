<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\SupCategory;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    // Hiển thị trang quản lý Category
    public function showCategoryPage()
    {
        return view('category.category-management');
    }

    // Lấy danh sách Category
    public function index()
    {
        $categories = Category::all();
        return response()->json($categories);
    }

    public function show($id)
    {
        $category = Category::with('subCategories')->find($id);

        if (!$category) {
            return response()->json(['message' => 'Category not found'], 404);
        }

        return response()->json($category); 
    }
    public function getCategoryById($id)
    {
        $category = Category::with('supCategories')->findOrFail($id);
        return response()->json($category);
    }

    public function getSubcategoriesByCategoryId($categoryId)
    {
        $subCategories = Category::findOrFail($categoryId)->subCategories;
        return response()->json($subCategories);
    }


    // public function addCategory(Request $request)
    // {
    //     $request->validate([
    //         'categoryName' => 'required|string|max:255',
    //         'isDelete' => 'boolean',
    //     ]);

    //     $category = new Category();
    //     $category->categoryName = $request->categoryName;
    //     $category->isDelete = $request->isDelete ?? 0;
    //     $category->save();

    //     return response()->json(['message' => 'Category added successfully!'], 201);
    // }
    
    public function addCategory(Request $request)
    {
        $category = new Category();
        $category->categoryName = $request->categoryName;  
        $category->isDelete = 0;
        $category->createdAt = now();  // Đặt giá trị cho createdAt
        $category->updatedAt = now();  // Đặt giá trị cho updatedAt
        $category->save();
        
        return response()->json(['message' => 'Category đã được thêm thành công!'], 201);
    }

    

    public function editCategory(Request $request, $id)
    {
        $category = Category::findOrFail($id);

        $request->validate([
            'categoryName' => 'string|max:255',
            'isDelete' => 'boolean',
        ]);

        $category->categoryName = $request->categoryName ?? $category->categoryName;
        $category->isDelete = $request->has('isDelete') ? $request->isDelete : $category->isDelete;
        $category->save();

        return response()->json(['message' => 'Category updated successfully!'], 200);
    }

    public function deleteCategory($id)
    {
        $category = Category::findOrFail($id);
        $category->delete();

        return response()->json(['message' => 'Category deleted successfully!'], 200);
    }

    
   
    public function addSupCategory(Request $request, $categoryId)
    {
        $category = Category::findOrFail($categoryId);

        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $supCategory = new SupCategory();
        $supCategory->category_id = $categoryId;
        $supCategory->name = $request->name;
        $supCategory->save();

        return response()->json(['message' => 'SupCategory added successfully!'], 201);
    }
    public function editSupCategory(Request $request, $categoryId, $supCategoryId)
    {
        $category = Category::findOrFail($categoryId);
        $supCategory = SupCategory::findOrFail($supCategoryId);

        $request->validate([
            'name' => 'string|max:255',
        ]);

        $supCategory->name = $request->name ?? $supCategory->name;
        $supCategory->save();

        return response()->json(['message' => 'SupCategory updated successfully!'], 200);
    }
    public function deleteSupCategory($categoryId, $supCategoryId)
    {
        $category = Category::findOrFail($categoryId);
        $supCategory = SupCategory::findOrFail($supCategoryId);
        $supCategory->delete();

        return response()->json(['message' => 'SupCategory deleted successfully!'], 200);
    }
}
