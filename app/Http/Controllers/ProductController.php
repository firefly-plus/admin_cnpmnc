<?php

namespace App\Http\Controllers;

use App\Models\InvoiceDetail;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductVariation;
use App\Models\SupCategory;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    //

    public function danhSachSanPham(){

        $products = Product::with(['productImages', 'productVariations'])
        ->paginate(10);

        Log::info('Danh sách sản phẩm:', ['products' => json_encode($products)]);
        return view('product.product-management', compact('products'));
    }
    public function showThemSP(){
        $Subcategories = SupCategory::all();

        return view('product.add-product', compact('Subcategories'));
    }
    
    public function themSanPham(Request $request)
    {
        try {
            Log::info('Request data:', $request->all());
            // Tạo sản phẩm mới
            $chuoi=Str::random(5);
            Log::info('Sản phẩm được tạo:'.$chuoi);
            $product = Product::create([
                'id'             => $chuoi,
                'ID_SupCategory' => $request->input('ID_SupCategory'),
                'productName'    => $request->input('productName'),
                'description'    => $request->input('description'),
                'isDelete'       => 0,
                'createdAt'      => now(),
                'updatedAt'      => now(),
            ]);
            Log::info('Sản phẩm được tạo:', $product->toArray());
            
            if ($request->has('productImage')) {
                foreach ($request->file('productImage', []) as $image) {
                    if ($image->isValid()) {
                        $uploadedFileUrl = Cloudinary::upload($image->getRealPath(), [
                            'folder' => 'products'
                        ])->getSecurePath();
            
                        $productImage = ProductImage::create([
                            'ProductID' =>  $chuoi,
                            'IMG_URL'   => $uploadedFileUrl,
                        ]);
                        Log::info('Hình ảnh được thêm:', $productImage->toArray());
                    } else {
                        Log::error('Tệp ảnh không hợp lệ:', [$image]);
                    }
                }
            }

            // Thêm các biến thể sản phẩm
            $sizes = $request->input('size', []);
            $prices = $request->input('price', []);
            $stocks = $request->input('stock', []);
            if (count($sizes) !== count($prices) || count($sizes) !== count($stocks)) {
                throw new \Exception('Số lượng size, price, và stock không khớp.');
            }
            foreach ($sizes as $index => $size) {
                Log::info("Bắt đầu xử lý biến thể sản phẩm:", [
                    'index' => $index,
                    'size' => $size,
                    'price' => $prices[$index] ?? null,
                    'stock' => $stocks[$index] ?? null,
                ]);
            
                $productVariation =ProductVariation::create([
                    'id'         => Str::random(5),
                    'ID_Product'  =>  $chuoi, 
                    'size'       => $size,
                    'Price'      => $prices[$index],
                    'stock'      => $stocks[$index],
                    'isDelete'   => 0,
                    'createdAt'  => now(),
                    'updatedAt'  => now(),
                ]);
                Log::info("Biến thể sản phẩm đã được thêm:", $productVariation->toArray());
            }
            Log::info('Biến thể sản phẩm được thêm:', $productVariation->toArray());


            // return redirect()->back()->with('success', 'Sản phẩm được thêm thành công.');
            return response()->json([
                'success' => true,
                'message' => 'Sản phẩm đã được thêm thành công!',
                'redirect_url' => route('danhSachSanPham'), // Gọi đúng tên route đã được gán
            ], 200);

        } catch (\Exception $e) {
            
            
            return redirect()->back()->withErrors('Có lỗi xảy ra: ' . $e->getMessage());
        }
        
    }

    public function suaSanPham(Request $request)
    {
        try {
            Log::info('Request data:', $request->all());
            // Lấy sản phẩm theo ID
            $product = Product::findOrFail($request->input('productId'));
            Log::info('Tìm thấy sản phẩm.', ['product' => $product]);
            // Cập nhật thông tin cơ bản của sản phẩm
            $product->update([
                'productName' => $request->input('productName'),
                // 'ID_SupCategory' => $request->input('subCategoryID'),
                'description' => $request->input('productDescription'),
                'updatedAt' => now(),
            ]);
            Log::info('Cập nhật thông tin sản phẩm thành công.', ['updated_data' => $product]);

            // Kiểm tra và cập nhật ảnh
            if ($request->hasFile('productImages')) {
                // Xóa ảnh cũ trên Cloudinary và trong DB
                foreach ($product->productImages as $oldImage) {
                    cloudinary()->destroy($oldImage->IMG_URL);
                    $oldImage->delete();
                }
    
                // Thêm ảnh mới
                foreach ($request->file('productImages') as $image) {
                    $uploadedFile = Cloudinary::upload($image->getRealPath(), ['folder' => 'products']);
                    ProductImage::create([
                        'ProductID' => $product->id,
                        'IMG_URL' => $uploadedFile->getSecurePath(),
                    ]);
                }
            }
    
            // Xử lý các biến thể sản phẩm
            $existingVariations = $product->productVariations()->pluck('id')->toArray(); // Lấy danh sách ID biến thể cũ
            Log::info('Lấy danh sách biến thể hiện tại.', ['existing_variations' => $existingVariations]);
            $newVariations = $request->input('variations', []);
            $newSizes = $newVariations['size'] ?? [];
            $newPrices = $newVariations['price'] ?? [];
            $newStocks = $newVariations['stock'] ?? [];
    
            // Cập nhật hoặc thêm mới biến thể
            foreach ($newSizes as $index => $size) {
                $price = $newPrices[$index] ?? 0;
                $stock = $newStocks[$index] ?? 0;
    
                // Kiểm tra nếu biến thể đã tồn tại
                if (isset($existingVariations[$index])) {
                    $variation = ProductVariation::findOrFail($existingVariations[$index]);
                    $variation->update([
                        'size' => $size,
                        'price' => $price,
                        'stock' => $stock,
                    ]);
                } else {
                    // Thêm biến thể mới
                    ProductVariation::create([
                        'ProductID' => $product->id,
                        'size' => $size,
                        'price' => $price,
                        'stock' => $stock,
                    ]);
                }
            }
    
            // // Xóa các biến thể không còn tồn tại trong form
            // $submittedVariationIds = array_slice(array_keys($newSizes), 0, count($newSizes));
            // ProductVariation::whereNotIn('id', $submittedVariationIds)
            //     ->where('ProductID', $product->id)
            //     ->delete();
    
            // Phản hồi thành công
            return redirect()->back()->with('success', 'Cập nhật sản phẩm thành công!');
        } catch (\Exception $e) {
            // Xử lý lỗi
            return response()->json([
                'message' => 'Có lỗi xảy ra khi cập nhật sản phẩm!',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
    
    

    


    public function xoaSanPham($id)
    {
        // try {
            // Tìm sản phẩm theo ID
            $product = Product::find($id);

            $maBT= $product->productVariations->pluck('id');
            $maCT= InvoiceDetail::whereIn('id', $maBT)->exists();
            // Kiểm tra nếu sản phẩm đã được sử dụng trong hóa đơn
            if ($maCT ) {
                // Cập nhật isDelete = 1
                $product->isDelete = 1;
                $product->save();
                return redirect()->back()->with('success', 'Sản phẩm đã được ngưng bán.');
            }

           
                $product->productImages()->delete();

                $product->productVariations()->delete();
              
                $product->delete();
                // return response()->json([
                //     'success' => true,
                //     'message' => 'Sản phẩm đã được xóa thành công.',
                // ]);
                return redirect()->back()->with('success', 'Sản phẩm đã được xóa thành công.');
            
           
        // } catch (\Exception $e) {
            
        //     Log::error('Lỗi khi xóa sản phẩm: ' . $e->getMessage());

        //     // return response()->json([
        //     //     'success' => false,
        //     //     'message' => 'Đã xảy ra lỗi khi xóa sản phẩm.',
        //     // ], 500);
        //     return redirect()->back()->with('error', 'Đã xảy ra lỗi khi xóa sản phẩm.');
        // }
    }
}
