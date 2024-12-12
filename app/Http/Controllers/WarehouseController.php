<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class WarehouseController extends Controller
{
    //
    public function showWareHouse()
    {
        return view('warehouse.warehouse-management');
    }

    public function showWarehouseDetail($orderSupplierId)
    {
        $response = Http::get('http://localhost:8017/v1/orderSupplier/' . $orderSupplierId);

        if ($response->successful()) {
            $order = $response->json();

            return view('warehouse.warehouse-detail', compact('order'));
        } else {
            return redirect()->route('warehouse.index')->with('error', 'Không tìm thấy đơn hàng!');
        }
    }

    public function showFormAddDonHang(){
        return view('warehouse.form-add-don-hang');
    }


}