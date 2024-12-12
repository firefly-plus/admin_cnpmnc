@extends('layout.index')

@section('title', 'Thêm Nhân Viên')

@section('css')
<style>
    .form-group {
        margin-bottom: 15px;
    }

    .form-group label {
        display: block;
        font-weight: bold;
    }

    .form-group input {
        width: 100%;
        padding: 8px;
        border: 1px solid #ddd;
        border-radius: 4px;
    }

    .form-group button {
        padding: 10px 15px;
        background-color: #007bff;
        color: white;
        border: none;
        border-radius: 4px;
    }

    .form-group button:hover {
        background-color: #0056b3;
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
</div>
@endsection

@section('js')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
@endsection
