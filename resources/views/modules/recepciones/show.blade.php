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
                                        <td>{{ \Carbon\Carbon::parse($recepcion->fecha_ingreso)->format('d/m/Y') }}</td>
                                    </tr>
                                    <tr>
                                        <th>Hora Recepción:</th>
                                        <td>{{ \Carbon\Carbon::parse($recepcion->hora_ingreso)->format('H:i') }}</td>
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
                                <div class="col-12 mb-4">
                                    <div class="card card-info">
                                        <div class="card-header">
                                            <h5>{{ $equipo->nombre }} <small class="text-muted">({{ $equipo->tipo }})</small></h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <!-- Campos comunes -->
                                                <div class="col-md-4 col-sm-6">
                                                    <div class="form-group">
                                                        <label><strong>Número de Serie</strong></label>
                                                        <p class="form-control-static">{{ $equipo->numero_serie ?? 'N/A' }}</p>
                                                    </div>
                                                </div>
                                                
                                                <!-- Campos específicos por tipo -->
                                                @if($equipo->tipo == 'MOTOR_ELECTRICO')
                                                <div class="col-md-4 col-sm-6">
                                                    <div class="form-group">
                                                        <label><strong>Marca</strong></label>
                                                        <p class="form-control-static">{{ $equipo->marca ?? 'N/A' }}</p>
                                                    </div>
                                                </div>
                                                <div class="col-md-4 col-sm-6">
                                                    <div class="form-group">
                                                        <label><strong>Modelo</strong></label>
                                                        <p class="form-control-static">{{ $equipo->modelo ?? 'N/A' }}</p>
                                                    </div>
                                                </div>
                                                <div class="col-md-4 col-sm-6">
                                                    <div class="form-group">
                                                        <label><strong>Color</strong></label>
                                                        <p class="form-control-static">{{ $equipo->color ?? 'N/A' }}</p>
                                                    </div>
                                                </div>
                                                <div class="col-md-4 col-sm-6">
                                                    <div class="form-group">
                                                        <label><strong>Voltaje</strong></label>
                                                        <p class="form-control-static">{{ $equipo->voltaje ?? 'N/A' }}</p>
                                                    </div>
                                                </div>
                                                <div class="col-md-4 col-sm-6">
                                                    <div class="form-group">
                                                        <label><strong>HP</strong></label>
                                                        <p class="form-control-static">{{ $equipo->hp ?? 'N/A' }}</p>
                                                    </div>
                                                </div>
                                                <div class="col-md-4 col-sm-6">
                                                    <div class="form-group">
                                                        <label><strong>RPM</strong></label>
                                                        <p class="form-control-static">{{ $equipo->rpm ?? 'N/A' }}</p>
                                                    </div>
                                                </div>
                                                <div class="col-md-4 col-sm-6">
                                                    <div class="form-group">
                                                        <label><strong>Hz</strong></label>
                                                        <p class="form-control-static">{{ $equipo->hz ?? 'N/A' }}</p>
                                                    </div>
                                                </div>
                                                
                                                @elseif($equipo->tipo == 'MAQUINA_SOLDADORA')
                                                <div class="col-md-4 col-sm-6">
                                                    <div class="form-group">
                                                        <label><strong>Marca</strong></label>
                                                        <p class="form-control-static">{{ $equipo->marca ?? 'N/A' }}</p>
                                                    </div>
                                                </div>
                                                <div class="col-md-4 col-sm-6">
                                                    <div class="form-group">
                                                        <label><strong>Modelo</strong></label>
                                                        <p class="form-control-static">{{ $equipo->modelo ?? 'N/A' }}</p>
                                                    </div>
                                                </div>
                                                <div class="col-md-4 col-sm-6">
                                                    <div class="form-group">
                                                        <label><strong>Color</strong></label>
                                                        <p class="form-control-static">{{ $equipo->color ?? 'N/A' }}</p>
                                                    </div>
                                                </div>
                                                <div class="col-md-4 col-sm-6">
                                                    <div class="form-group">
                                                        <label><strong>Voltaje</strong></label>
                                                        <p class="form-control-static">{{ $equipo->voltaje ?? 'N/A' }}</p>
                                                    </div>
                                                </div>
                                                <div class="col-md-4 col-sm-6">
                                                    <div class="form-group">
                                                        <label><strong>AMP</strong></label>
                                                        <p class="form-control-static">{{ $equipo->amperio ?? 'N/A' }}</p>
                                                    </div>
                                                </div>
                                                <div class="col-md-4 col-sm-6">
                                                    <div class="form-group">
                                                        <label><strong>Cable +</strong></label>
                                                        <p class="form-control-static">{{ $equipo->cable_positivo ?? 'N/A' }}</p>
                                                    </div>
                                                </div>
                                                <div class="col-md-4 col-sm-6">
                                                    <div class="form-group">
                                                        <label><strong>Cable -</strong></label>
                                                        <p class="form-control-static">{{ $equipo->cable_negativo ?? 'N/A' }}</p>
                                                    </div>
                                                </div>
                                                
                                                @elseif($equipo->tipo == 'GENERADOR_DINAMO')
                                                <div class="col-md-4 col-sm-6">
                                                    <div class="form-group">
                                                        <label><strong>Marca</strong></label>
                                                        <p class="form-control-static">{{ $equipo->marca ?? 'N/A' }}</p>
                                                    </div>
                                                </div>
                                                <div class="col-md-4 col-sm-6">
                                                    <div class="form-group">
                                                        <label><strong>Modelo</strong></label>
                                                        <p class="form-control-static">{{ $equipo->modelo ?? 'N/A' }}</p>
                                                    </div>
                                                </div>
                                                <div class="col-md-4 col-sm-6">
                                                    <div class="form-group">
                                                        <label><strong>Color</strong></label>
                                                        <p class="form-control-static">{{ $equipo->color ?? 'N/A' }}</p>
                                                    </div>
                                                </div>
                                                <div class="col-md-4 col-sm-6">
                                                    <div class="form-group">
                                                        <label><strong>Voltaje</strong></label>
                                                        <p class="form-control-static">{{ $equipo->voltaje ?? 'N/A' }}</p>
                                                    </div>
                                                </div>
                                                <div class="col-md-4 col-sm-6">
                                                    <div class="form-group">
                                                        <label><strong>RPM</strong></label>
                                                        <p class="form-control-static">{{ $equipo->rpm ?? 'N/A' }}</p>
                                                    </div>
                                                </div>
                                                <div class="col-md-4 col-sm-6">
                                                    <div class="form-group">
                                                        <label><strong>Hz</strong></label>
                                                        <p class="form-control-static">{{ $equipo->hz ?? 'N/A' }}</p>
                                                    </div>
                                                </div>
                                                <div class="col-md-4 col-sm-6">
                                                    <div class="form-group">
                                                        <label><strong>Kva/Kw</strong></label>
                                                        <p class="form-control-static">{{ $equipo->kva_kw ?? 'N/A' }}</p>
                                                    </div>
                                                </div>
                                                
                                                @else
                                                <!-- Otros equipos -->
                                                <div class="col-md-4 col-sm-6">
                                                    <div class="form-group">
                                                        <label><strong>Marca</strong></label>
                                                        <p class="form-control-static">{{ $equipo->marca ?? 'N/A' }}</p>
                                                    </div>
                                                </div>
                                                <div class="col-md-4 col-sm-6">
                                                    <div class="form-group">
                                                        <label><strong>Modelo</strong></label>
                                                        <p class="form-control-static">{{ $equipo->modelo ?? 'N/A' }}</p>
                                                    </div>
                                                </div>
                                                <div class="col-md-4 col-sm-6">
                                                    <div class="form-group">
                                                        <label><strong>Color</strong></label>
                                                        <p class="form-control-static">{{ $equipo->color ?? 'N/A' }}</p>
                                                    </div>
                                                </div>
                                                <div class="col-md-4 col-sm-6">
                                                    <div class="form-group">
                                                        <label><strong>Voltaje</strong></label>
                                                        <p class="form-control-static">{{ $equipo->voltaje ?? 'N/A' }}</p>
                                                    </div>
                                                </div>
                                                <div class="col-md-4 col-sm-6">
                                                    <div class="form-group">
                                                        <label><strong>Potencia</strong></label>
                                                        <p class="form-control-static">{{ $equipo->potencia ?? 'N/A' }}</p>
                                                    </div>
                                                </div>
                                                @endif
                                                
                                                <!-- Campos comunes finales -->
                                                <div class="col-md-6 col-sm-12">
                                                    <div class="form-group">
                                                        <label><strong>Partes Faltantes</strong></label>
                                                        <p class="form-control-static">{{ $equipo->partes_faltantes ?? 'N/A' }}</p>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 col-sm-12">
                                                    <div class="form-group">
                                                        <label><strong>Observaciones</strong></label>
                                                        <p class="form-control-static">{{ $equipo->observaciones ?? 'N/A' }}</p>
                                                    </div>
                                                </div>
                                                @if($equipo->fotos->count() > 0)
                                                <div class="row mt-3">
                                                    <div class="col-md-12">
                                                        <p><strong>Fotos del Equipo:</strong></p>
                                                        <div class="row">
                                                            @foreach($equipo->fotos as $foto)
                                                                <div class="col-md-3 mb-3">
                                                                    <a href="{{ Storage::url($foto->ruta) }}" data-lightbox="equipo-{{ $equipo->id }}" data-title="{{ $equipo->nombre }}">
                                                                        <img src="{{ asset('storage/' . $foto->ruta) }}" class="img-thumbnail">
                                                                    </a>
                                                                    @if($foto->descripcion)
                                                                        <p class="small text-muted mt-1">{{ $foto->descripcion }}</p>
                                                                    @endif
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
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