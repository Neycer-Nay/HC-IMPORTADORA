@extends('layouts.main')

@section('contenido')
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Detalle del Cliente</h1>
            <div class="section-header-breadcrumb">
                <a href="{{ route('clientes.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Volver
                </a>
            </div>
        </div>
        <div class="section-body">
            <div class="card">
                <div class="card-header">
                    <h4>{{ $cliente->nombre }}</h4>
                </div>
                <div class="card-body">
                    <p><strong>Tipo:</strong> {{ $cliente->tipo }}</p>
                    <p><strong>Documento:</strong> {{ $cliente->tipo_documento }} - {{ $cliente->numero_documento }}</p>
                    <p><strong>Teléfonos:</strong> {{ $cliente->telefono_1 }} {{ $cliente->telefono_2 ? ' / ' . $cliente->telefono_2 : '' }} {{ $cliente->telefono_3 ? ' / ' . $cliente->telefono_3 : '' }}</p>
                    <p><strong>Email:</strong> {{ $cliente->email }}</p>
                    <p><strong>Ciudad y Dirección:</strong> {{ $cliente->ciudad }} - {{ $cliente->direccion }}</p>
                </div>
            </div>
            
            <div class="card mt-4">
                <div class="card-header">
                    <h5>Equipos Relacionados</h5>
                </div>
                <div class="card-body">
                    @if($cliente->equipos->isEmpty())
                        <div class="alert alert-info">Este cliente no tiene equipos registrados.</div>
                    @else
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Nombre</th>
                                    <th>Tipo</th>
                                    <th>Marca</th>
                                    <th>Modelo</th>
                                    <th>Serie</th>
                                    <th>Fotos</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($cliente->equipos as $equipo)
                                    <tr>
                                        <td>{{ $equipo->nombre }}</td>
                                        <td>{{ $equipo->tipo }}</td>
                                        <td>{{ $equipo->marca }}</td>
                                        <td>{{ $equipo->modelo }}</td>
                                        <td>{{ $equipo->serie }}</td>
                                        <td>
                                            @if($equipo->fotos && $equipo->fotos->count())
                                                @foreach($equipo->fotos as $foto)
                                                    <a href="{{ Storage::url($foto->ruta) }}" target="_blank" data-lightbox="equipo-{{ $equipo->id }}" data-title="{{ $equipo->nombre }}">
                                                        <img src="{{ asset('storage/' . $foto->ruta) }}" alt="Foto" width="50" class="img-thumbnail mb-1">
                                                    </a>
                                                @endforeach
                                            @else
                                                <span class="text-muted">Sin fotos</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>
            <!-- Se muestra las recepciones en la que esta el cliente
            <div class="card mt-4">
                <div class="card-header">
                    <h5>Recepciones del Cliente</h5>
                </div>
                <div class="card-body">
                    @if($cliente->recepciones->isEmpty())
                        <div class="alert alert-info">Este cliente no tiene recepciones registradas.</div>
                    @else
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>N° Recepción</th>
                                    <th>Fecha y hora</th>
                                    <th>Estado</th>
                                    <th>Ver</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($cliente->recepciones as $recepcion)
                                    <tr>
                                        <td>{{ $recepcion->numero_recepcion }}</td>
                                        <td>{{ \Carbon\Carbon::parse($recepcion->fecha_ingreso)->format('d/m/Y') }}-{{ $recepcion->hora_ingreso  }}</td>
                                        <td>{{ $recepcion->estado }}</td>
                                        <td>
                                            <a href="{{ route('recepciones.show', $recepcion->id) }}" class="btn btn-info btn-sm" title="Ver">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>-->
        </div>
    </section>
</div>
@endsection