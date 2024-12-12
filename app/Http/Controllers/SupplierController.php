<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Supplier;

class SupplierController extends Controller
{
    // Hiển thị danh sách nhà cung cấp
    public function index()
    {
        $suppliers = Supplier::all();
        return view('suppliers.index', compact('suppliers')); 
    }

    public function edit($id)
    {
        $supplier = Supplier::find($id);

        if (!$supplier || $supplier->isDelete) {
            return redirect()->route('suppliers.index')->with('error', 'Nhà cung cấp không tồn tại hoặc đã bị xóa');
        }

        return view('suppliers.edit', compact('supplier'));
    }

    public function show($id)
    {
        $supplier = Supplier::find($id);
    
        if (!$supplier || $supplier->isDelete) {
            return redirect()->route('suppliers.index')->with('error', 'Nhà cung cấp không tồn tại hoặc đã bị xóa');
        }
    
        return view('suppliers.show', compact('supplier')); 
    }
    
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'SupplierName' => 'required|string|max:255',
            'address' => 'nullable|string|max:255',
            'phoneNumber' => 'nullable|string|max:15',
            'Email' => 'nullable|email|max:255',
            'contactPerson' => 'nullable|string|max:255',
        ]);
    
        Supplier::create(array_merge($validatedData, [
            'isDelete' => 0,
        ]));
    
        return redirect()->route('suppliers.index')->with('success', 'Nhà cung cấp được tạo thành công');
    }
    public function update(Request $request, $id)
    {
        $supplier = Supplier::find($id);

        if (!$supplier || $supplier->isDelete) {
            return redirect()->route('suppliers.index')->with('error', 'Nhà cung cấp không tồn tại hoặc đã bị xóa');
        }

        $validatedData = $request->validate([
            'SupplierName' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:255',
            'phoneNumber' => 'nullable|string|max:15',
            'Email' => 'nullable|email|max:255',
            'contactPerson' => 'nullable|string|max:255',
        ]);

        $supplier->update($validatedData);

        return redirect()->route('suppliers.index')->with('success', 'Nhà cung cấp được cập nhật thành công');
    }

    public function destroy($id)
    {
        $supplier = Supplier::find($id);
    
        if (!$supplier || $supplier->isDelete) {
            return redirect()->route('suppliers.index')->with('error', 'Nhà cung cấp không tồn tại hoặc đã bị xóa');
        }
    
        $supplier->update(['isDelete' => 1]);
    
        return redirect()->route('suppliers.index')->with('success', 'Nhà cung cấp đã bị xóa mềm');
    }
}
