@extends('layout.index')
@section('title','Management Promotion')
@section('css')
<style>
.promotion-info {
    max-width: 800px; 
    margin: 0 auto;
}

.form-row {
    display: flex;
    justify-content: space-between; 
    gap: 10px; 
    align-items: flex-start;
}

.form-group {
    flex: 1;
    min-width: 150px;
}

label {
    display: block;
    margin-bottom: 5px;
}

input.form-control {
    width: 100%;
    padding: 8px;
    font-size: 14px;
}

.product-list {
    max-width: 1000px;
    margin: 0 auto;
}

.product-grid {
    display: flex;
    flex-wrap: wrap;
    gap: 15px; 
    justify-content: space-between; 
}

.product-card {
    width: calc(25% - 15px); 
    border: 1px solid #ddd;
    border-radius: 8px;
    padding: 10px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    text-align: center;
}

.product-card input {
    margin-bottom: 10px;
}

.product-card p {
    margin: 5px 0;
}

button#apply-discount {
    margin-top: 20px;
    display: block;
    width: 200px;
    margin-left: auto;
    margin-right: auto;
}
.product-checkbox{
    float: left;
}
.form-row {
    display: flex;
    justify-content: space-between;
    gap: 10px; /* Khoảng cách giữa các ô */
    align-items: flex-start;
    margin-top: 10px; /* Tạo khoảng cách với phần trên */
}

.form-group {
    flex: 1;
    min-width: 150px;
}

label {
    display: block;
    margin-bottom: 5px;
}

select.form-control {
    width: 100%;
    padding: 8px;
    font-size: 14px;
}


</style>
@endsection

@section('content')
<div class="container">
    <!-- Phần thông tin giảm giá -->
    <div class="promotion-info">
        <h3>Promotion Information</h3>
        <form id="promotion-form">
            <div class="form-row">
                <div class="form-group">
                    <label for="discount">Discount Percentage (%):</label>
                    <input type="number" id="discount" name="discount" class="form-control" placeholder="Enter discount percentage" min="1" max="100" required>
                </div>
                <div class="form-group">
                    <label for="start_date">Start Date:</label>
                    <input type="date" id="start_date" name="start_date" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="end_date">End Date:</label>
                    <input type="date" id="end_date" name="end_date" class="form-control" required>
                </div>
            </div>
        </form>
    
        <!-- 3 Select Boxes -->
        <div class="form-row">
            <div class="form-group">
                <select id="category" name="category" class="form-control">
                    <option value="">Select Category</option>
                    <option value="1">Category 1</option>
                    <option value="2">Category 2</option>
                    <option value="3">Category 3</option>
                </select>
            </div>
            <div class="form-group">
                <select id="product_type" name="product_type" class="form-control">
                    <option value="">Select Product Type</option>
                    <option value="1">Type 1</option>
                    <option value="2">Type 2</option>
                    <option value="3">Type 3</option>
                </select>
            </div>
            <div class="form-group">
                <select id="product" name="product" class="form-control">
                    <option value="">Select Product</option>
                    <option value="1">Product 1</option>
                    <option value="2">Product 2</option>
                    <option value="3">Product 3</option>
                </select>
            </div>
        </div>
    </div>
    
    
    <div class="product-list">
        <h3>Product List</h3>
        <div class="product-grid">
            <div class="product-card">
                <input type="checkbox" class="product-checkbox" data-product-id="1">
                <div style="clear: both"></div>
                <p><img style="width: 150px;" src="{{ asset('images/team.jpg') }}" alt="Team"></p>

                <p><strong>Product Name:</strong> Product 1</p>
                <p><strong>Price:</strong> $100</p>
            </div>
            <div class="product-card">
                <input type="checkbox" class="product-checkbox" data-product-id="2">
                <p><strong>Product Name:</strong> Product 2</p>
                <p><strong>Price:</strong> $120</p>
            </div>
            <div class="product-card">
                <input type="checkbox" class="product-checkbox" data-product-id="3">
                <p><strong>Product Name:</strong> Product 3</p>
                <p><strong>Price:</strong> $150</p>
            </div>
            <div class="product-card">
                <input type="checkbox" class="product-checkbox" data-product-id="4">
                <p><strong>Product Name:</strong> Product 4</p>
                <p><strong>Price:</strong> $200</p>
            </div>
        </div>
        <button type="button" id="apply-discount" class="btn btn-success">Apply Discount</button>
    </div>
    
</div>
@endsection

@section('js')

@endsection
