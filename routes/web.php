<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Dashboard;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\EquipoController;
use App\Http\Controllers\RecepcionController;
use App\Http\Controllers\AuthController;


// Crear un usuario administrador, solo usar una vez
Route::get('/crear-admin', [AuthController::class, 'crearAdmin']);

Route::get('/', [AuthController::class, 'index'])->name('login');
Route::post('/logear', [AuthController::class, 'logear'])->name('logear');


Route::middleware("auth")->group(function () {
    Route::get('/home', [Dashboard::class, 'index'])->name('dashboard.index');
    Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
});


Route::prefix('clientes')->middleware('auth')->group(function () {
    Route::get('/', [ClienteController::class, 'index'])->name('clientes.index');
    Route::get('/create', [ClienteController::class, 'create'])->name('clientes.create');
    Route::post('/', [ClienteController::class, 'store'])->name('clientes.store');
    Route::get('/{cliente}', [ClienteController::class, 'show'])->name('clientes.show');
    Route::get('/{cliente}/edit', [ClienteController::class, 'edit'])->name('clientes.edit');
    Route::put('/{cliente}', [ClienteController::class, 'update'])->name('clientes.update');
    Route::delete('/{cliente}', [ClienteController::class, 'destroy'])->name('clientes.destroy');
});


Route::prefix('recepciones')->middleware('auth')->group(function () {
    Route::get('/', [RecepcionController::class, 'index'])->name('recepciones.index');
    Route::get('/create', [RecepcionController::class, 'create'])->name('recepciones.create');
    Route::post('/', [RecepcionController::class, 'store'])->name('recepciones.store');
    Route::get('/{recepcion}', [RecepcionController::class, 'show'])->name('recepciones.show');
    Route::get('/{recepcion}/edit', [RecepcionController::class, 'edit'])->name('recepciones.edit');
    Route::put('/{recepcion}', [RecepcionController::class, 'update'])->name('recepciones.update');
    Route::delete('/{recepcion}', [RecepcionController::class, 'destroy'])->name('recepciones.destroy');
});


 
Route::prefix(('equipos'))->middleware('auth')->group(function () {
    Route::get('/', [EquipoController::class, 'index'])->name('equipos.index');
    Route::get('/create', [EquipoController::class, 'create'])->name('equipos.create');
    Route::post('/', [EquipoController::class, 'store'])->name('equipos.store');
    Route::get('/{equipo}', [EquipoController::class, 'show'])->name('equipos.show');
    Route::get('/{equipo}/edit', [EquipoController::class, 'edit'])->name('equipos.edit');
    Route::put('/{equipo}', [EquipoController::class, 'update'])->name('equipos.update');
    Route::delete('/{equipo}', [EquipoController::class, 'destroy'])->name('equipos.destroy');
});

Route::prefix('usuarios')->middleware('auth')->group(function () {
    Route::get('/', [\App\Http\Controllers\UsuariosController::class, 'index'])->name('usuarios.index');
    Route::get('/create', [\App\Http\Controllers\UsuariosController::class, 'create'])->name('usuarios.create');
    Route::post('/', [\App\Http\Controllers\UsuariosController::class, 'store'])->name('usuarios.store');
    Route::get('/{usuario}', [\App\Http\Controllers\UsuariosController::class, 'show'])->name('usuarios.show');
    Route::get('/{usuario}/edit', [\App\Http\Controllers\UsuariosController::class, 'edit'])->name('usuarios.edit');
    Route::put('/{usuario}', [\App\Http\Controllers\UsuariosController::class, 'update'])->name('usuarios.update');
    Route::delete('/{usuario}', [\App\Http\Controllers\UsuariosController::class, 'destroy'])->name('usuarios.destroy');
});

Route::prefix('cotizaciones')->middleware('auth')->group(function () {
    Route::get('/', [\App\Http\Controllers\CotizacionController::class, 'index'])->name('cotizaciones.index');
    Route::get('/create', [\App\Http\Controllers\CotizacionController::class, 'create'])->name('cotizaciones.create');
    Route::post('/', [\App\Http\Controllers\CotizacionController::class, 'store'])->name('cotizaciones.store');
    Route::get('/{id}/edit', [\App\Http\Controllers\CotizacionController::class, 'edit'])->name('cotizaciones.edit');
    // Aquí puedes agregar más rutas según sea necesario
});

