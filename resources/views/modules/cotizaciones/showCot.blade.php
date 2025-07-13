@extends('layouts.main')

@section('contenido')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Detalle de la cotizacion N°: {{ $recepcion->numero_recepcion }}</h1>
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
                                        <strong>Fotos de la cotización:</strong><br>
                                        @if(isset($equipo->pivot->fotos))
                                            @php
                                                $fotoIds = json_decode($equipo->pivot->fotos, true);
                                                $fotosMostrar = App\Models\FotoEquipo::whereIn('id', $fotoIds)
                                                    ->where('equipo_id', $equipo->id)
                                                    ->get();
                                            @endphp

                                            @foreach($fotosMostrar as $foto)
                                                <img src="{{ Storage::url($foto->ruta) }}" alt="Foto de cotización" width="120"
                                                    class="img-thumbnail m-1">
                                            @endforeach
                                        @else
                                            <span class="text-muted">No se incluyeron fotos para este equipo</span>
                                        @endif
                                    </div>
                                    <a href="{{ route('cotizaciones.pdf', $recepcion->id) }}" class="btn btn-danger"
                                        target="_blank">
                                        <i class="fas fa-file-pdf"></i> Generar PDF
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach



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