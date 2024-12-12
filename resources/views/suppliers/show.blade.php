<head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

@extends('layout.index')

@section('title', 'Chi tiết nhà cung cấp')

@section('css')
<style>
    /* CSS Custom Styles */
    .container {
        max-width: 600px;
        margin-top: 30px;
    }

    h1 {
        margin-bottom: 20px;
        font-size: 24px;
        font-weight: bold;
    }

    .card {
        border: 1px solid #ddd;
        border-radius: 8px;
        padding: 20px;
    }

    .card-body {
        padding: 20px;
    }

    .card-title {
        font-size: 22px;
        font-weight: bold;
    }

    .card-text {
        font-size: 16px;
        margin-bottom: 10px;
    }

    .card-text strong {
        color: #333;
    }

    .card-text i {
        color: #007bff;
        margin-right: 8px;
    }

    .btn-secondary {
        padding: 10px 20px;
        font-size: 16px;
        border-radius: 5px;
        background-color: #6c757d;
        color: white;
    }

    .btn-secondary:hover {
        background-color: #5a6268;
    }

    .card-header {
        font-size: 20px;
        font-weight: bold;
        color: #333;
        background-color: #f8f9fa;
        padding: 10px;
    }

    .card-footer {
        text-align: right;
        padding-top: 15px;
    }
</style>
@endsection

@section('content')
<div class="container">
    <h1>Chi tiết nhà cung cấp</h1>

    <div class="card">
        <div class="card-header">
            Thông tin chi tiết nhà cung cấp
        </div>
        <div class="card-body">
            <h5 class="card-title">{{ $supplier->SupplierName }}</h5>
            <p class="card-text">
                <i class="fas fa-map-marker-alt"></i><strong>Địa chỉ:</strong> {{ $supplier->address }}
            </p>
            <p class="card-text">
                <i class="fas fa-phone-alt"></i><strong>Điện thoại:</strong> {{ $supplier->phoneNumber }}
            </p>
            <p class="card-text">
                <i class="fas fa-envelope"></i><strong>Email:</strong> {{ $supplier->Email }}
            </p>
            <p class="card-text">
                <i class="fas fa-user"></i><strong>Người liên hệ:</strong> {{ $supplier->contactPerson }}
            </p>
        </div>
        <div class="card-footer">
            <a href="{{ route('suppliers.index') }}" class="btn btn-secondary mt-3"><i class="fa-solid fa-arrow-left"></i></a>
        </div>
    </div>
</div>
@endsection
