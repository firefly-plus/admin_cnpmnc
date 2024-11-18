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

    public function suaSanPham(Request $request, $id)
    {
        $product = Product::findOrFail($id); 

      
        if ($request->hasFile('image')) {
            
            if ($product->productImages->count() > 0) {
                $oldImage = $product->productImages->first();
                cloudinary()->destroy($oldImage->image_url); 
                $oldImage->delete();
            }

          
            $image = $request->file('image');
            $uploadedFile = cloudinary()->upload($image->getRealPath(), [
                'folder' => 'products',
            ]);

            // Lưu ảnh mới vào cơ sở dữ liệu
            $productImage = new ProductImage();
            $productImage->ProductID = $product->id;
            $productImage->IMG_URL = $uploadedFile->getSecurePath(); // Lưu URL của ảnh
            $productImage->save();
        }

        // Cập nhật thông tin sản phẩm
        $product->update([
            'productName' => $request->input('productName'),
            'description' => $request->input('description'),
            'isDelete' => $request->input('isDelete'),
            'updatedAt' => now(),
        ]);

        return response()->json(['message' => 'Sản phẩm đã được cập nhật thành công']);
    }


    public function xoaSanPham($id)
    {
        $product = Product::findOrFail($id); 

       
        foreach ($product->productImages as $image) {
            cloudinary()->destroy($image->IMG_URL); 
            $image->delete(); 
        }

     
        $product->delete();

        return response()->json(['message' => 'Sản phẩm đã được xóa thành công']);
    }

}
