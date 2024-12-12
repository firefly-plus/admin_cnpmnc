<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductVariation;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RecommenderController extends Controller
{
    //
    public function timSanPhamTuongTu(Request $request): JsonResponse
    {
        try {
            $productId = $request->input('ID_Product');
            $productVariation = ProductVariation::where('ID_Product', $productId)->first();

            while (!$productVariation && $productId > 0) {
                $productId--;
                $productVariation = ProductVariation::where('ID_Product', $productId)->first();
            }

            if (!$productVariation) {
                return response()->json([
                    'message' => 'Không tìm thấy sản phẩm hoặc sản phẩm không có biến thể.',
                ], 404);
            }

            $suggestionCount = $request->input('so_goi_y', 5);

            $products = DB::table('product as p')
                ->join('productvariation as pv', 'p.id', '=', 'pv.ID_Product')
                ->select([
                    'p.id as ID_Product',
                    DB::raw('COALESCE(p.productName, "") as productName'),
                    DB::raw('COALESCE(p.description, "") as description'),
                    DB::raw('COALESCE(pv.size, "") as size'),
                    DB::raw('COALESCE(pv.Price, 0) as price'),
                    DB::raw('COALESCE(pv.stock, 0) as stock'),
                    DB::raw('COALESCE(pv.isDelete, 0) as isDeleted')
                ])
                ->get();

            if ($products->isEmpty()) {
                return response()->json([
                    'message' => 'Không tìm thấy sản phẩm nào trong cơ sở dữ liệu.',
                ], 404);
            }

            $products = $products->map(function ($item) {
                return (array)$item;
            });

            $products = collect($products)->map(function ($item) {
                $item['combinedFeatures'] = implode(' ', array_filter([
                    $item['productName'] ?? '',
                    $item['description']??'',
                    $item['size'] ?? '',
                    $item['price'] ?? 0,
                    $item['stock'] ?? 0,
                ]));
                return $item;
            });

            $features = $products->pluck('combinedFeatures')->toArray();

            if (empty($features)) {
                return response()->json([
                    'message' => 'Không có dữ liệu đặc trưng hợp lệ để tính toán độ tương đồng.',
                ], 400);
            }

            $tfMatrix = $this->calculateTfIdfMatrix($features);
            $similarityMatrix = $this->calculateCosineSimilarity($tfMatrix);

            $selectedIndex = $products->search(fn($item) => $item['ID_Product'] == $productId);

            if ($selectedIndex === false) {
                return response()->json([
                    'message' => 'Không tìm thấy sản phẩm được chọn.',
                ], 404);
            }

            $similarProducts = collect($similarityMatrix[$selectedIndex])
                ->map(fn($similarity, $index) => ['index' => $index, 'similarity' => $similarity])
                ->sortByDesc('similarity')
                ->skip(1) 
                ->map(function ($item) use ($products) {
                    $product = $products[$item['index']];
                    return [
                        'ID_Product' => $product['ID_Product'],
                        'productName' => $product['productName'],
                        'similarity' => $item['similarity'],
                    ];
                });

            $uniqueProducts = [];
            foreach ($similarProducts as $item) {
                if (!in_array($item['ID_Product'], array_column($uniqueProducts, 'ID_Product'))) {
                    $uniqueProducts[] = $item;
                }
                if (count($uniqueProducts) >= $suggestionCount) {
                    break;
                }
            }

            $recommendedProducts = Product::with(['subCategory', 'productImages', 'productVariations.variationdiscount'])
                ->whereIn('id', array_column($uniqueProducts, 'ID_Product'))
                ->get();
                $recommendedProductsIds = $recommendedProducts->pluck('id')->toArray();
                $idString = implode(',', $recommendedProductsIds);
                
                // Gọi stored procedure
                $pr = DB::select("call GetProductsByIds(?)", [$idString]);
           
            return response()->json($pr);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return response()->json([
                'message' => 'Đã xảy ra lỗi khi xử lý dữ liệu.',
            ], 500);
        }
    }

    private function calculateTfIdfMatrix($features)
    {
        $numDocuments = count($features);
        $documentTermMatrix = [];
        foreach ($features as $doc) {
            $terms = explode(' ', $doc);
            $termFrequency = array_count_values($terms);
            $documentTermMatrix[] = $termFrequency;
        }
        $allTerms = [];
        foreach ($documentTermMatrix as $termFreq) {
            $allTerms = array_merge($allTerms, array_keys($termFreq));
        }
        $allTerms = array_unique($allTerms);
        $idf = [];
        foreach ($allTerms as $term) {
            $idf[$term] = log($numDocuments / (1 + array_sum(array_map(fn($doc) => isset($doc[$term]) ? 1 : 0, $documentTermMatrix))));
        }
        $tfIdfMatrix = [];
        foreach ($documentTermMatrix as $doc) {
            $tfIdfDoc = [];
            foreach ($doc as $term => $count) {
                $tfIdfDoc[$term] = ($count / count($doc)) * $idf[$term];
            }
            $tfIdfMatrix[] = $tfIdfDoc;
        }

        return $tfIdfMatrix;
    }
    private function calculateCosineSimilarity($tfMatrix)
    {
        $similarityMatrix = [];
        foreach ($tfMatrix as $i => $vectorA) {
            foreach ($tfMatrix as $j => $vectorB) {
                $similarityMatrix[$i][$j] = $this->cosineSimilarity($vectorA, $vectorB);
            }
        }
        return $similarityMatrix;
    }
    private function cosineSimilarity($vectorA, $vectorB)
    {
        $dotProduct = 0;
        $magnitudeA = 0;
        $magnitudeB = 0;

        foreach ($vectorA as $term => $value) {
            $dotProduct += $value * ($vectorB[$term] ?? 0);
            $magnitudeA += $value * $value;
        }

        foreach ($vectorB as $value) {
            $magnitudeB += $value * $value;
        }

        return $dotProduct / (sqrt($magnitudeA) * sqrt($magnitudeB));
    }

}
