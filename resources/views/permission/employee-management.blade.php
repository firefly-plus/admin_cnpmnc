@extends('layout.index')

@section('title', 'Category Management')

@section('css')
<style>
    table {
        width: 100%;
        border-collapse: collapse;
    }

    th, td {
        padding: 10px;
        text-align: left;
        border: 1px solid #ddd;
    }

    th {
        background-color: #f4f4f4;
    }

    tr:nth-child(even) {
        background-color: #f9f9f9;
    }

    tr:hover {
        background-color: #f1f1f1;
    }
</style>
@endsection

@section('content')
<div class="container">
    
    <h1>Thêm Nhân Viên</h1>

    <!-- Form thêm nhân viên -->
    <form action="/themnhanvien" method="POST">
        @csrf

        <div class="form-group">
            <label for="FullName">Họ và tên</label>
            <input type="text" id="FullName" name="FullName" value="{{ old('FullName') }}" required>
            @error('FullName')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="Phone">Số điện thoại</label>
            <input type="text" id="Phone" name="Phone" value="{{ old('Phone') }}" required>
            @error('Phone')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="Passwords">Mật khẩu</label>
            <input type="password" id="Passwords" name="Passwords" required>
            @error('Passwords')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="address">Địa chỉ</label>
            <input type="text" id="address" name="address" value="{{ old('address') }}">
            @error('address')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="isDelete">Trạng thái</label>
            <select id="isDelete" name="isDelete">
                <option value="0">Hoạt động</option>
                <option value="1">Đã xóa</option>
            </select>
        </div>

        <div class="form-group">
            <button type="submit">Thêm Nhân Viên</button>
        </div>
    </form>
   
    <h1>Danh sách nhân viên</h1>
    
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Họ tên</th>
                <th>Số điện thoại</th>
                <th>Địa chỉ</th>
                <th>Trạng thái</th>
                <th>Ngày tạo</th>
                <th>Ngày cập nhật</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            @foreach($employees as $employee)
                <tr>
                    <td>{{ $employee->id }}</td>
                    <td>{{ $employee->FullName }}</td>
                    <td>{{ $employee->Phone }}</td>
                    <td>{{ $employee->address }}</td>
                    <td>{{ $employee->isDelete ? 'Đã xóa' : 'Hoạt động' }}</td>
                    <td>{{ $employee->createdAt }}</td>
                    <td>{{ $employee->updatedAt }}</td>
                    <td>
                        <a href="">Sửa</a> |
                        <a href="" onclick="return confirm('Bạn có chắc chắn muốn xóa nhân viên này?')">Xóa</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection

@section('js')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
@endsection
