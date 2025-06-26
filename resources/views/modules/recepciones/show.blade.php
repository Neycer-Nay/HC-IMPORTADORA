@extends('layouts.main')

@section('contenido')
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Detalle de Recepción</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active">
                    <a href="{{ route('recepciones.index') }}" class="btn btn-icon icon-left btn-secondary">
                        <i class="fas fa-arrow-left"></i> Volver
                    </a>
                </div>
            </div>
        </div>

        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <!-- Card principal con información de recepción -->
                    <div class="card card-primary">
                        <div class="card-header">
                            <h4>Recepción #{{ $recepcion->numero_recepcion }}</h4>
                            <div class="card-header-action">
                                <span class="badge badge-{{ 
                                    $recepcion->estado == 'RECIBIDO' ? 'primary' : 
                                    ($recepcion->estado == 'EN_REPARACION' ? 'warning' : 
                                    ($recepcion->estado == 'REPARADO' ? 'success' : 'secondary'))
                                }}">
                                    {{ str_replace('_', ' ', $recepcion->estado) }}
                                </span>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="section-title">Información General</div>
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <tr>
                                        <th width="40%">Fecha Recepción:</th>
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
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Card para los equipos recibidos -->
                    <div class="card card-secondary mt-4">
                        <div class="card-header">
                            <h4><i class="fas fa-laptop"></i> Equipos Recibidos</h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                @foreach($recepcion->equipos as $equipo)
                                <div class="col-md-6">
                                    <div class="card card-info">
                                        <div class="card-header">
                                            <h5>{{ $equipo->nombre }}</h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="list-group">
                                                <div class="list-group-item list-group-item-action flex-column align-items-start">
                                                    <div class="d-flex w-100 justify-content-between">
                                                        <small class="text-muted">Tipo</small>
                                                    </div>
                                                    <p class="mb-1">{{ $equipo->tipo }}</p>
                                                </div>
                                                <div class="list-group-item list-group-item-action flex-column align-items-start">
                                                    <div class="d-flex w-100 justify-content-between">
                                                        <small class="text-muted">Marca</small>
                                                    </div>
                                                    <p class="mb-1">{{ $equipo->marca }}</p>
                                                </div>
                                                <div class="list-group-item list-group-item-action flex-column align-items-start">
                                                    <div class="d-flex w-100 justify-content-between">
                                                        <small class="text-muted">Serie</small>
                                                    </div>
                                                    <p class="mb-1">{{ $equipo->numero_serie ?? 'N/A' }}</p>
                                                </div>
                                                <div class="list-group-item list-group-item-action flex-column align-items-start">
                                                    <div class="d-flex w-100 justify-content-between">
                                                        <small class="text-muted">Modelo</small>
                                                    </div>
                                                    <p class="mb-1">{{ $equipo->modelo ?? 'N/A' }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection