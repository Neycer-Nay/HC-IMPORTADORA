
@extends('layouts.main')

@section('contenido')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1 style="color:#151414">Ingresos</h1>
                <div class="section-header-breadcrumb">
                    <a href="#" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Nuevo Ingreso
                    </a>
                </div>
            </div>
            <div class="section-body">
                <p style="font-size: 1.2em;">Aquí puedes gestionar los ingresos.</p>
            </div>
            <div class="d-none d-md-block">
                <table class="table table-striped" id="table-1">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Fecha</th>
                            
                            <th>Nombre Cuenta</th>
                            <th>Glosa</th>
                            <th>Razón Social</th>
                            <th>N° de Recibo</th>
                            <th>Método de Pago</th>
                            <th>Monto Total</th>                            
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1</td>
                            <td>--/--/----</td>
                            
                            <td>---</td>
                            <td>---</td>
                            <td>---</td>
                            <td>---</td>
                            <td>---</td>
                            <td>---</td>
                            
                            <td>
                                <a href="#" class="btn btn-info btn-sm" title="Ver"><i class="fas fa-eye"></i></a>
                                <a href="#" class="btn btn-warning btn-sm" title="Editar"><i class="fas fa-edit"></i></a>
                                <a href="#" class="btn btn-danger btn-sm" title="Eliminar"><i class="fas fa-trash"></i></a>
                            </td>
                        </tr>
                        <!-- Puedes agregar más filas de ejemplo aquí -->
                    </tbody>
                </table>
                <div class="d-flex justify-content-center">
                    {{-- Paginación aquí si la necesitas --}}
                </div>
            </div>
        </section>
    </div>
@endsection