<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Voucher;

class VoucherController extends Controller
{
    //
    public function addVoucher(Request $request)
    {
        // Tạo mới voucher
        $voucher = new Voucher();
        $voucher->voucherCode = $request->voucherCode;
        $voucher->description = $request->description;
        $voucher->discountValue = $request->discountValue;
        $voucher->minOrderValue = $request->minOrderValue;
        $voucher->max_discount_amount = $request->maxDiscountAmount;
        $voucher->maxUses = $request->maxUses;
        $voucher->usedCount = 0;
        $voucher->startDate = $request->startDate;
        $voucher->endDate = $request->endDate;
        $voucher->isActive = 1;


        $voucher->save();

        return response()->json(['message' => 'Voucher đã được thêm thành công!'], 201);
    }

    public function getVoucherById($id)
    {
        $voucher = Voucher::find($id);
        if (!$voucher) {
            return response()->json(['error' => 'Voucher không tồn tại'], 404);
        }
        return response()->json($voucher);
    }

    public function editVoucher(Request $request, $id)
    {
        $voucher = Voucher::find($id);

        if (!$voucher) {
            return response()->json(['message' => 'Voucher không tồn tại!'], 404);
        }

        $voucher->voucherCode = $request->voucherCode ?? $voucher->voucherCode;
        $voucher->description = $request->description ?? $voucher->description;
        $voucher->discountValue = $request->discountValue ?? $voucher->discountValue;
        $voucher->minOrderValue = $request->minOrderValue ?? $voucher->minOrderValue;
        $voucher->max_discount_amount = $request->maxDiscountAmount ?? $voucher->max_discount_amount;
        $voucher->maxUses = $request->maxUses ?? $voucher->maxUses;
        $voucher->startDate = $request->startDate ?? $voucher->startDate;
        $voucher->endDate = $request->endDate ?? $voucher->endDate;
        $voucher->isActive = $request->has('isActive') ? $request->isActive : $voucher->isActive;
        $voucher->save();
        return response()->json(['message' => 'Voucher đã được cập nhật thành công!'], 200);
    }

    public function deleteVoucher($id)
{
    $voucher = Voucher::find($id);
    if (!$voucher) {
        return response()->json(['error' => 'Voucher không tồn tại'], 404);
    }
    $voucher->delete();
    return response()->json(['message' => 'Voucher đã được xóa thành công!'], 200);
}

}
