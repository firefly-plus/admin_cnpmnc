<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductImage;
use App\Models\SupCategory;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    //

    public function danhSachSanPham(){

        $products = Product::with('productImages')->get();

      
        $categories = SupCategory::all();

       
        return view('product.product-management', compact('products', 'categories'));
    }

    public function themSanPham(Request $request)
    {
       
        $request->validate([
            'ID_SupCategory' => 'required|integer',
            'productName' => 'required|string|max:255',
            'description' => 'nullable|string',
            'productImages' => 'nullable|array', 
            'productImages.*' => 'image|mimes:jpeg,png,jpg,gif',
        ]);

        try {
         
            $product = Product::create([
                'ID_SupCategory' => $request->ID_SupCategory,
                'productName' => $request->productName,
                'description' => $request->description,
                'isDelete' => 0, 
                'createdAt' => now(),
                'updatedAt' => now(),
            ]);
          
            if ($request->hasFile('productImages')) {
                foreach ($request->file('productImages') as $image) {
                    $uploadedFile = Cloudinary::upload($image->getRealPath(), [
                       'folder' => 'products',
                    ]);
                    
                    // Kiểm tra kết quả trả về
                    if ($uploadedFile) {
                        $imageUrl = $uploadedFile->getSecurePath(); // Lấy URL an toàn
                        ProductImage::create([
                            'ProductID' => $product->id,
                            'IMG_URL' => $imageUrl,
                        ]);
                    } 
                }
            }

            return redirect()->back()->with('success', 'Sản phẩm đã được thêm.');
    
            // return response()->json([
            //     'message' => 'Sản phẩm và ảnh được thêm thành công!',
            //     'product' => $product,
            // ], 201);
            
        } catch (\Exception $e) {
            
            return response()->json([
                'message' => 'Có lỗi xảy ra khi thêm sản phẩm hoặc ảnh!',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function suaSanPham(Request $request)
    {
       
        try {
            // Lấy thông tin sản phẩm từ ID trong request
            $product = Product::findOrFail($request->input('id'));
           
            // Cập nhật thông tin cơ bản của sản phẩm
            $product->update([
                'productName' => $request->input('productName'),
                'ID_SupCategory' => $request->input('subCategoryID'),
                'description' => $request->input('description'),
                'updatedAt' => now(),
            ]);

            // Kiểm tra nếu có ảnh mới
            if ($request->hasFile('productImages')) {

                if ($product->productImages->count() > 0) {
                    foreach ($product->productImages as $oldImage) {
                        cloudinary()->destroy($oldImage->IMG_URL); // Xóa ảnh trên Cloudinary
                        $oldImage->delete(); // Xóa bản ghi trong cơ sở dữ liệu
                    }
                }

                // Tải lên và lưu ảnh mới
                foreach ($request->file('productImages') as $image) {
                    $uploadedFile = Cloudinary::upload($image->getRealPath(), [
                    'folder' => 'products',
                    ]);
                    
                    if ($uploadedFile) {
                        $imageUrl = $uploadedFile->getSecurePath(); // Lấy URL an toàn
                        ProductImage::create([
                            'ProductID' => $product->id,
                            'IMG_URL' => $imageUrl,
                        ]);
                    } 
                }
            }
            
            

            // Phản hồi sau khi cập nhật thành công
            return redirect()->back()->with('success', 'Thành công');
        
        } catch (\Exception $e) {
            // Xử lý lỗi và trả về phản hồi lỗi
            return response()->json([
                'message' => 'Có lỗi xảy ra khi cập nhật sản phẩm!',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    


    public function xoaSanPham($id)
    {
        $product = Product::findOrFail($id); 

       
        foreach ($product->productImages as $image) {
            cloudinary()->destroy($image->IMG_URL); 
            $image->delete(); 
        }

     
        $product->delete();

        return redirect()->back()->with('success', 'Thành công');
    }
}
