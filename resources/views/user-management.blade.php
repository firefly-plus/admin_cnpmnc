@extends('layout.index')
@section('title', 'Danh sách khách hàng')

@section('css')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }

        td {
            vertical-align: middle;
        }

        .wrapper {
            min-height: 100vh;
        }

        .row-search {
            margin-bottom: 20px;
        }

        .form-search {
            position: relative;
            display: flex;
            justify-content: center;
        }

        .form-search input {
            width: 90%;
            padding: 5px 30px 5px 20px;
            border-radius: 999px;
            border: 1px solid black;
            outline: none;
        }

        .fa-magnifying-glass {
            position: absolute;
            top: 50%;
            right: 7%;
            transform: translateY(-50%);
            color: #000000;
            cursor: pointer;
        }

        .table-user{
            max-height: 500px;
            overflow-y: auto; 
            width: 100%;
            border: 1px solid #ddd;
        }

        .table-user table {
            width: 100%; 
            border-collapse: collapse; 
        }

        .table-user thead th {
            position: sticky;
            top: 0; 
            background-color: #f8f9fa; 
            z-index: 1; 
            border-bottom: 1px solid #ddd; 
            padding: 8px; 
            text-align: left; 
        }

        .avata-user img {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            object-fit: cover;
        }

        .btn-preview button {
            border: none;
            border-radius: 999px;
            background-color: rgb(63, 185, 255);
            cursor: pointer;
            padding: 5px 10px;
            transition: all .1s linear;
        }

        .btn-preview button:hover {
            background-color: aqua;
            box-shadow: rgba(50, 50, 93, 0.25) 0px 2px 5px -1px, rgba(0, 0, 0, 0.3) 0px 1px 3px -1px;
        }

        .row-filter {
            display: flex;
            justify-content: space-around;
            margin-bottom: 20px;
        }

        .filter-user {
            display: flex;
            align-items: center;
        }

        .filter-user label {
            font-size: 1.2rem;
            font-weight: 500;
            margin-right: 5px;
        }

        .filter-user select {
            padding: 5px 10px;
        }

        .card-preview {
            box-shadow: rgba(0, 0, 0, 0.16) 0px 1px 4px;
            padding: 10px;
        }

        .title-preview {
            text-align: center;
            font-size: 1.2rem;
            font-weight: 500;
            border-bottom: 1px solid #6e6e6e;
        }

        .info-user {
            margin: 20px 0;
            display: flex;
            gap: 20px;
            flex-direction: column;
        }

        .left-col {
            text-align: end;
        }

        .row-input input {
            width: 100%;
            padding: 4px 10px;
        }

        .row-action {
            display: flex;
            justify-content: space-around;
        }

        .btn-viewDetail,
        .btn-blockAccount,
        .btn-DelAccount {
            border-radius: 999px;
            border: none;
            padding: 5px 10px;
            cursor: pointer;
            color: #fff;
        }

        .btn-viewDetail {
            background-color: #6DABDA;
        }

        .btn-viewDetail:hover {
            background-color: #91c7f0;
        }

        .btn-blockAccount {
            background-color: #fc7f41;
        }

        .btn-blockAccount:hover {
            background-color: #fe9561;
        }

        .btn-DelAccount {
            background-color: #FF5457;
        }

        .btn-DelAccount:hover {
            background-color: #f87477;
        }

        .custom-modal-width {
            max-width: 1000px;
            margin-top: -50px;
        }

        .header_modal {
            text-align: center;
            padding: 10px;
            font-size: 1.4rem;
            border-bottom: 1px solid #333;
        }

        .card__info {
            display: flex;
            justify-content: space-around;
            align-items: center;
            width: 100%;
            margin: 20px 0;
            padding: 10px 20px;
            box-shadow: rgba(0, 0, 0, 0.16) 0px 1px 4px;
        }

        .card__info-avt {
            width: 20%;
        }

        .card__info-avt img {
            width: 80px;
            height: 80px;
            border-radius: 50%;
        }

        .card__info-mainInfo {
            width: 20%;
        }

        .card__info-detailInfo {
            width: 20%;
        }

        .card__info-totalOrder {
            width: 40%;
        }

        .list_order-user{
            max-height: 300px;
            overflow: auto;
            position: relative;
        }

        .list_order-user thead th {
            position: sticky;
            top: 0;
            background-color: #fff; 
            z-index: 1;
        }
    </style>
@endsection

@section('content')
    <div class="col-md-12">
        <div class="row wrapper">
            <div class="col-md-8">

                {{-- chỗ này để search nè --}}
                <div class="row row-search">
                    <div class="col-md-12 ">
                        <form class="form-search">
                            <input placeholder="Tìm kiếm khách hàng..." />
                            <i class="fa-solid fa-magnifying-glass"></i>
                        </form>
                    </div>
                </div>

                {{-- Chỗ này để lọc user nè --}}
                <div class="row row-filter">
                    <div class="filter-user">
                        <label for="user-filter">Tên:</label>
                        <select id="user-filter" class="dropdown">
                            <option value="">Tất cả</option>
                            <option value="user1">Nguyễn Xuân Bính</option>
                            <option value="user2">Bính</option>
                            <option value="user3">Binh</option>
                        </select>
                    </div>
                    <div class="filter-user">
                        <label for="user-filter">Trạng thái</label>
                        <select id="user-filter" class="dropdown">
                            <option value="">Tất cả</option>
                            <option value="user1">Active</option>
                            <option value="user2">Inactive</option>
                        </select>
                    </div>
                </div>

                {{-- Chỗ này là bảng dữ liệu khách hàng, xem trước: tên, ngày tạo, trạng thái --}}
                <div class="row row-data">
                    <div class="table-user">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Ảnh</th>
                                    <th>Tên</th>
                                    <th>Ngày tạo</th>
                                    <th>Trạng thái</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="avata-user align-middle">
                                        <img src="https://picsum.photos/200/300" alt="avatar" />
                                    </td>
                                    <td class="align-middle">Xuân Bính nè</td>
                                    <td class="align-middle">20/11/2024</td>
                                    <td class="align-middle">Active</td>
                                    <td class="btn-preview align-middle">
                                        <button>Xem trước</button>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="avata-user align-middle">
                                        <img src="https://picsum.photos/200/300" alt="avatar" />
                                    </td>
                                    <td class="align-middle">Xuân Bính nè</td>
                                    <td class="align-middle">20/11/2024</td>
                                    <td class="align-middle">Active</td>
                                    <td class="btn-preview align-middle">
                                        <button>Xem trước</button>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="avata-user align-middle">
                                        <img src="https://picsum.photos/200/300" alt="avatar" />
                                    </td>
                                    <td class="align-middle">Xuân Bính nè</td>
                                    <td class="align-middle">20/11/2024</td>
                                    <td class="align-middle">Active</td>
                                    <td class="btn-preview align-middle">
                                        <button>Xem trước</button>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="avata-user align-middle">
                                        <img src="https://picsum.photos/200/300" alt="avatar" />
                                    </td>
                                    <td class="align-middle">Xuân Bính nè</td>
                                    <td class="align-middle">20/11/2024</td>
                                    <td class="align-middle">Active</td>
                                    <td class="btn-preview align-middle">
                                        <button>Xem trước</button>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="avata-user align-middle">
                                        <img src="https://picsum.photos/200/300" alt="avatar" />
                                    </td>
                                    <td class="align-middle">Xuân Bính nè</td>
                                    <td class="align-middle">20/11/2024</td>
                                    <td class="align-middle">Active</td>
                                    <td class="btn-preview align-middle">
                                        <button>Xem trước</button>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="avata-user align-middle">
                                        <img src="https://picsum.photos/200/300" alt="avatar" />
                                    </td>
                                    <td class="align-middle">Xuân Bính nè</td>
                                    <td class="align-middle">20/11/2024</td>
                                    <td class="align-middle">Active</td>
                                    <td class="btn-preview align-middle">
                                        <button>Xem trước</button>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="avata-user align-middle">
                                        <img src="https://picsum.photos/200/300" alt="avatar" />
                                    </td>
                                    <td class="align-middle">Xuân Bính nè</td>
                                    <td class="align-middle">20/11/2024</td>
                                    <td class="align-middle">Active</td>
                                    <td class="btn-preview align-middle">
                                        <button>Xem trước</button>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="avata-user align-middle">
                                        <img src="https://picsum.photos/200/300" alt="avatar" />
                                    </td>
                                    <td class="align-middle">Xuân Bính nè</td>
                                    <td class="align-middle">20/11/2024</td>
                                    <td class="align-middle">Active</td>
                                    <td class="btn-preview align-middle">
                                        <button>Xem trước</button>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="avata-user align-middle">
                                        <img src="https://picsum.photos/200/300" alt="avatar" />
                                    </td>
                                    <td class="align-middle">Xuân Bính nè</td>
                                    <td class="align-middle">20/11/2024</td>
                                    <td class="align-middle">Active</td>
                                    <td class="btn-preview align-middle">
                                        <button>Xem trước</button>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="avata-user align-middle">
                                        <img src="https://picsum.photos/200/300" alt="avatar" />
                                    </td>
                                    <td class="align-middle">Xuân Bính nè</td>
                                    <td class="align-middle">20/11/2024</td>
                                    <td class="align-middle">Active</td>
                                    <td class="btn-preview align-middle">
                                        <button>Xem trước</button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- phần này là xem trước thông tin khách hàng --}}
            <div class="col-md-4">
                <div class="card-preview">
                    <div class="title-preview">
                        <span>Xem trước thông tin</span>
                    </div>

                    <div class="info-user">

                        <div class="row row-input">
                            <div class="col-md-4 left-col">
                                <label>Tên:</label>
                            </div>
                            <div class="col-md-8 right-col">
                                <input value="Bính nè" />
                            </div>
                        </div>

                        <div class="row row-input">
                            <div class="col-md-4 left-col">
                                <label>Tên đăng nhập:</label>
                            </div>
                            <div class="col-md-8 right-col">
                                <input value="binhne" />
                            </div>
                        </div>

                        <div class="row row-input">
                            <div class="col-md-4 left-col">
                                <label>Email:</label>
                            </div>
                            <div class="col-md-8 right-col">
                                <input value="binhne@gmail.com" />
                            </div>
                        </div>

                        <div class="row row-input">
                            <div class="col-md-4 left-col">
                                <label>Điện thoại:</label>
                            </div>
                            <div class="col-md-8 right-col">
                                <input value="0123456789" />
                            </div>
                        </div>

                        <div class="row row-input">
                            <div class="col-md-4 left-col">
                                <label>Trạng thái:</label>
                            </div>
                            <div class="col-md-8 right-col">
                                <input value="Active" />
                            </div>
                        </div>

                        <div class="row row-action">
                            <button class="btn-viewDetail" data-bs-toggle="modal" data-bs-target="#myModal">Xem chi
                                tiết</button>
                            <button class="btn-blockAccount">Khóa tài khoản</button>
                            <button class="btn-DelAccount">Xóa tài khoản</button>
                        </div>

                        {{-- Phần modal thông tin chi tiết khách hàng --}}
                        <div class="modal" id="myModal">
                            <div class="modal-dialog custom-modal-width">
                                <div class="modal-content">

                                    <!-- Modal body -->
                                    <div class="modal-body">
                                        <div class="header_modal">
                                            <span>Thông tin chi tiết khách hàng</span>
                                        </div>

                                        <div class="modal_content">
                                            <div class="card__info">
                                                <div class="card__info-avt">
                                                    <img src="https://picsum.photos/200/300" alt="avt" />
                                                </div>
                                                <div class="card__info-mainInfo">
                                                    <h5 class="info-name">Nguyễn Xuân Bính</h5>
                                                    Trạng thái:<span> Active</span>
                                                </div>
                                                <div class="card__info-detailInfo">
                                                    Mã khách hàng:<h5> KH001</h5>
                                                    Ngày đăng ký:<h5> 20/11/2024</h5>
                                                </div>
                                                <div class="card__info-totalOrder">
                                                    Tổng đơn hàng:<h5> 1000</h5>
                                                </div>
                                            </div>
                                            <div class="list_order-user">
                                                <table class="table table-hover">
                                                    <thead>
                                                        <tr>
                                                            <th>Mã đơn</th>
                                                            <th>Ngày đặt</th>
                                                            <th>Ngày nhận</th>
                                                            <th>Trạng thái đơn</th>
                                                            <th>Tổng tiền</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td>DH201124</td>
                                                            <td>20/11/2024</td>
                                                            <td>20/11/2024</td>
                                                            <td>Đã nhận</td>
                                                            <td>1.000 vnđ</td>
                                                        </tr>
                                                        <tr>
                                                            <td>DH201124</td>
                                                            <td>20/11/2024</td>
                                                            <td>20/11/2024</td>
                                                            <td>Đã nhận</td>
                                                            <td>1.000 vnđ</td>
                                                        </tr>
                                                        <tr>
                                                            <td>DH201124</td>
                                                            <td>20/11/2024</td>
                                                            <td>20/11/2024</td>
                                                            <td>Đã nhận</td>
                                                            <td>1.000 vnđ</td>
                                                        </tr>
                                                        <tr>
                                                            <td>DH201124</td>
                                                            <td>20/11/2024</td>
                                                            <td>20/11/2024</td>
                                                            <td>Đã nhận</td>
                                                            <td>1.000 vnđ</td>
                                                        </tr>
                                                        <tr>
                                                            <td>DH201124</td>
                                                            <td>20/11/2024</td>
                                                            <td>20/11/2024</td>
                                                            <td>Đã nhận</td>
                                                            <td>1.000 vnđ</td>
                                                        </tr>
                                                        <tr>
                                                            <td>DH201124</td>
                                                            <td>20/11/2024</td>
                                                            <td>20/11/2024</td>
                                                            <td>Đã nhận</td>
                                                            <td>1.000 vnđ</td>
                                                        </tr>
                                                        <tr>
                                                            <td>DH201124</td>
                                                            <td>20/11/2024</td>
                                                            <td>20/11/2024</td>
                                                            <td>Đã nhận</td>
                                                            <td>1.000 vnđ</td>
                                                        </tr>
                                                        <tr>
                                                            <td>DH201124</td>
                                                            <td>20/11/2024</td>
                                                            <td>20/11/2024</td>
                                                            <td>Đã nhận</td>
                                                            <td>1.000 vnđ</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Modal footer -->
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@endsection
