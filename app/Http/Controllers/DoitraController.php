<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Claims;
use App\Models\ClaimsProduct;
use App\Models\Invoice;
use App\Models\InvoiceDetail;
use App\Models\OrderSupplier;
use App\Models\OrderSupplierDetail;
use App\Models\Product;
use App\Models\ProductVariation;
use Illuminate\Support\Str; // Import lớp Str
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

use function PHPSTORM_META\map;

class DoitraController extends Controller
{

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'user_id' => 'required|integer|exists:users,id',
            'invoice_id' => 'required|string|max:50',
            'products' => 'required|array',
            'products.*.old_product_variant_id' => 'required|string|max:30',
            'products.*.new_product_variant_id' => 'nullable|string|max:30',
            'products.*.reason' => 'required|string|max:255',
            'products.*.quantity' => 'required|integer|min:1',
        ]);

        $doitra = Claims::create([
            'user_id' => $validatedData['user_id'],
            'invoice_id' => $validatedData['invoice_id'],
            'status' => 'pending',
        ]);

        foreach ($validatedData['products'] as $product) {
            $doitra->products()->create([
                'old_product_variant_id' => $product['old_product_variant_id'],
                'new_product_variant_id' => $product['new_product_variant_id'] ?? null,
                'quantity' => $product['quantity'],
                'reason' => $product['reason'],
            ]);
        }

        return response()->json([
            'message' => 'Yêu cầu đổi hàng đã được tạo thành công.',
            'claims_id' => $doitra->id,
        ], 201);
    }
    public function showAllReturns()
    {
        $allReturns = Claims::with(['user', 'products'])->get();

        // Truyền dữ liệu sang view
        return view('Claims.chitietdoitra', compact('allReturns'));
    }

    public function getOrderDetails($orderSupplier_id)
    {
        $orderDetails = InvoiceDetail::where('ID_Invoice', $orderSupplier_id)->get();

        if ($orderDetails->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy chi tiết đơn hàng.',
            ]);
        }

        $formattedOrderDetails = $orderDetails->map(function ($row) {
            $bienthe = ProductVariation::where('id', $row->ID_productVariation)->first();
            $tensanpham = Product::Where('id', $bienthe->ID_Product)->first();
            return [
                'id' => $row->id,
                'product_name' => $tensanpham->productName,
                'size' => $bienthe->size,
                'bienthe_id' => $bienthe->id,
                'price_product' => $bienthe->Price,
                'quantity' => $row->Quantity,
                'price' => $row->Amount,
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $formattedOrderDetails,
        ]);
    }

    public function searchReturns(Request $request)
    {
        $variant_id = $request->query('variant_id');

        if (!$variant_id) {
            return response()->json([
                'success' => false,
                'message' => 'Mã biến thể không hợp lệ.',
            ]);
        }

        $result = ProductVariation::where('id', $variant_id)
            ->with('product')
            ->first();

        if (!$result) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy sản phẩm nào phù hợp.',
            ]);
        }

        $stock_quantity = $result->stock;

        return response()->json([
            'success' => true,
            'data' => [
                'variant_id' => $result->id,
                'product_name' => $result->product->productName ?? 'Không xác định',
                'size' => $result->size,
                'price' => $result->Price,
                'stock_quantity' => $stock_quantity,
            ],
        ]);
    }

    public function approveReturn($id)
    {
        Log::info('Bắt đầu xử lý yêu cầu đổi trả.', ['claim_id' => $id]);

        $claim = Claims::with('products')->find($id);

        if (!$claim) {
            Log::error('Không tìm thấy yêu cầu đổi trả.', ['claim_id' => $id]);
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy yêu cầu đổi trả.',
            ]);
        }

        Log::info('Tìm thấy yêu cầu đổi trả.', ['claim' => $claim]);

        foreach ($claim->products as $product) {
            $variant = ProductVariation::find($product->old_product_variant_id);

            if (!$variant) {
                Log::error('Không tìm thấy sản phẩm.', ['product_variant_id' => $product->old_product_variant_id]);
                return response()->json([
                    'success' => false,
                    'message' => "Không tìm thấy sản phẩm: {$product->old_product_variant_id}.",
                ]);
            }

            if ($variant->stock < $product->quantity) {
                Log::error('Sản phẩm không đủ số lượng tồn.', ['product_variant_id' => $variant->id, 'stock' => $variant->stock, 'required_quantity' => $product->quantity]);
                return response()->json([
                    'success' => false,
                    'message' => "Sản phẩm: {$variant->id} không đủ số lượng tồn.",
                ]);
            }

            Log::info('Sản phẩm đủ số lượng tồn.', ['product_variant_id' => $variant->id, 'stock' => $variant->stock]);
        }


        $invoice = Invoice::create([
            'invoice_id' => 1,
            'ID_Employeer' => auth()->user()->id ?? 1,
            'ID_User' => $claim->user_id,
            'totalAmount' => 0,
            'discountAmount' => 0,
            'finalAmount' => 0,
            'paymentStatus' => 'Chưa thanh toán',
            'orderStatus' => 'Đang xử lý',
            'note' => 'Đổi trả',
            'receivedDate' => now()->addDays(7),
            'shippingAddress' => 'Địa chỉ mặc định',
            'phoneNumber' => '0123456789',
            'customerName' => 'Nguyễn Văn A',
        ]);
        Log::info('Hóa đơn mới được tạo.', ['invoice' => $invoice]);

        $lastInvoice = DB::table('invoice')
            ->where('ID_User', $claim->user_id)
            ->orderByDesc('createdAt')
            ->first();
        if ($lastInvoice) {
            Log::info('Hóa đơn cuối cùng của người dùng: ', ['invoice_id' => $lastInvoice->invoice_id]);
        } else {
            Log::info('Không tìm thấy hóa đơn trước đó cho người dùng.');

            $lastInvoice = (object) ['invoice_id' => 'INVOICE-' . uniqid()];
            Log::info('Tạo hóa đơn mới với invoice_id: ' . $lastInvoice->invoice_id);
        }


        $shippingFee = 30000;

        foreach ($claim->products as $product) {
            $variant = ProductVariation::find($product->old_product_variant_id);

            $variant->stock -= $product->quantity;
            $variant->save();

            Log::info('Cập nhật số lượng tồn kho sản phẩm.', ['product_variant_id' => $variant->id, 'new_stock' => $variant->stock]);

            Log::info('Hóa đơn cuối cùng của người dùng: ', ['invoice_id' => $lastInvoice->invoice_id]);

            $invoiceDetail = InvoiceDetail::create([
                'ID_Invoice' => $lastInvoice->invoice_id,
                'ID_productVariation' => $product->old_product_variant_id,
                'UnitPrice' => $shippingFee,
                'Amount' => $shippingFee,
                'Quantity' => $product->quantity,
            ]);

            Log::info('Chi tiết hóa đơn được tạo.', ['invoice_detail' => $invoiceDetail]);
        }

        $invoice = Invoice::where('invoice_id', $lastInvoice->invoice_id)
            ->first();
        $invoice->update([
            'totalAmount' => $shippingFee,
            'finalAmount' => $shippingFee,
        ]);


        // Cập nhật trạng thái yêu cầu đổi trả
        $claim->update(['status' => 'approved']);


        return response()->json([
            'success' => true,
            'message' => 'Yêu cầu đổi trả đã được duyệt và hóa đơn mới đã được tạo với phí ship 30k.',
            'invoice_id' => $lastInvoice->invoice_id,
        ]);
    }
}
