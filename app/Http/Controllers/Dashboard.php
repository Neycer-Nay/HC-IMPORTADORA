<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


class Dashboard extends Controller
{
   
    
    public function index()
    {   
        $totalClientes = \App\Models\Cliente::count();
        $totalRecepciones = \App\Models\Recepcion::count();
        $totalUsuarios = \App\Models\User::count();
        $totalEquipos = \App\Models\Equipo::count();
        $totalCotizaciones = \App\Models\Cotizacion::count();
        return view('modules.dashboard.home', compact('totalClientes','totalRecepciones', 'totalUsuarios','totalEquipos', 'totalCotizaciones')); // Assuming you have a dashboard view at resources/views/dashboard/index.blade.php
    }
}
