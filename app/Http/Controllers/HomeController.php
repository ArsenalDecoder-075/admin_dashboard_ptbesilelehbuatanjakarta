<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Supplier;

class HomeController extends Controller
{
    public function index()
    {
        $suppliers = Supplier::all(); // fetch all suppliers
        return view('dashboard', compact('suppliers'));
    }
}
