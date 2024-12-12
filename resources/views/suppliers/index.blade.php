<head>
    <!-- Thêm Font Awesome qua CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

@extends('layout.index')

@section('title', 'Quản lý nhà cung cấp')

@section('css')
    <style>
        .table-container {
            max-height: 400px;
            overflow-y: auto;
        }

        .table th,
        .table td {
            vertical-align: middle;
        }

        .content {
            width: 100%;
        }

        .modal-dialog {
            max-width: 80%;
        }

        .btn {
            padding: 8px 12px;
            font-size: 14px;
            border-radius: 5px;
            display: inline-flex;
            align-items: center;
        }

        .btn i {
            margin-right: 5px;
        }

        .btn-sm {
            padding: 5px 10px;
        }

        .btn-info {
            background-color: #17a2b8;
            color: white;
        }

        .btn-warning {
            background-color: #ffc107;
            color: white;
        }

        .btn-danger {
            background-color: #dc3545;
            color: white;
        }

        .btn:hover {
            opacity: 0.8;
        }

        .btn-danger {
            border: none;
        }

        .btn-danger:hover {
            opacity: 0.9;
        }
        .actions {
            display: flex;
            justify-content: space-between;
            gap: 10px; 
        }

        .actions .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 8px 12px; 
            font-size: 14px;
            border-radius: 5px;
            width: auto; 
            height: 36px;
        }

        .actions .btn i {
            margin-right: 5px; 
        }


    </style>
@endsection

@section('content')
<div class="container">
    <h1>Danh sách nhà cung cấp</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <a href="{{ route('suppliers.create') }}" class="btn btn-primary mb-3">
        <i class="fas fa-plus"></i>
    </a>

    <div class="table-container">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Mã nhà cung cấp</th>
                    <th>Tên nhà cung cấp</th>
                    <th>Địa chỉ</th>
                    <th>Số điện thoại</th>
                    <th>Email</th>
                    <th>Người liên hệ</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($suppliers as $supplier)
                    <tr>
                        <td>{{ $supplier->id }}</td>
                        <td>{{ $supplier->SupplierName }}</td>
                        <td>{{ $supplier->address }}</td>
                        <td>{{ $supplier->phoneNumber }}</td>
                        <td>{{ $supplier->Email }}</td>
                        <td>{{ $supplier->contactPerson }}</td>
                        <td class="actions">
                            <a href="{{ route('suppliers.show', $supplier->id) }}" class="btn btn-info btn-sm">
                                <i class="fas fa-eye"></i> 
                            </a>
                            <a href="{{ route('suppliers.edit', $supplier->id) }}" class="btn btn-warning btn-sm">
                                <i class="fas fa-edit"></i> 
                            </a>
                            <form action="{{ route('suppliers.destroy', $supplier->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">
                                    <i class="fas fa-trash-alt"></i> 
                                </button>
                            </form>
                        </td>                       
                    </tr>
                @endforeach           

            </tbody>
        </table>
    </div>
</div>
@endsection
