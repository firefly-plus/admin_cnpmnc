<head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

@extends('layout.index')

@section('title', 'Thêm nhà cung cấp')
@section('css')
<style>
    .container {
        max-width: 600px;
        margin-top: 30px;
    }

    h1 {
        margin-bottom: 20px;
        font-size: 24px;
        font-weight: bold;
    }

    .form-label {
        font-weight: bold;
    }

    .form-control {
        border-radius: 5px;
        padding: 10px;
        font-size: 16px;
        transition: border-color 0.3s;
    }

    .form-control:focus {
        border-color: #007bff;
        box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
    }

    button {
        width: 100%;
        padding: 10px;
        font-size: 16px;
        margin-top: 15px;
    }

    button:hover {
        background-color: #0056b3;
    }

    a.btn-secondary {
        width: 100%;
        padding: 10px;
        font-size: 16px;
        margin-top: 10px;
        text-align: center;
        display: block;
    }

    a.btn-secondary:hover {
        background-color: #6c757d;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .container {
            max-width: 90%;
        }
        button, a.btn-secondary {
            width: 100%;
        }
    }

</style>
@endsection

@section('content')
<div class="container">
    <h1>Thêm nhà cung cấp mới</h1>

    <form action="{{ route('suppliers.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label for="SupplierName" class="form-label">Tên nhà cung cấp</label>
            <div class="input-group">
                <span class="input-group-text"><i class="fas fa-building"></i></span>
                <input type="text" name="SupplierName" class="form-control" id="SupplierName" required>
            </div>
            @error('SupplierName')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="address" class="form-label">Địa chỉ</label>
            <div class="input-group">
                <span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>
                <input type="text" name="address" class="form-control" id="address">
            </div>
            @error('address')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="phoneNumber" class="form-label">Điện thoại</label>
            <div class="input-group">
                <span class="input-group-text"><i class="fas fa-phone-alt"></i></span>
                <input type="text" name="phoneNumber" class="form-control" id="phoneNumber">
            </div>
            @error('phoneNumber')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="Email" class="form-label">Email</label>
            <div class="input-group">
                <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                <input type="email" name="Email" class="form-control" id="Email">
            </div>
            @error('Email')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="contactPerson" class="form-label">Người liên hệ</label>
            <div class="input-group">
                <span class="input-group-text"><i class="fas fa-user"></i></span>
                <input type="text" name="contactPerson" class="form-control" id="contactPerson">
            </div>
            @error('contactPerson')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary"><i class="fa-solid fa-floppy-disk"></i></button>
        <a href="{{ route('suppliers.index') }}" class="btn btn-secondary">Hủy</a>
    </form>
</div>
@endsection
