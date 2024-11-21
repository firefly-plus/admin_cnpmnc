<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Discount;
use App\Models\Employee;
use App\Models\Invoice;
use App\Models\InvoiceDetail;
use App\Models\Product;
use App\Models\ProductVariation;
use App\Models\SupCategory;
use App\Models\User;
use App\Models\VariationDiscount;
use App\Models\Voucher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Hash;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
function removeAccents($str) {
    $accents = array(
        'à' => 'a', 'á' => 'a', 'ả' => 'a', 'ã' => 'a', 'ạ' => 'a', 'ă' => 'a', 'ằ' => 'a', 'ắ' => 'a', 'ẳ' => 'a', 'ẵ' => 'a', 'ặ' => 'a',
        'â' => 'a', 'ầ' => 'a', 'ấ' => 'a', 'ẩ' => 'a', 'ẫ' => 'a', 'ậ' => 'a', 'è' => 'e', 'é' => 'e', 'ẻ' => 'e', 'ẽ' => 'e', 'ẹ' => 'e',
        'ê' => 'e', 'ề' => 'e', 'ế' => 'e', 'ể' => 'e', 'ễ' => 'e', 'ệ' => 'e', 'ì' => 'i', 'í' => 'i', 'ỉ' => 'i', 'ĩ' => 'i', 'ị' => 'i',
        'ò' => 'o', 'ó' => 'o', 'ỏ' => 'o', 'õ' => 'o', 'ọ' => 'o', 'ô' => 'o', 'ồ' => 'o', 'ố' => 'o', 'ổ' => 'o', 'ỗ' => 'o', 'ộ' => 'o',
        'ơ' => 'o', 'ờ' => 'o', 'ớ' => 'o', 'ở' => 'o', 'ỡ' => 'o', 'ợ' => 'o', 'ù' => 'u', 'ú' => 'u', 'ủ' => 'u', 'ũ' => 'u', 'ụ' => 'u',
        'ư' => 'u', 'ừ' => 'u', 'ứ' => 'u', 'ử' => 'u', 'ữ' => 'u', 'ự' => 'u', 'ỳ' => 'y', 'ý' => 'y', 'ỷ' => 'y', 'ỹ' => 'y', 'ỵ' => 'y',
        'đ' => 'd', 'Đ' => 'd'
    );
    $str = strtr($str, $accents);
    $str = preg_replace('/[^a-zA-Z\s]/', '', $str);
    $str = preg_replace('/\s+/', ' ', $str);
    $str = trim($str);

    return $str;
} 

class AdminController extends Controller
{
    //
    public function showLogin()
    {
        return view('sign-in');
    }

    public function showProductManagement() 
    {
        return view('product.product-management');
    }
        

    public function login(Request $request)
    {
        $request->validate([
            'Phone' => 'required|digits:10',
            'Password' => 'required|string|min:1',
        ]);
        $employee = Employee::where('Phone', $request->Phone)->first();

        if ($request->has('rememberMe') && $request->rememberMe == 'on') {
            Cookie::queue('employee_id', $employee->id, 60 * 24 * 30); 
            Cookie::queue('employee_name', $employee->FullName, 60 * 24 * 30);
        }
        if ($employee && Hash::check($request->Password, $employee->Passwords)) 
        {
            session(['employee_id' => $employee->id]); 
            session(['employee_name' => $employee->FullName]); 
            return redirect()->to('/statistics.html');
        } else {
            return back()->withErrors(['error' => 'Thông tin đăng nhập không chính xác.']);  
        }
    }

    public function showInvoice() 
    {
        return view('invoice.invoice-management');
    }

    public function Invoice(Request $request)
    {
        $filteredInvoices = [];
        if ($request->orderStatus) {
            $invoices = Invoice::all();
            $orderStatusWithoutAccents = removeAccents($request->orderStatus);  
            foreach ($invoices as $invoice) {
                $orderStatusFromDB = removeAccents($invoice->orderStatus);
                if (stripos($orderStatusFromDB, $orderStatusWithoutAccents) !== false) {
                    $filteredInvoices[] = $invoice;
                }
            }
        } else {
            $filteredInvoices = Invoice::getAllInvoices($request);
        }
        return response()->json($filteredInvoices);
    }
    
    public function updateOrderStatus(Request $request)
    {
        if ($request->invoice_id && $request->orderStatus) {
            $updated = DB::table('invoice')
                ->where('invoice_id', $request->invoice_id)
                ->update(['orderStatus' => $request->orderStatus]);
            if ($updated === 0) {
                return response()->json(['error' => 'Invoice not found'], 404);
            }

            return response()->json(['success' => 'Order status updated successfully']);
        }

        return response()->json(['error' => 'Missing required parameters'], 400);
    }

    

    

    public function exportPdf(Request $request)
    {
        try 
        {
            $selectedInvoices = $request->input('invoices'); 
    
            if (is_string($selectedInvoices)) {
                $selectedInvoices = explode(',', $selectedInvoices);
            }
        
            if (empty($selectedInvoices)) {
                return redirect()->back()->with('error', 'Vui lòng chọn ít nhất một hóa đơn!');
            }

            $invoices = Invoice::with('invoiceDetails.productVariation.product')
                                ->whereIn('invoice_id', $selectedInvoices)
                                ->get();
            
            Log::debug('Retrieved invoices: ', $invoices->toArray());
            
           
            $pdfPath = public_path('pdf/hoadon.pdf');
            $pdf = Pdf::loadView('invoice.pdf', compact('invoices'));
            $pdf->save($pdfPath);
        
            
            return response()->json(['pdfPath' => asset('pdf/hoadon.pdf')], 200);
        } catch (\Exception $e) {
            Log::error('Lỗi xuất PDF: ' . $e->getMessage());
            return response()->json(['error' => 'Có lỗi xảy ra khi xuất PDF'], 500);
        }
    }

    public function Statistics(Request $request)
    {
        $now = Carbon::now('Asia/Ho_Chi_Minh')->endOfDay();
        Log::info('Current DateTime (endOfDay): ', ['now' => $now]);

        $year = Carbon::now('Asia/Ho_Chi_Minh')->subDays(365)->startOfYear()->toDateString();
        Log::info('Start of Year (365 days ago): ', ['year' => $year]);

        $start_of_month = Carbon::now('Asia/Ho_Chi_Minh')->startOfMonth();
        Log::info('Start of Current Month: ', ['start_of_month' => $start_of_month]);

        $total_year = Invoice::whereBetween('createdAt', [$year, $now])
            ->where('orderStatus', "Đã hoàn thành")
            ->get();
        Log::info('Total invoices in the last year: ', ['total_year' => $total_year->count()]);

        $invoicesDay = Invoice::whereDate('createdAt', Carbon::today())
            ->where('orderStatus', "Đã hoàn thành")
            ->get();
        Log::info('Invoices for today: ', ['invoicesDay' => $invoicesDay->count()]);

        $userCount = User::count();
        Log::info('Total users count: ', ['userCount' => $userCount]);

        Log::info('Danh sách người dùng:', ['users' => User::all()]);

        $movies = Product::all();
        Log::info('Total products count: ', ['movies' => $movies->count()]);

        // Tính tổng doanh thu hôm nay
        $sum_today = 0;
        foreach ($invoicesDay as $invoice) {
            $sum_today += $invoice->totalAmount;
        }
        Log::info('Total revenue for today: ', ['sum_today' => $sum_today]);

        $sum = 0;
        foreach ($total_year as $invoice) {
            $sum += $invoice->totalAmount;
        }
        Log::info('Total revenue for the last year: ', ['sum' => $sum]);

        $sum_iv_today = 0;
        foreach ($invoicesDay as $invoice) {
            
                $sum_iv_today += 1;
            
        }
        Log::info('Total ticket seats sold today: ', ['sum_iv_today' => $sum_iv_today]);

        // Doanh thu theo danh mục
        $revenueByCategory = [];
        $subCates = SupCategory::all();
        $proDucts = Product::all();
        $proDuct_Vas = ProductVariation::all();
        $Invoices_Des = InvoiceDetail::all();
        foreach ($subCates as $subCate) {
            $sumByCate = 0;

            Log::info('Processing subcategory: ', ['subCate' => $subCate->SupCategoryName]);

            foreach ($proDucts as $proDuct) {
                if ($subCate->id == $proDuct->ID_SupCategory) {
                    foreach ($proDuct_Vas as $proDuct_Va) {
                        if ($proDuct->id == $proDuct_Va->ID_Product) {
                            foreach ($Invoices_Des as $Invoices_De) {
                            //    dd($Invoices_De->toArray());

                                if ($proDuct_Va->id == $Invoices_De->ID_productVariation) {
                                    Log::info('Invoice Detail:', ['product_variation_id' => $proDuct_Va->id, 'amount' => $Invoices_De->Amount]);
                                    
                                    if ($Invoices_De->invoice->orderStatus == 'Đã hoàn thành') {
                                        
                                        $sumByCate += $Invoices_De->Amount;
                                    }
                                }
                            }
                        }
                    }
                }
            }

            $revenueByCategory[] = [
                'category' => $subCate->SupCategoryName,
                'revenue' => $sumByCate,
            ];
        

            Log::info('Total revenue for subcategory: ', ['sumByCate' => $sumByCate]);
        }
        
        $revenueByProduct = [];

        foreach ($proDuct_Vas as $product) {
            $sumByPro = 0;
            
            Log::info('Processing product variation:', ['product' => $product->productName]);
        
            foreach($Invoices_Des as $Invoices_De) {
            
                Log::info('Checking invoice detail:', [
                    'product_variation_id' => $product->id,
                    'invoice_detail_product_variation_id' => $Invoices_De->ID_productVariation,
                    'invoice_status' => $Invoices_De->invoice->orderStatus,
                ]);
        
                if($product->id == $Invoices_De->ID_productVariation) {
                    if ($Invoices_De->invoice->orderStatus == 'Đã hoàn thành') {
                        $sumByPro += $Invoices_De->Amount;
                    }
                }
            }
        
            $revenueByProduct[] = [
                'product' => $product->product->productName.' Size: '.$product->size, 
                'revenue' => $sumByPro,  
            ];
        
            Log::info('Revenue for product:', ['product' => $product->productName, 'revenue' => $sumByPro]);
        }
        $revenueByCategory = collect($revenueByCategory); //
        $revenueByProduct = collect($revenueByProduct);   
        return view('statistics.index', compact(
            'userCount',
            'sum_today',
            'sum',
            'sum_iv_today',
            'now',
            'year',
            'revenueByCategory', 
            'revenueByProduct' 
        ));
    }

    public function getRevenueData(Request $request)
    {
        $statistical = $request->statistical;
        
        $start_time = $request->start_time ? Carbon::parse($request->start_time) : Carbon::now();
        $end_time = $request->end_time ? Carbon::parse($request->end_time) : Carbon::now();
    
        if ($statistical == 'week') {
            $start_time = Carbon::now()->startOfWeek(); 
            $end_time = Carbon::now()->endOfWeek(); 
        } elseif ($statistical == 'last_week') {
           
            $start_time = Carbon::now()->subWeek()->startOfWeek();
            $end_time = Carbon::now()->subWeek()->endOfWeek();
        } elseif ($statistical == 'this_month') {
            // Lọc theo tháng này
            $start_time = Carbon::now()->startOfMonth();
            $end_time = Carbon::now()->endOfMonth();
        } elseif ($statistical == 'last_month') {
          
            $start_time = Carbon::now()->subMonth()->startOfMonth();
            $end_time = Carbon::now()->subMonth()->endOfMonth();
        } elseif ($statistical == 'year') {
            
            $start_time = Carbon::now()->startOfYear();
            $end_time = Carbon::now()->endOfYear();
        } elseif ($statistical == 'last_year') {
           
            $start_time = Carbon::now()->subYear()->startOfYear();
            $end_time = Carbon::now()->subYear()->endOfYear();
        } elseif ($statistical == 'all_time') {
           
            $start_time = Carbon::createFromDate(2000, 1, 1); 
            $end_time = Carbon::now(); 
        }
    
      
        $revenue_per_day = Invoice::whereBetween('createdAt', [$start_time, $end_time])
                                  ->where('orderStatus', 'Đã hoàn thành')
                                  ->selectRaw('DATE(createdAt) as date, SUM(totalAmount) as revenue')
                                  ->groupBy('date')
                                  ->orderBy('date')
                                  ->get();
    
       
        $labels = $revenue_per_day->pluck('date');
        $revenues = $revenue_per_day->pluck('revenue');
        
      
        return response()->json([
            'labels' => $labels,
            'revenues' => $revenues,
        ]);
    }
    
    
    //Promotion
    public function showPromotion()
    {
        return view('promotion.promotion-management');
    }

    public function getDiscount()
    {
        $discount=Discount::all();
        return response()->json($discount);
    }

    public function getProductVariationByCate(Request $request)
    {
        $productVariations = ProductVariation::with('product', 'product.productImages')
            ->whereHas('product.subCategory.category', function ($query) use ($request) {
                $query->where('id', $request->id);
            })
            ->get();
        $discounts = VariationDiscount::all();
    
        $filteredProductVariations = [];
        foreach ($productVariations as $productVariation) {
            $hasDiscount = false;
            foreach ($discounts as $discount) {
                if ($discount->ID_ProductVariation == $productVariation->id) {
                    $hasDiscount = true;
                    break; 
                }
            }
            if (!$hasDiscount) {
                $filteredProductVariations[] = $productVariation;
            }
        }
    
        return response()->json($filteredProductVariations);
    }
    
    public function getProductVariationBySubCate(Request $request)
    {
        $productVariations = ProductVariation::with('product', 'product.productImages')
            ->whereHas('product.subCategory', function ($query) use ($request) {
                $query->where('id', $request->id);
            })
            ->get();
        $discounts = VariationDiscount::all();
        $filteredProductVariations = [];
        foreach ($productVariations as $productVariation) {
            $hasDiscount = false;
            foreach ($discounts as $discount) {
                if ($discount->ID_ProductVariation == $productVariation->id) {
                    $hasDiscount = true;
                    break; 
                }
            }
            if (!$hasDiscount) {
                $filteredProductVariations[] = $productVariation;
            }
        }
    
        return response()->json($filteredProductVariations);
    }
    
    public function getProductVariationByProduct(Request $request)
    {
        $productVariations = ProductVariation::with('product', 'product.productImages')
            ->where('ID_Product', $request->id)
            ->get();
    
        $discounts = VariationDiscount::all();
        $filteredProductVariations = [];
        foreach ($productVariations as $productVariation) {
            $hasDiscount = false;
            foreach ($discounts as $discount) {
                if ($discount->ID_ProductVariation == $productVariation->id) {
                    $hasDiscount = true;
                    break; 
                }
            }
            if (!$hasDiscount) {
                $filteredProductVariations[] = $productVariation;
            }
        }
    
        return response()->json($filteredProductVariations);
    }
    

    public function getCategory()
    {
        $category=Category::all();
        return response()->json($category);
    }

    public function getSubCategory()
    {
        $subCategory=SupCategory::all();
        return response()->json($subCategory);
    }

    public function getProduct()
    {
        $product=Product::all();
        return response()->json($product);
    }

    public function getProductVariationDiscount(Request $request)
    {
        $productVariations = ProductVariation::with('product', 'product.productImages','variationdiscount.discount')->get();
        $variationDiscounts = VariationDiscount::all();
        $filteredProductVariations = [];
        foreach ($productVariations as $productVariation) {
            foreach ($variationDiscounts as $variationDiscount) {
                if ($variationDiscount->ID_Variation == $productVariation->id) {
                    $filteredProductVariations[] = $productVariation;
                    break; 
                }
            }
        }
        return response()->json($filteredProductVariations);
    }

    public function deleteDiscountByProductVariation(Request $request)
    {
        Log::info('ID received: ' . $request->id);
        $variationDiscount = VariationDiscount::where('ID_Variation', $request->id)->first();
        if (!$variationDiscount) {
            return response()->json(['message' => 'Không tìm thấy khuyến mãi cho sản phẩm này.'], 404);
        }
        $variationDiscount->delete();
        return response()->json(['message' => 'Khuyến mãi đã được hủy thành công.'], 200);
    }

    public function addVariationDiscount(Request $request)
    {
        $request->validate([
            'ID_Variation'=>'required|array',
        ]);

        try{
            foreach($request->ID_Variation as $id)
            {
                VariationDiscount::create([
                    'ID_Variation' => $id,
                    'ID_Discount' => $request->input('ID_Discount'),
                    'StartDate' => $request->input('StartDate'),
                    'EndDate' => $request->input('EndDate'),
                    'status' =>1,
                ]);
            }

        }catch(Exception $e)
        {
            return response()->json([
                'success'=>false,
                'message' => $e->getMessage(),
            ],422);
        }
    }

    // user management
    public function showUser()
    {
        return view('user.user-management');
    }

    public function getUser()
    {
        $users=User::all();
        return response()->json($users);
    }

    public function getUserById(Request $request)
    {
        $user = User::find($request->id);       
        return response()->json($user);     
    }

    public function getInvoiceByUser(Request $request)
    {
        $invoices=Invoice::where('ID_User',$request->id)->get();
        return response()->json($invoices);
    }

    public function updateStatusUser(Request $request)
    {
        $user = User::find($request->id);
        $user->isDelete = $user->isDelete == 0 ? 1 : 0;
        $user->save();
        return response()->json([
            'success' => true,
            'message' => 'Cập nhật trạng thái thành công.',
            'newStatus' => $user->isDelete
        ]);
    }

    public function getUserByStatus(Request $request)
    {
        $user=User::where('isDelete',$request->isDelete)->get();
        return response()->json($user);
    }

    //voucher
    public function showVoucher()
    {
        return view('voucher.voucher-management');
    }
    
    public function getVoucher()
    {
        $vouchers=Voucher::all();
        return response()->json($vouchers);
    }


    






    

    
 
    
}
