<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class testCategoryController extends Controller
{
    public function index()
    {
        return view('category.testCategory');
    }
}
