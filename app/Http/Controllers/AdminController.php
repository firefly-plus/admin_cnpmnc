<?php

namespace App\Http\Controllers;

use App\Models\employees;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    //
    public function register(Request $request)
    {
        
        $request->validate([
            'FullName' => 'required|string|max:255',
            'Phone' => 'required|string|max:15|unique:employees',
            'Password' => 'required|string|min:1',  
        ]);

       
        $hashedPassword = Hash::make($request->Password);

      
        $employee = employees::create([
            'FullName' => $request->FullName,
            'Phone' => $request->Phone,
            'Passwords' => $hashedPassword,  
            'address' => $request->address ?? null,
            'isDelete' => false,  
        ]);

        return response()->json([
            'message' => 'Đăng ký thành công!',
            'employee' => $employee,
        ], 201);  
    }

    public function login(Request $request)
    {
        $request->validate([
            'Phone' => 'required|string|max:15',
            'Password' => 'required|string|min:1',
        ]);
        $employee = employees::where('Phone', $request->Phone)->first();

        if ($employee && Hash::check($request->Password, $employee->Passwords)) 
        {
            session(['employee_id' => $employee->id]); 
            session(['employee_name' => $employee->FullName]); 
            return redirect()->url('/login');
        } else {
            return back()->withErrors(['message' => 'Thông tin đăng nhập không chính xác.']);  
        }
    }
}
