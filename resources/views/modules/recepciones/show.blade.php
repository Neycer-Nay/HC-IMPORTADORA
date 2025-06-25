@extends('layouts.main')

@section('contenido')
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Detalle de Recepción</h1>
            <div class="section-header-breadcrumb">
                <a href="{{ route('recepciones.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Volver
                </a>
            </div>
        </div>

        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Recepción #{{ $recepcion->numero_recepcion }}</h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <h6>Información General</h6>
                                    <table class="table table-bordered">
                                        <tr>
                                            <th>Fecha Recepción:</th>
                                            <td>{{ $recepcion->fecha_ingreso->format('d/m/Y H:i') }}</td>
                                        </tr>
                                        <tr>
                                            <th>Cliente:</th>
                                            <td>{{ $recepcion->cliente->nombre }}</td>
                                        </tr>
                                        <tr>
                                            <th>Atendido por:</th>
                                            <td>{{ $recepcion->usuario->nombre }}</td>
                                        </tr>
                                        <tr>
                                            <th>Estado:</th>
                                            <td>
                                                <span class="badge badge-{{ 
                                                    $recepcion->estado == 'RECIBIDO' ? 'primary' : 
                                                    ($recepcion->estado == 'EN_REPARACION' ? 'warning' : 
                                                    ($recepcion->estado == 'REPARADO' ? 'success' : 'secondary'))
                                                }}">
                                                    {{ str_replace('_', ' ', $recepcion->estado) }}
                                                </span>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                                
                                <div class="col-md-6">
                                    <h6>Equipos Recibidos</h6>
                                    @foreach($recepcion->equipos as $equipo)
                                    <div class="card mb-3">
                                        <div class="card-header">
                                            <h5>{{ $equipo->nombre }}</h5>
                                        </div>
                                        <div class="card-body">
                                            <p><strong>Tipo:</strong> {{ $equipo->tipo }}</p>
                                            <p><strong>Marca:</strong> {{ $equipo->marca }}</p>
                                            <p><strong>Serie:</strong> {{ $equipo->numero_serie ?? 'N/A' }}</p>
                                            <!-- Más detalles del equipo según necesites -->
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection