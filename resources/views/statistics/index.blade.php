@extends('layout.index')
@section('title', 'Báo Cáo Doanh Thu')
@section('css')
<style>
   
</style>
@endsection

@section('content')
<div class="container-fluid py-4">
    <!-- Sales -->
    @include('statistics.sales')
    @include('statistics.chart')
    @include('statistics.revenue')

</div>
 
@endsection

@section('js')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

@endsection
