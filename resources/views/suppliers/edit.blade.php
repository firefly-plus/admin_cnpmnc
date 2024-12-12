<head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

@extends('layout.index')

@section('title', 'Sửa nhà cung cấp')

@section('css')
    <style>
        .form-label {
            font-weight: bold;
        }

        .form-control {
            padding-left: 30px; /* Dành không gian cho biểu tượng */
        }

        .form-group {
            position: relative;
        }

        .form-control-icon {
            position: absolute;
            left: 10px;
            top: 50%;
            transform: translateY(-50%);
            color: #007bff;
        }

        .btn-secondary {
            margin-left: 10px;
        }

        .container {
            max-width: 600px;
            margin-top: 30px;
        }

        h1 {
            margin-bottom: 20px;
            font-size: 28px;
            font-weight: bold;
            text-align: center;
        }

        .mb-3 {
            margin-bottom: 1.5rem;
        }

        .input-group {
            position: relative;
        }

        .form-control-icon {
            color: #007bff;
            font-size: 18px;
        }

        .btn-primary {
            padding: 10px 20px;
            font-size: 16px;
            background-color: #007bff;
            border-color: #007bff;
            transition: background-color 0.3s ease;
        }

        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #0056b3;
        }

        .btn-secondary {
            padding: 10px 20px;
            font-size: 16px;
            background-color: #6c757d;
            border-color: #6c757d;
            transition: background-color 0.3s ease;
        }

        .btn-secondary:hover {
            background-color: #5a6268;
            border-color: #5a6268;
        }

    </style>
@endsection

@section('content')
<div class="container">
    <h1>Chỉnh sửa nhà cung cấp</h1>

    <form action="{{ route('suppliers.update', $supplier->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3 form-group">
            <label for="SupplierName" class="form-label">Tên nhà cung cấp</label>
            <div class="input-group">
                <i class="fas fa-store form-control-icon"></i>
                <input type="text" name="SupplierName" class="form-control" id="SupplierName" value="{{ $supplier->SupplierName }}" required>
            </div>
        </div>

        <div class="mb-3 form-group">
            <label for="address" class="form-label">Địa chỉ</label>
            <div class="input-group">
                <i class="fas fa-map-marker-alt form-control-icon"></i>
                <input type="text" name="address" class="form-control" id="address" value="{{ $supplier->address }}">
            </div>
        </div>

        <div class="mb-3 form-group">
            <label for="phoneNumber" class="form-label">Điện thoại</label>
            <div class="input-group">
                <i class="fas fa-phone-alt form-control-icon"></i>
                <input type="text" name="phoneNumber" class="form-control" id="phoneNumber" value="{{ $supplier->phoneNumber }}">
            </div>
        </div>

        <div class="mb-3 form-group">
            <label for="Email" class="form-label">Email</label>
            <div class="input-group">
                <i class="fas fa-envelope form-control-icon"></i>
                <input type="email" name="Email" class="form-control" id="Email" value="{{ $supplier->Email }}">
            </div>
        </div>

        <div class="mb-3 form-group">
            <label for="contactPerson" class="form-label">Người liên hệ</label>
            <div class="input-group">
                <i class="fas fa-user form-control-icon"></i>
                <input type="text" name="contactPerson" class="form-control" id="contactPerson" value="{{ $supplier->contactPerson }}">
            </div>
        </div>

        <button type="submit" class="btn btn-primary"><i class="fa-solid fa-pen"></i></button>
        <a href="{{ route('suppliers.index') }}" class="btn btn-secondary"><i class="fa-solid fa-arrow-left"></i></a>
    </form>
</div>
@endsection
