<?php

namespace App\Http\Controllers;

use App\Models\Recepcion;
use Illuminate\Http\Request;

class RecepcionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        
        return view('modules.recepciones.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('modules.recepciones.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Recepcion $recepcion)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Recepcion $recepcion)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Recepcion $recepcion)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Recepcion $recepcion)
    {
        //
    }
}
