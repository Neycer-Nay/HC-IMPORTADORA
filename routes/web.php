<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Dashboard;


Route::get('/', function () {
    return view('layouts.main');
});
Route::get('/home', [Dashboard::class, 'index'])->name('dashboard.index');
