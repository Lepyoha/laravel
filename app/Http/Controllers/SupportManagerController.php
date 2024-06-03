<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SupportManagerController extends Controller
{
    public function index()
    {
        return view('support.home');
    }
}
