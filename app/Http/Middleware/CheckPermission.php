<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Role;  // Đảm bảo bạn đã import đúng Role
use App\Models\Employee; // Và Employee nếu cần


class CheckPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $permission)
    {
        $employee = Auth::guard('employee')->user();
        if (!$employee) {
            return redirect('/')->withErrors('Bạn cần đăng nhập để truy cập');
        }
        $emp=Employee::find($employee->id);

        $permissions = $emp->roles()
            ->with('permissions')
            ->get()
            ->pluck('permissions')
            ->flatten()
            ->pluck('name')
            ->unique();


        session()->forget('employee_permissions');
        session()->put('employee_permissions', $permissions);


        // Kiểm tra quyền có trong danh sách quyền của nhân viên
        if (!$permissions->contains($permission)) {
            return response()->json(['message' => 'Bạn không có quyền truy cập'], 403);
        }
        // dd(session('employee_permissions'));
        return $next($request);
    }
}
