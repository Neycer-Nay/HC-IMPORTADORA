<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class Dashboard extends Controller
{
   
    
    public function index()
    {
        return view('modules.dashboard.home'); // Assuming you have a dashboard view at resources/views/dashboard/index.blade.php
    }
}
