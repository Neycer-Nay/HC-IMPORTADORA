@extends('layouts.main')

@section('contenido')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Editar Cotización de Recepción N° {{ $recepcion->numero_recepcion }}</h1>
                <div class="section-header-breadcrumb">
                    <a href="{{ route('cotizaciones.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Volver
                    </a>
                </div>
            </div>

            <div class="section-body">
                <!-- Datos del Cliente -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5>Datos del Cliente</h5>
                    </div>
                    <div class="card-body">
                        <p><strong>Nombre:</strong> {{ $recepcion->cliente->nombre }}</p>
                        <p><strong>Teléfono:</strong> {{ $recepcion->cliente->telefono_1 }}</p>
                        <p><strong>Email:</strong> {{ $recepcion->cliente->email }}</p>
                        <p><strong>Dirección:</strong> {{ $recepcion->cliente->ciudad }}
                            -{{ $recepcion->cliente->direccion }}</p>
                    </div>
                </div>

                <form action="{{ route('cotizaciones.store', $recepcion->id) }}" method="POST">
                    @csrf
                    
                    @foreach($recepcion->equipos as $equipo)
                        <div class="card mb-3">
                            <div class="card-body">
                                <div class="row">
                                    <!-- Información detallada del equipo -->
                                    <div class="col-md-6">
                                        <h5 class="mb-1">
                                            {{ $equipo->nombre }}
                                            <small class="text-muted">({{ $equipo->tipo }})</small>
                                        </h5>
                                        <p class="mb-1"><strong>Serie:</strong> {{ $equipo->numero_serie }}</p>
                                        @if($equipo->tipo == 'MOTOR_ELECTRICO')
                                        <p class="mb-1"><strong>Marca:</strong> {{ $equipo->marca }}</p>
                                        <p class="mb-1"><strong>Modelo:</strong> {{ $equipo->modelo }}</p>
                                        <p class="mb-1"><strong>Color:</strong> {{ $equipo->color ?? 'N/A' }}</p>
                                        <p class="mb-1"><strong>Voltaje:</strong> {{ $equipo->voltaje ?? 'N/A' }}</p>
                                        <p class="mb-1"><strong>HP:</strong> {{ $equipo->hp ?? 'N/A' }}</p>
                                        <p class="mb-1"><strong>RPM:</strong> {{ $equipo->rpm ?? 'N/A' }}</p>
                                        <p class="mb-1"><strong>Hz:</strong> {{ $equipo->hz ?? 'N/A' }}</p>
                                        @elseif($equipo->tipo == 'MAQUINA_SOLDADORA')   
                                        <p class="mb-1"><strong>Marca:</strong> {{ $equipo->marca }}</p>
                                        <p class="mb-1"><strong>Modelo:</strong> {{ $equipo->modelo }}</p>
                                        <p class="mb-1"><strong>Color:</strong> {{ $equipo->color ?? 'N/A' }}</p>
                                        <p class="mb-1"><strong>Voltaje:</strong> {{ $equipo->voltaje ?? 'N/A' }}</p>
                                        <p class="mb-1"><strong>Amp:</strong> {{ $equipo->amperaje ?? 'N/A' }}</p>
                                        <p class="mb-1"><strong>Cable +:</strong> {{ $equipo->cable_positivo ?? 'N/A' }}</p>
                                        <p class="mb-1"><strong>Cable +:</strong> {{ $equipo->cable_negativo ?? 'N/A' }}</p>

                                        @elseif($equipo->tipo == 'GENERADOR_DINAMO')
                                        <p class="mb-1"><strong>Marca:</strong> {{ $equipo->marca }}</p>
                                        <p class="mb-1"><strong>Modelo:</strong> {{ $equipo->modelo }}</p>
                                        <p class="mb-1"><strong>Color:</strong> {{ $equipo->color ?? 'N/A' }}</p>
                                        <p class="mb-1"><strong>Voltaje:</strong> {{ $equipo->voltaje ?? 'N/A' }}</p>
                                        <p class="mb-1"><strong>HP:</strong> {{ $equipo->hp ?? 'N/A' }}</p>
                                        <p class="mb-1"><strong>RPM:</strong> {{ $equipo->rpm ?? 'N/A' }}</p>
                                        <p class="mb-1"><strong>Hz:Kva/Kw</strong> {{ $equipo->hz ?? 'N/A' }}</p>
                                        <p class="mb-1"><strong>Kva/Kw:</strong> {{ $equipo->kva_kw ?? 'N/A' }}</p>

                                        @else
                                        <p class="mb-1"><strong>Marca:</strong> {{ $equipo->marca }}</p>
                                        <p class="mb-1"><strong>Modelo:</strong> {{ $equipo->modelo }}</p>
                                        <p class="mb-1"><strong>Color:</strong> {{ $equipo->color ?? 'N/A' }}</p>
                                        <p class="mb-1"><strong>Voltaje:</strong> {{ $equipo->voltaje ?? 'N/A' }}</p>
                                        <p class="mb-1"><strong>Potencia:</strong> {{ $equipo->potencia ?? 'N/A' }}</p>
                                        @endif
                                    </div>
                                    <!-- Formulario de edición -->
                                    <div class="col-md-6">
                                        <!-- Selección de fotos -->
                                        <div class="mb-2">
                                            <strong>Selecciona las fotos para el PDF:</strong><br>
                                            @if($equipo->fotos && $equipo->fotos->count())
                                                @foreach($equipo->fotos as $foto)
                                                    <label class="mr-2">
                                                        <input type="checkbox" name="equipos[{{ $equipo->id }}][fotos][]"
                                                            value="{{ $foto->id }}" {{ (isset(old('equipos')[$equipo->id]['fotos']) && in_array($foto->id, old('equipos')[$equipo->id]['fotos'])) ? 'checked' : '' }}> <img src="{{ asset('storage/' . $foto->ruta) }}" alt="Foto" width="100" height="100"
                                                            class="img-thumbnail mb-1">
                                                    </label>
                                                @endforeach
                                            @else
                                                <span class="text-muted">Sin fotos</span>
                                            @endif
                                        </div>

                                        <!-- Descripción y valor del trabajo -->
                                        <div class="form-row">
                                            <div class="form-group col-md-7">
                                                <label for="descripcion_{{ $equipo->id }}">Descripción del trabajo a
                                                    realizar</label>
                                                <textarea name="equipos[{{ $equipo->id }}][descripcion]"
                                                    id="descripcion_{{ $equipo->id }}" class="form-control"
                                                    rows="2">{{ old('equipos.' . $equipo->id . '.descripcion') }}</textarea>
                                            </div>
                                            <div class="form-group col-md-5">
                                                <label for="valor_trabajo_{{ $equipo->id }}">Valor del trabajo (Bs)</label>
                                                <input type="number" min="0" step="0.01"
                                                    name="equipos[{{ $equipo->id }}][valor_trabajo]"
                                                    id="valor_trabajo_{{ $equipo->id }}" class="form-control"
                                                    value="{{ old('equipos.' . $equipo->id . '.valor_trabajo', 0) }}">
                                            </div>
                                        </div>

                                        <!-- Repuestos a usar -->
                                        <div class="form-group">
                                            <label>Repuestos a usar</label>
                                            <div id="repuestos-container-{{ $equipo->id }}">
                                                @php
                                                    $repuestos = old('equipos.' . $equipo->id . '.repuestos_detalle', []);
                                                @endphp
                                                @if(empty($repuestos))
                                                    @php $repuestos = [['nombre' => '', 'cantidad' => '', 'precio' => '']]; @endphp
                                                @endif
                                                @foreach($repuestos as $index => $repuesto)
                                                    <div class="form-row mb-2">
                                                        <div class="col">
                                                            <input type="text"
                                                                name="equipos[{{ $equipo->id }}][repuestos_detalle][{{ $index }}][nombre]"
                                                                class="form-control" placeholder="Nombre repuesto"
                                                                value="{{ $repuesto['nombre'] ?? '' }}">
                                                        </div>
                                                        <div class="col">
                                                            <input type="number" min="1"
                                                                name="equipos[{{ $equipo->id }}][repuestos_detalle][{{ $index }}][cantidad]"
                                                                class="form-control" placeholder="Cantidad"
                                                                value="{{ $repuesto['cantidad'] ?? '' }}">
                                                        </div>
                                                        <div class="col">
                                                            <input type="number" min="0" step="0.01"
                                                                name="equipos[{{ $equipo->id }}][repuestos_detalle][{{ $index }}][precio]"
                                                                class="form-control" placeholder="Precio unitario"
                                                                value="{{ $repuesto['precio'] ?? '' }}">
                                                        </div>
                                                        <div class="col-auto">
                                                            <button type="button" class="btn btn-danger btn-sm remove-repuesto"
                                                                onclick="this.parentNode.parentNode.remove();">-</button>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                            <button type="button" class="btn btn-secondary btn-sm"
                                                onclick="agregarRepuesto({{ $equipo->id }})">Agregar repuesto</button>

                                        </div>
                                        <button type="submit" class="btn btn-success">
                                            <i class="fas fa-save"></i> Guardar Cotización
                                        </button>
                                        <a href="{{ route('cotizaciones.pdf', $recepcion->id) }}" class="btn btn-danger"
                                            target="_blank">
                                            <i class="fas fa-file-pdf"></i> Generar PDF
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach


                </form>
            </div>
        </section>
    </div>
@endsection
<script>
    function agregarRepuesto(equipoId) {
        var container = document.getElementById('repuestos-container-' + equipoId);
        var index = container.querySelectorAll('.form-row').length;
        var html = `
        <div class="form-row mb-2">
            <div class="col">
                <input type="text" name="equipos[${equipoId}][repuestos_detalle][${index}][nombre]" class="form-control" placeholder="Nombre repuesto">
            </div>
            <div class="col">
                <input type="number" min="1" name="equipos[${equipoId}][repuestos_detalle][${index}][cantidad]" class="form-control" placeholder="Cantidad">
            </div>
            <div class="col">
                <input type="number" min="0" step="0.01" name="equipos[${equipoId}][repuestos_detalle][${index}][precio]" class="form-control" placeholder="Precio unitario">
            </div>
            <div class="col-auto">
                <button type="button" class="btn btn-danger btn-sm remove-repuesto" onclick="this.parentNode.parentNode.remove();">-</button>
            </div>
        </div>
        `;
        container.insertAdjacentHTML('beforeend', html);
    }
</script>