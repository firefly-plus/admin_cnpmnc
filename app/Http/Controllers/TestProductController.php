<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TestProductController extends Controller
{
    //
    public function index()
    {
        return view('product.testproduct-management');
    }
}
