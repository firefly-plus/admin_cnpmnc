<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\SupCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;


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
    // public function getCategoryById($id)
    // {
    //     $category = Category::with('supCategories')->findOrFail($id);
    //     return response()->json($category);
    // }

    public function getSubcategoriesByCategoryId($categoryId)
    {
        $category = Category::with('subCategories')->findOrFail($categoryId);

        $subCategories = $category->subCategories->map(function ($subCategory) {
            return [
                'id' => (string) $subCategory->id,
                'SupCategoryName' => $subCategory->SupCategoryName,
                'categoryId' => $subCategory->categoryId,
                'isDelete' => $subCategory->isDelete,
                'createdAt' => $subCategory->createdAt,
                'updatedAt' => $subCategory->updatedAt
            ];
        });

        return response()->json([
            'id' => $category->id,
            'categoryName' => $category->categoryName,
            'isDelete' => $category->isDelete,
            'createdAt' => $category->createdAt,
            'updatedAt' => $category->updatedAt,
            'sub_categories' => $subCategories
        ]);
    }


    public function addCategory(Request $request)
    {
        $category = new Category();
        $category->categoryName = $request->categoryName;
        $category->isDelete = 0;
        $category->createdAt = now();
        $category->updatedAt = now();
        $category->save();

        return response()->json(['message' => 'Category đã được thêm thành công!'], 201);
    }

    public function editCategory(Request $request, $id)
    {
        $category = Category::find($id);
        if (!$category) {
            return response()->json(['message' => 'Category not found'], 404);
        }

        // Validate input
        $request->validate([
            'categoryName' => 'required|string|max:255',
        ]);

        // Update category
        $category->categoryName = $request->categoryName;
        $category->save();

        return response()->json(['message' => 'Đã cập nhật danh mục thành công']);
    }

    public function deleteCategory($id)
    {
        $category = Category::find($id);
        if (!$category) {
            return response()->json(['message' => 'Không tìm thấy'], 404);
        }

        // Xóa danh mục
        $category->delete();

        return response()->json(['message' => 'Xóa danh mục thành công']);
    }

    public function addSupCategory(Request $request, $categoryId)
    {
        // Validate dữ liệu từ người dùng
        $validatedData = $request->validate([
            'SupCategoryName' => 'required|string|max:255',
        ]);
        $SupCategoryName = $validatedData['SupCategoryName'];
        $categoryCode = $categoryId;
        $namePrefix = strtoupper(substr($SupCategoryName, 0, 3));
        $randomSuffix = Str::upper(Str::random(5));
        $subCategoryCode = $categoryCode . $namePrefix . $randomSuffix;
        $subCategory = new SupCategory();
        $subCategory->id = $subCategoryCode;
        $subCategory->SupCategoryName = $SupCategoryName;
        $subCategory->categoryId = $categoryId;
        $subCategory->isDelete = 0;
        $subCategory->createdAt = now();
        $subCategory->updatedAt = now();
        $subCategory->save();

        // Trả về kết quả
        return response()->json([
            'message' => 'Thêm danh mục con thành công',
            'subcategory' => [
                'id' => $subCategoryCode,
                'SupCategoryName' => $SupCategoryName,
                'categoryId' => $categoryId,
                'subCategoryCode' => $subCategoryCode,
            ]
        ]);
    }

    public function editSupCategory(Request $request, $categoryId, $supCategoryId)
    {
        $category = Category::findOrFail($categoryId);
        $supCategory = SupCategory::findOrFail($supCategoryId);

        $request->validate([
            'SupCategoryName' => 'required|string|max:255',
        ]);

        $supCategory->SupCategoryName = $request->SupCategoryName;
        $supCategory->updatedAt = now();

        $supCategory->save();

        return response()->json([
            'message' => 'Cập nhật danh mục con thành công!',
            'subcategory' => $supCategory
        ]);
    }

    public function deleteSupCategory($categoryId, $supCategoryId)
    {
        $category = Category::findOrFail($categoryId);
        $supCategory = SupCategory::findOrFail($supCategoryId);

        $supCategory->delete();

        return response()->json(['message' => 'Danh mục con đã được xóa thành công!'], 200);
    }
}
