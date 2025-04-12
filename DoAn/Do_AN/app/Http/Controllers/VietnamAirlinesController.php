<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ChuyenBay;


class VietnamAirlinesController extends Controller
{
    public function index()
    {
        $flights = ChuyenBay::paginate(12);
        return view('vietnam_airlines',compact('flights'));
    }
}
