<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class EgresosController extends Controller
{
    public function index()
    {
        
        return view('contabilidad.egresos.egresos');
    }
}
