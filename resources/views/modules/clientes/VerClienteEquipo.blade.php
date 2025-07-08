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
                        <p><strong>Teléfonos:</strong> {{ $cliente->telefono_1 }}
                            {{ $cliente->telefono_2 ? ' / ' . $cliente->telefono_2 : '' }}
                            {{ $cliente->telefono_3 ? ' / ' . $cliente->telefono_3 : '' }}
                        </p>
                        <p><strong>Email:</strong> {{ $cliente->email }}</p>
                        <p><strong>Ciudad y Dirección:</strong> {{ $cliente->ciudad }} - {{ $cliente->direccion }}</p>
                    </div>
                </div>

                <div class="card mt-4">
                    <div class="card-header">
                        <h5>Equipos Relacionados del cliente</h5>
                    </div>
                    <div class="row">
                        @forelse($cliente->equipos as $equipo)
                            <div class="col-md-4 mb-4">
                                <div class="card h-100 shadow-sm">
                                    <div class="card-body">
                                        <h5 class="card-title mb-1">{{ $equipo->nombre }}</h5>
                                        <p class="mb-1">
    <strong>N° Recepción:</strong>
    {{ $equipo->recepcion->numero_recepcion ?? 'Sin recepción asociada' }}
</p>
                                        <p class="mb-1"><strong>Fecha:</strong> {{ $equipo->created_at->format('d/m/Y') }}</p>
                                        <p class="mb-1"><strong>Tipo:</strong> {{ $equipo->tipo }}</p>
                                        <p class="mb-1"><strong>Marca:</strong> {{ $equipo->marca }}</p>
                                        <p class="mb-1"><strong>Modelo:</strong> {{ $equipo->modelo }}</p>
                                        <p class="mb-1"><strong>Serie:</strong> {{ $equipo->serie }}</p>
                                        <div>
                                            <strong>Fotos:</strong><br>
                                            @if($equipo->fotos && $equipo->fotos->count())
                                                @foreach($equipo->fotos as $foto)
                                                    <a href="{{ Storage::url($foto->ruta) }}" target="_blank"
                                                        data-lightbox="equipo-{{ $equipo->id }}" data-title="{{ $equipo->nombre }}">
                                                        <img src="{{ asset('storage/' . $foto->ruta) }}" alt="Foto" width="50"
                                                            class="img-thumbnail mb-1">
                                                    </a>
                                                @endforeach
                                            @else
                                                <span class="text-muted">Sin fotos</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-12">
                                <div class="alert alert-info">Este cliente no tiene equipos registrados.</div>
                            </div>
                        @endforelse
                    </div>
                </div>


                <div class="card mt-4">
                    <div class="card-header">
                        <h5>Recepciones asociadas al Cliente</h5>
                    </div>
                    <div class="row">
                        @forelse($cliente->recepciones as $recepcion)
                            <div class="col-md-4 mb-4">
                                <div class="card h-100 border-info">
                                    <div class="card-body">
                                        <h5 class="card-title mb-1">N° Recepción: {{ $recepcion->numero_recepcion }}</h5>
                                        <p class="mb-1"><strong>Fecha y hora:</strong>
                                            {{ \Carbon\Carbon::parse($recepcion->fecha_ingreso)->format('d/m/Y') }} -
                                            {{ $recepcion->hora_ingreso }}
                                        </p>
                                        <p class="mb-1"><strong>Estado:</strong>
                                            <span
                                                class="badge badge-{{ $recepcion->estado == 'EN_REPARACION' ? 'warning' : ($recepcion->estado == 'REPARADO' ? 'success' : 'secondary') }}">
                                                {{ str_replace('_', ' ', $recepcion->estado) }}
                                            </span>
                                        </p>
                                        <a href="{{ route('recepciones.show', $recepcion->id) }}"
                                            class="btn btn-info btn-sm mt-2" title="Ver">
                                            <i class="fas fa-eye"></i> Ver detalle
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-12">
                                <div class="alert alert-info">Este cliente no tiene recepciones registradas.</div>
                            </div>
                        @endforelse
                    </div>
                </div>

            </div>
        </section>
    </div>
@endsection